<?php

namespace App\Services;

use App\Exceptions\CheckoutException;
use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Payment\PaymentManager;
use App\Services\Payment\PaymentResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Orchestrates order creation.
 *
 * - All products are reloaded from the database inside a transaction with row
 *   locking so two simultaneous buyers cannot oversell the same stock.
 * - Prices, stock and availability are always authoritative (database).
 * - Order items are snapshots so historical orders stay accurate forever.
 * - Payment is delegated to the PaymentManager; nothing is ever marked "paid"
 *   unless the provider confirms it.
 */
class CheckoutService
{
    public function __construct(
        protected CartService $cart,
        protected InventoryService $inventory,
        protected PaymentManager $payments,
    ) {
    }

    /**
     * @param array<string,mixed> $data validated checkout payload
     */
    public function checkout(array $data): Order
    {
        $raw = $this->cart->getRaw();

        if (empty($raw)) {
            throw new CheckoutException('Your bag is empty. Please add a book before checking out.');
        }

        $method = $data['payment_method'];

        if (! $this->payments->supports($method)) {
            $this->assertGatewayAvailable($method);
        }

        if (in_array($method, ['card', 'paypal'], true) && ! $this->payments->provider()->isConfigured()) {
            throw new CheckoutException(
                'Online (' . strtoupper($method) . ') payments are not available yet. Please use bank transfer or cash on delivery.'
            );
        }

        $user = auth()->user();

        return DB::transaction(function () use ($raw, $data, $user, $method) {
            $ids = array_keys($raw);

            // Lock product rows (sorted) to make concurrent purchases safe.
            $books = Ecommerce::whereIn('id', $ids)
                ->where('is_active', true)
                ->where('status', Ecommerce::STATUS_ACTIVE)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0.0;
            $lines = [];

            foreach ($raw as $id => $entry) {
                $book = $books->get((int) $id);

                if (! $book) {
                    throw new CheckoutException('One or more books in your bag are no longer available.');
                }

                $quantity = max(1, (int) ($entry['quantity'] ?? 1));

                if ($book->stock < $quantity) {
                    throw new \App\Exceptions\InsufficientStockException($book->title, $quantity, $book->stock);
                }

                $lineTotal = round((float) $book->price * $quantity, 2);
                $subtotal += $lineTotal;

                $lines[] = ['book' => $book, 'quantity' => $quantity, 'line_total' => $lineTotal];
            }

            $subtotal = round($subtotal, 2);
            $shipping = $this->cart->shippingCost($subtotal);
            $tax = round($subtotal * (float) config('ecommerce.tax_rate'), 2);
            $total = round($subtotal + $shipping + $tax, 2);

            $order = Order::create([
                'order_number' => $this->newOrderNumber(),
                'user_id' => $user?->id,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'tax_amount' => $tax,
                'discount_amount' => 0.00,
                'total' => $total,
                'currency' => config('ecommerce.currency', 'USD'),
                'shipping_name' => $data['shipping_name'],
                'shipping_email' => $data['shipping_email'],
                'shipping_phone' => $data['shipping_phone'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'shipping_city' => $data['shipping_city'],
                'shipping_country' => $data['shipping_country'],
                'shipping_zip' => $data['shipping_zip'],
                'payment_method' => $method,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($lines as $line) {
                $book = $line['book'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'ecommerce_id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'sku' => $book->sku,
                    'price' => $book->price,
                    'quantity' => $line['quantity'],
                    'line_total' => $line['line_total'],
                    'image_url' => $book->image_url,
                    'currency' => $order->currency,
                ]);

                $this->inventory->decrement(
                    $book,
                    $line['quantity'],
                    InventoryService::REASON_ORDER_CREATED,
                    $order,
                    $user
                );
            }

            // Initiate payment (never assume success).
            $result = $this->payments->provider()->charge($order, $data);

            $order->payment_reference = $result->reference;

            if ($result->status === PaymentResult::STATUS_FAILED) {
                $order->payment_status = Order::PAYMENT_FAILED;
                $order->save();
                throw new CheckoutException($result->message ?: 'Payment failed. Please try again.');
            }

            $order->save();

            $this->cart->clear();

            return $order;
        });
    }

    protected function assertGatewayAvailable(string $method): void
    {
        throw new CheckoutException(
            'The selected payment method ('.strtoupper($method).') is not currently supported.'
        );
    }

    protected function newOrderNumber(): string
    {
        do {
            $number = 'EA-'.strtoupper(Str::random(4)).'-'.random_int(1000, 9999);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}