<?php

namespace App\Services;

use App\Exceptions\InvalidOrderTransition;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

/**
 * Order state machine. Admins transition orders through a strictly validated
 * set of allowed transitions. Cancelling/refunding restores inventory exactly
 * once (guarded by orders.stock_restored).
 */
class OrderService
{
    protected const TRANSITIONS = [
        Order::STATUS_PENDING => [Order::STATUS_CANCELLED],
        Order::STATUS_PAID => [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_CANCELLED, Order::STATUS_REFUNDED],
        Order::STATUS_PROCESSING => [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED, Order::STATUS_REFUNDED],
        Order::STATUS_SHIPPED => [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED, Order::STATUS_REFUNDED],
        Order::STATUS_DELIVERED => [Order::STATUS_REFUNDED, Order::STATUS_CANCELLED],
        Order::STATUS_CANCELLED => [],
        Order::STATUS_REFUNDED => [],
    ];

    protected InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * @return string[] allowed destination statuses from the given state
     */
    public function allowedTransitions(string $status): array
    {
        return self::TRANSITIONS[$status] ?? [];
    }

    /**
     * Whether a transition may legally be performed.
     */
    public function canTransition(Order $order, string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions($order->status), true);
    }

    /**
     * Transition an order to a new status (with inventory restoration when
     * the order is cancelled or refunded).
     */
    public function transition(Order $order, string $newStatus, ?string $trackingNumber = null): Order
    {
        if (! in_array($newStatus, [
            Order::STATUS_PENDING,
            Order::STATUS_PAID,
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
            Order::STATUS_REFUNDED,
        ], true)) {
            throw new InvalidOrderTransition($order->status, $newStatus);
        }

        if (! $this->canTransition($order, $newStatus)) {
            throw new InvalidOrderTransition($order->status, $newStatus);
        }

        return DB::transaction(function () use ($order, $newStatus, $trackingNumber) {
            $order->status = $newStatus;

            if ($trackingNumber !== null) {
                $order->tracking_number = $trackingNumber;
            }

            match ($newStatus) {
                Order::STATUS_PAID => $order->paid_at = $order->paid_at ?? now(),
                Order::STATUS_SHIPPED => $order->shipped_at = now(),
                Order::STATUS_DELIVERED => $order->delivered_at = now(),
                Order::STATUS_CANCELLED => $order->cancelled_at = now(),
                Order::STATUS_REFUNDED => $order->refunded_at = now(),
                default => null,
            };

            $order->save();

            if (in_array($newStatus, [Order::STATUS_CANCELLED, Order::STATUS_REFUNDED], true)) {
                $this->restoreInventory($order);
            }

            return $order;
        });
    }

    /**
     * Confirm that payment for this order has been received.
     */
    public function markPaid(Order $order, ?string $reference = null): Order
    {
        $order->payment_status = Order::PAYMENT_PAID;
        $order->paid_at = $order->paid_at ?? now();

        if ($order->status === Order::STATUS_PENDING) {
            $order->status = Order::STATUS_PAID;
        }

        if ($reference !== null && ! $order->payment_reference) {
            $order->payment_reference = $reference;
        }
        $order->save();

        return $order;
    }

    public function markPaymentFailed(Order $order, ?string $reason = null): Order
    {
        $order->payment_status = Order::PAYMENT_FAILED;
        $order->save();

        return $order;
    }

    /**
     * Return sold inventory to stock when an order is cancelled or refunded.
     * Guarded by orders.stock_restored so restoration happens exactly once.
     */
    protected function restoreInventory(Order $order): void
    {
        if ($order->stock_restored) {
            return;
        }

        $reason = $order->status === Order::STATUS_REFUNDED
            ? InventoryService::REASON_ORDER_REFUNDED
            : InventoryService::REASON_ORDER_CANCELLED;

        foreach ($order->items as $item) {
            if ($item->ecommerce_id && $item->quantity > 0) {
                $book = \App\Models\Ecommerce::find($item->ecommerce_id);
                if ($book) {
                    $this->inventory->restock($book, $item->quantity, $reason, $order, $order->user);
                }
            }
        }

        $order->forceFill(['stock_restored' => true])->save();
    }
}