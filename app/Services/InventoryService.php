<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Ecommerce;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\User;

/**
 * Central place for all stock movement. Every mutation is written to the
 * inventory_logs table so movements are fully traceable and inventory can
 * never be changed outside of these verified paths.
 */
class InventoryService
{
    public const REASON_ORDER_CREATED = 'order_created';
    public const REASON_ORDER_CANCELLED = 'order_cancelled';
    public const REASON_ORDER_REFUNDED = 'order_refunded';
    public const REASON_ADMIN_ADJUSTMENT = 'admin_adjustment';
    public const REASON_SELLER_ADJUSTMENT = 'seller_adjustment';

    /**
     * Reduce stock. MUST be called while the product row is locked inside a
     * transaction (see CheckoutService) so concurrent purchases cannot
     * oversell.
     */
    public function decrement(
        Ecommerce $book,
        int $quantity,
        string $reason = self::REASON_ORDER_CREATED,
        ?Order $order = null,
        ?User $user = null,
        array $metadata = []
    ): void {
        if ($quantity < 1) {
            return;
        }

        if ($book->stock < $quantity) {
            throw new InsufficientStockException($book->title, $quantity, $book->stock);
        }

        $previous = $book->stock;
        $book->decrement('stock', $quantity);
        $book->refresh();

        $this->log($book, -$quantity, $previous, $book->stock, $reason, $order, $user, $metadata);
    }

    /**
     * Increase stock (cancellation / refund / manual correction).
     */
    public function restock(
        Ecommerce $book,
        int $quantity,
        string $reason = self::REASON_ORDER_REFUNDED,
        ?Order $order = null,
        ?User $user = null,
        array $metadata = []
    ): void {
        if ($quantity < 1) {
            return;
        }

        $previous = $book->stock;
        $book->increment('stock', $quantity);
        $book->refresh();

        $this->log($book, $quantity, $previous, $book->stock, $reason, $order, $user, $metadata);
    }

    /**
     * Set an absolute stock level (manual admin/seller correction).
     */
    public function setStock(Ecommerce $book, int $newStock, string $reason, ?User $user = null, array $metadata = []): void
    {
        $newStock = max(0, $newStock);
        $previous = $book->stock;
        $delta = $newStock - $previous;

        if ($delta !== 0) {
            $book->forceFill(['stock' => $newStock])->save();
            $book->refresh();
            $this->log($book, $delta, $previous, $newStock, $reason, null, $user, $metadata);
        }
    }

    protected function log(
        Ecommerce $book,
        int $change,
        ?int $previous,
        ?int $newStock,
        string $reason,
        ?Order $order = null,
        ?User $user = null,
        array $metadata = []
    ): void {
        InventoryLog::create([
            'ecommerce_id' => $book->id,
            'quantity_change' => $change,
            'previous_stock' => $previous,
            'new_stock' => $newStock,
            'reason' => $reason,
            'order_id' => $order?->id,
            'user_id' => $user?->id ?? $metadata['user_id'] ?? null,
            'metadata' => $metadata,
        ]);
    }
}