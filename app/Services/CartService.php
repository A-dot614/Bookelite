<?php

namespace App\Services;

use App\Models\Ecommerce;
use Illuminate\Support\Facades\Session;

/**
 * Session-backed shopping cart.
 *
 * The session only stores the trusted primary key and the quantity the user
 * requested. Every piece of display/pricing data is re-read from the database
 * at render time, so the cart can never be manipulated through the browser.
 */
class CartService
{
    public const SESSION_KEY = 'cart';

    /**
     * @return array<int, array{id:int, quantity:int}>
     */
    public function getRaw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    protected function setRaw(array $cart): void
    {
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Rehydrate the stored cart against the current database state.
     *
     * - Drops products that were deleted, unpublished, or deactivated.
     * - Clamps quantities to the available (non-negative) stock.
     * - Uses the current database price for every line.
     *
     * @return array{items: array<int, array<string,mixed>>, notice: ?string, has_unavailable: bool}
     */
    public function contents(): array
    {
        $raw = $this->getRaw();

        if (empty($raw)) {
            return ['items' => [], 'notice' => null, 'has_unavailable' => false];
        }

        $books = Ecommerce::whereIn('id', array_keys($raw))
            ->where('is_active', true)
            ->where('status', Ecommerce::STATUS_ACTIVE)
            ->get()
            ->keyBy('id');

        $items = [];
        $notice = null;
        $hasUnavailable = false;

        foreach ($raw as $id => $entry) {
            $book = $books->get((int) $id);

            // Product no longer available → prune.
            if (! $book) {
                $hasUnavailable = true;
                unset($raw[$id]);
                continue;
            }

            $requested = max(1, (int) ($entry['quantity'] ?? 1));
            $quantity = min($requested, max($book->stock, 0));

            if ($quantity <= 0) {
                $hasUnavailable = true;
                unset($raw[$id]);
                continue;
            }

            if ($quantity !== $requested) {
                $raw[$id]['quantity'] = $quantity;
                $notice = 'The quantity of “'.$book->title.'” was adjusted to match available stock.';
            }

            $items[$book->id] = [
                'id' => $book->id,
                'slug' => $book->slug,
                'title' => $book->title,
                'author' => (string) $book->author,
                'price' => (float) $book->price,
                'image_url' => (string) $book->image_url,
                'stock' => $book->stock,
                'quantity' => $quantity,
                'line_total' => round((float) $book->price * $quantity, 2),
                'low_stock' => $book->isLowStock(),
            ];
        }

        $this->setRaw($raw);

        return ['items' => $items, 'notice' => $notice, 'has_unavailable' => $hasUnavailable];
    }

    /**
     * Total quantity of units in the cart (distinct from number of line items).
     */
    public function count(): int
    {
        return array_sum(array_column($this->getRaw(), 'quantity'));
    }

    /**
     * Compute order totals from trusted (already rehydrated) items.
     *
     * @param array<int, array<string,mixed>> $items
     * @return array{subtotal:float, shipping_cost:float, tax_amount:float, total:float}
     */
    public function summary(array $items): array
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += (float) $item['line_total'];
        }

        $shipping = $this->shippingCost($subtotal);
        $tax = round($subtotal * (float) config('ecommerce.tax_rate'), 2);

        return [
            'subtotal' => round($subtotal, 2),
            'shipping_cost' => $shipping,
            'tax_amount' => $tax,
            'total' => round($subtotal + $shipping + $tax, 2),
        ];
    }

    public function shippingCost(float $subtotal): float
    {
        $flat = (float) config('ecommerce.shipping_flat_rate');
        $threshold = (float) config('ecommerce.free_shipping_threshold');

        if ($flat <= 0) {
            return 0.0;
        }

        if ($threshold > 0 && $subtotal >= $threshold) {
            return 0.0;
        }

        return round($flat, 2);
    }

    public function add(Ecommerce $book, int $quantity): array
    {
        $quantity = max(1, (int) $quantity);

        if (! $book->is_active || $book->status !== Ecommerce::STATUS_ACTIVE) {
            throw new \App\Exceptions\CartException('This book is not available for purchase.');
        }

        if ($book->stock < 1) {
            throw new \App\Exceptions\CartException('“'.$book->title.'” is currently out of stock.');
        }

        $cart = $this->getRaw();

        $newQuantity = $quantity;
        if (isset($cart[$book->id])) {
            $newQuantity = (int) $cart[$book->id]['quantity'] + $quantity;
        }
        $newQuantity = min($newQuantity, $book->stock);

        $cart[$book->id] = ['id' => $book->id, 'quantity' => $newQuantity];

        $this->setRaw($cart);

        if ($newQuantity < $quantity) {
            return ['status' => 'adjusted', 'message' => 'Quantity capped at available stock ('.$book->stock.').'];
        }

        return ['status' => 'added', 'message' => '“'.$book->title.'” has been added to your bag.'];
    }

    public function update(int $id, int $quantity): array
    {
        $quantity = max(1, (int) $quantity);
        $cart = $this->getRaw();

        if (! isset($cart[$id])) {
            throw new \App\Exceptions\CartException('That item is not in your bag.');
        }

        $book = Ecommerce::find($id);
        if (! $book || ! $book->is_active || $book->status !== Ecommerce::STATUS_ACTIVE) {
            $this->remove($id);
            throw new \App\Exceptions\CartException('That item is no longer available and was removed.');
        }

        $cart[$id]['quantity'] = min($quantity, $book->stock);
        $this->setRaw($cart);

        return ['status' => 'updated', 'message' => 'Your bag has been updated.'];
    }

    public function remove(int $id): void
    {
        $cart = $this->getRaw();
        unset($cart[$id]);
        $this->setRaw($cart);
    }
}