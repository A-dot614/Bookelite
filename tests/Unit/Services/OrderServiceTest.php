<?php

namespace Tests\Unit\Services;

use App\Exceptions\InvalidOrderTransition;
use App\Models\Ecommerce;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $service;
    protected User $user;
    protected Ecommerce $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(OrderService::class);

        $this->user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->book = Ecommerce::create([
            'title' => 'Meditations',
            'slug' => 'meditations',
            'author' => 'Marcus Aurelius',
            'description' => 'Stoic philosophy.',
            'price' => 45.00,
            'rating' => 4.9,
            'category' => 'Philosophy',
            'genre' => 'Stoicism',
            'stock' => 10,
            'low_stock_threshold' => 3,
            'pages' => 300,
            'language' => 'English',
            'isbn' => '978-0-14-044933-4',
            'sku' => '9780140449334',
            'image_url' => 'https://example.com/book.jpg',
            'is_active' => true,
            'status' => Ecommerce::STATUS_ACTIVE,
        ]);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Create an order with items. $stock is the book's stock AFTER checkout
     * (i.e. already decremented by the ordered quantity of 2).
     */
    protected function createOrder(string $status = Order::STATUS_PENDING, int $stock = 8): Order
    {
        // Start from 10, then simulate a 2-unit purchase.
        $this->book->update(['stock' => $stock + 2]);

        $order = Order::create([
            'order_number' => 'EA-TEST-'.rand(1000, 9999),
            'user_id' => $this->user->id,
            'status' => $status,
            'payment_status' => Order::PAYMENT_PENDING,
            'subtotal' => 90.00,
            'shipping_cost' => 0.00,
            'tax_amount' => 0.00,
            'total' => 90.00,
            'currency' => 'USD',
            'shipping_name' => 'Test Customer',
            'shipping_email' => 'customer@test.com',
            'shipping_address' => '100 Broadway',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10001',
            'payment_method' => 'bank_transfer',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'ecommerce_id' => $this->book->id,
            'title' => $this->book->title,
            'author' => $this->book->author,
            'isbn' => $this->book->isbn,
            'sku' => $this->book->sku,
            'price' => $this->book->price,
            'quantity' => 2,
            'line_total' => 90.00,
            'image_url' => $this->book->image_url,
            'currency' => 'USD',
        ]);

        // Simulate stock already being decremented at checkout time.
        $this->book->decrement('stock', 2);
        $this->assertEquals($stock, $this->book->fresh()->stock);

        return $order->fresh();
    }

    // ==================================================================
    // allowedTransitions()
    // ==================================================================

    public function test_allowed_transitions_from_pending(): void
    {
        $this->assertEquals([Order::STATUS_CANCELLED], $this->service->allowedTransitions(Order::STATUS_PENDING));
    }

    public function test_allowed_transitions_from_paid(): void
    {
        $expected = [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_CANCELLED, Order::STATUS_REFUNDED];
        $this->assertEquals($expected, $this->service->allowedTransitions(Order::STATUS_PAID));
    }

    public function test_allowed_transitions_from_processing(): void
    {
        $expected = [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED, Order::STATUS_REFUNDED];
        $this->assertEquals($expected, $this->service->allowedTransitions(Order::STATUS_PROCESSING));
    }

    public function test_allowed_transitions_from_shipped(): void
    {
        $expected = [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED, Order::STATUS_REFUNDED];
        $this->assertEquals($expected, $this->service->allowedTransitions(Order::STATUS_SHIPPED));
    }

    public function test_allowed_transitions_from_delivered(): void
    {
        $expected = [Order::STATUS_REFUNDED, Order::STATUS_CANCELLED];
        $this->assertEquals($expected, $this->service->allowedTransitions(Order::STATUS_DELIVERED));
    }

    public function test_allowed_transitions_from_cancelled_returns_empty(): void
    {
        $this->assertEmpty($this->service->allowedTransitions(Order::STATUS_CANCELLED));
    }

    public function test_allowed_transitions_from_refunded_returns_empty(): void
    {
        $this->assertEmpty($this->service->allowedTransitions(Order::STATUS_REFUNDED));
    }

    public function test_allowed_transitions_unknown_status_returns_empty(): void
    {
        $this->assertEmpty($this->service->allowedTransitions('bogus'));
    }

    // ==================================================================
    // canTransition()
    // ==================================================================

    public function test_can_transition_returns_true_for_valid_transition(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);
        $this->assertTrue($this->service->canTransition($order, Order::STATUS_CANCELLED));
    }

    public function test_can_transition_returns_false_for_invalid_transition(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);
        $this->assertFalse($this->service->canTransition($order, Order::STATUS_SHIPPED));
    }

    public function test_can_transition_returns_false_for_terminal_states(): void
    {
        $cancelled = $this->createOrder(Order::STATUS_CANCELLED);
        $refunded = $this->createOrder(Order::STATUS_REFUNDED);

        $this->assertFalse($this->service->canTransition($cancelled, Order::STATUS_PAID));
        $this->assertFalse($this->service->canTransition($refunded, Order::STATUS_PAID));
    }

    // ==================================================================
    // transition() — happy paths with timestamp verification
    // ==================================================================

    public function test_transition_pending_to_cancelled_sets_cancelled_at(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $result = $this->service->transition($order, Order::STATUS_CANCELLED);

        $this->assertEquals(Order::STATUS_CANCELLED, $result->status);
        $this->assertNotNull($result->cancelled_at);
        $this->assertTrue($result->cancelled_at->isPast());
    }

    public function test_transition_paid_to_processing(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID);

        $result = $this->service->transition($order, Order::STATUS_PROCESSING);

        $this->assertEquals(Order::STATUS_PROCESSING, $result->status);
    }

    public function test_transition_paid_to_shipped_sets_shipped_at(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID);

        $result = $this->service->transition($order, Order::STATUS_SHIPPED);

        $this->assertEquals(Order::STATUS_SHIPPED, $result->status);
        $this->assertNotNull($result->shipped_at);
        $this->assertTrue($result->shipped_at->isPast());
    }

    public function test_transition_shipped_to_delivered_sets_delivered_at(): void
    {
        $order = $this->createOrder(Order::STATUS_SHIPPED);

        $result = $this->service->transition($order, Order::STATUS_DELIVERED);

        $this->assertEquals(Order::STATUS_DELIVERED, $result->status);
        $this->assertNotNull($result->delivered_at);
        $this->assertTrue($result->delivered_at->isPast());
    }

    public function test_transition_paid_to_refunded_sets_refunded_at(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID);

        $result = $this->service->transition($order, Order::STATUS_REFUNDED);

        $this->assertEquals(Order::STATUS_REFUNDED, $result->status);
        $this->assertNotNull($result->refunded_at);
        $this->assertTrue($result->refunded_at->isPast());
    }

    public function test_transition_paid_to_cancelled_sets_cancelled_at(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID);

        $result = $this->service->transition($order, Order::STATUS_CANCELLED);

        $this->assertEquals(Order::STATUS_CANCELLED, $result->status);
        $this->assertNotNull($result->cancelled_at);
        $this->assertTrue($result->cancelled_at->isPast());
    }

    public function test_transition_sets_tracking_number(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID);

        $result = $this->service->transition($order, Order::STATUS_SHIPPED, 'TRACK-12345');

        $this->assertEquals('TRACK-12345', $result->tracking_number);
    }

    public function test_transition_does_not_overwrite_existing_paid_at(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID);
        $originalPaidAt = now()->subHours(2);
        $order->update(['paid_at' => $originalPaidAt]);

        $result = $this->service->transition($order, Order::STATUS_PROCESSING);

        $this->assertEquals($originalPaidAt->timestamp, $result->paid_at->timestamp);
    }

    // ==================================================================
    // transition() — invalid transitions
    // ==================================================================

    public function test_transition_throws_for_invalid_transition(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_SHIPPED);
    }

    public function test_transition_throws_for_pending_to_paid(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_PAID);
    }

    public function test_transition_throws_for_pending_to_processing(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_PROCESSING);
    }

    public function test_transition_throws_for_pending_to_delivered(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_DELIVERED);
    }

    public function test_transition_throws_for_pending_to_refunded(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_REFUNDED);
    }

    public function test_transition_throws_for_cancelled_to_anything(): void
    {
        $order = $this->createOrder(Order::STATUS_CANCELLED);

        foreach ([Order::STATUS_PENDING, Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED, Order::STATUS_REFUNDED] as $target) {
            $order->update(['status' => Order::STATUS_CANCELLED]);
            $this->expectException(InvalidOrderTransition::class);
            $this->service->transition($order, $target);
        }
    }

    public function test_transition_throws_for_refunded_to_anything(): void
    {
        $order = $this->createOrder(Order::STATUS_REFUNDED);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_PENDING);
    }

    public function test_transition_throws_for_invalid_status_value(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, 'bogus_status');
    }

    public function test_transition_throws_for_shipped_to_paid(): void
    {
        $order = $this->createOrder(Order::STATUS_SHIPPED);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_PAID);
    }

    public function test_transition_throws_for_delivered_to_processing(): void
    {
        $order = $this->createOrder(Order::STATUS_DELIVERED);

        $this->expectException(InvalidOrderTransition::class);
        $this->service->transition($order, Order::STATUS_PROCESSING);
    }

    // ==================================================================
    // Inventory restoration on cancel/refund
    // ==================================================================

    public function test_cancelling_order_restores_stock(): void
    {
        $order = $this->createOrder(Order::STATUS_PROCESSING, stock: 8);

        $this->service->transition($order, Order::STATUS_CANCELLED);

        $this->assertEquals(10, $this->book->fresh()->stock);
        $this->assertTrue($order->fresh()->stock_restored);
    }

    public function test_refunding_order_restores_stock(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID, stock: 8);

        $this->service->transition($order, Order::STATUS_REFUNDED);

        $this->assertEquals(10, $this->book->fresh()->stock);
        $this->assertTrue($order->fresh()->stock_restored);
    }

    public function test_stock_restored_guard_prevents_double_restoration(): void
    {
        $order = $this->createOrder(Order::STATUS_PROCESSING, stock: 8);

        // First cancellation restores stock.
        $this->service->transition($order, Order::STATUS_CANCELLED);
        $this->assertEquals(10, $this->book->fresh()->stock);

        // Attempting another cancel should fail (cancelled is terminal),
        // but even if the guard were bypassed, stock must stay at 10.
        $this->assertFalse($this->service->canTransition($order->fresh(), Order::STATUS_CANCELLED));
        $this->assertEquals(10, $this->book->fresh()->stock);
    }

    public function test_inventory_log_recorded_on_cancellation(): void
    {
        $order = $this->createOrder(Order::STATUS_PROCESSING, stock: 8);

        $this->service->transition($order, Order::STATUS_CANCELLED);

        $this->assertDatabaseHas('inventory_logs', [
            'ecommerce_id' => $this->book->id,
            'reason' => 'order_cancelled',
            'order_id' => $order->id,
            'quantity_change' => 2,
        ]);
    }

    public function test_inventory_log_recorded_on_refund(): void
    {
        $order = $this->createOrder(Order::STATUS_PAID, stock: 8);

        $this->service->transition($order, Order::STATUS_REFUNDED);

        $this->assertDatabaseHas('inventory_logs', [
            'ecommerce_id' => $this->book->id,
            'reason' => 'order_refunded',
            'order_id' => $order->id,
            'quantity_change' => 2,
        ]);
    }

    public function test_multiple_order_items_all_restore_stock(): void
    {
        $secondBook = Ecommerce::create([
            'title' => 'Second Book',
            'slug' => 'second-book',
            'author' => 'Author Two',
            'description' => 'Another book.',
            'price' => 30.00,
            'rating' => 4.0,
            'category' => 'Science',
            'genre' => 'Science',
            'stock' => 5,
            'low_stock_threshold' => 2,
            'pages' => 200,
            'language' => 'English',
            'isbn' => '978-0-00-000000-2',
            'sku' => 'SECBOOK1',
            'image_url' => 'https://example.com/second.jpg',
            'is_active' => true,
            'status' => Ecommerce::STATUS_ACTIVE,
        ]);

        $order = Order::create([
            'order_number' => 'EA-MULTI-'.rand(1000, 9999),
            'user_id' => $this->user->id,
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PAID,
            'subtotal' => 150.00,
            'shipping_cost' => 0.00,
            'tax_amount' => 0.00,
            'total' => 150.00,
            'currency' => 'USD',
            'shipping_name' => 'Test Customer',
            'shipping_email' => 'customer@test.com',
            'shipping_address' => '100 Broadway',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10001',
            'payment_method' => 'bank_transfer',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'ecommerce_id' => $this->book->id,
            'title' => $this->book->title,
            'author' => $this->book->author,
            'price' => $this->book->price,
            'quantity' => 2,
            'line_total' => 90.00,
            'image_url' => $this->book->image_url,
            'currency' => 'USD',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'ecommerce_id' => $secondBook->id,
            'title' => $secondBook->title,
            'author' => $secondBook->author,
            'price' => $secondBook->price,
            'quantity' => 2,
            'line_total' => 60.00,
            'image_url' => $secondBook->image_url,
            'currency' => 'USD',
        ]);

        $this->book->decrement('stock', 2);
        $secondBook->decrement('stock', 2);

        $this->assertEquals(8, $this->book->fresh()->stock);
        $this->assertEquals(3, $secondBook->fresh()->stock);

        $this->service->transition($order, Order::STATUS_CANCELLED);

        $this->assertEquals(10, $this->book->fresh()->stock);
        $this->assertEquals(5, $secondBook->fresh()->stock);
    }

    public function test_transition_with_null_ecommerce_id_skips_restoration_for_that_item(): void
    {
        // An order item with a null ecommerce_id (deleted product) should be
        // skipped gracefully during restoration without throwing.
        $order = Order::create([
            'order_number' => 'EA-NULL-'.rand(1000, 9999),
            'user_id' => $this->user->id,
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PAID,
            'subtotal' => 45.00,
            'shipping_cost' => 0.00,
            'tax_amount' => 0.00,
            'total' => 45.00,
            'currency' => 'USD',
            'shipping_name' => 'Test Customer',
            'shipping_email' => 'customer@test.com',
            'shipping_address' => '100 Broadway',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10001',
            'payment_method' => 'bank_transfer',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'ecommerce_id' => null,
            'title' => 'Deleted Book',
            'price' => 45.00,
            'quantity' => 1,
            'line_total' => 45.00,
            'currency' => 'USD',
        ]);

        // Should not throw even though the book no longer exists.
        $result = $this->service->transition($order, Order::STATUS_CANCELLED);
        $this->assertEquals(Order::STATUS_CANCELLED, $result->status);
    }

    // ==================================================================
    // markPaid()
    // ==================================================================

    public function test_mark_paid_sets_payment_status_and_paid_at(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);
        $this->assertNull($order->paid_at);

        $result = $this->service->markPaid($order);

        $this->assertEquals(Order::PAYMENT_PAID, $result->payment_status);
        $this->assertNotNull($result->paid_at);
        $this->assertEquals(Order::STATUS_PAID, $result->status);
    }

    public function test_mark_paid_transitions_pending_to_paid(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $result = $this->service->markPaid($order);

        $this->assertEquals(Order::STATUS_PAID, $result->status);
    }

    public function test_mark_paid_does_not_change_status_if_already_paid(): void
    {
        $order = $this->createOrder(Order::STATUS_PROCESSING);
        $order->update(['payment_status' => Order::PAYMENT_PAID, 'status' => Order::STATUS_PAID]);

        $result = $this->service->markPaid($order);

        $this->assertEquals(Order::STATUS_PAID, $result->status);
    }

    public function test_mark_paid_does_not_overwrite_existing_paid_at(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);
        $originalTime = now()->subDays(3);
        $order->update(['paid_at' => $originalTime]);

        $result = $this->service->markPaid($order);

        $this->assertEquals($originalTime->timestamp, $result->paid_at->timestamp);
    }

    public function test_mark_paid_sets_payment_reference_when_provided(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);
        $this->assertNull($order->payment_reference);

        $result = $this->service->markPaid($order, 'REF-ABCD-1234');

        $this->assertEquals('REF-ABCD-1234', $result->payment_reference);
    }

    public function test_mark_paid_does_not_overwrite_existing_reference(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);
        $order->update(['payment_reference' => 'EXISTING-REF']);

        $result = $this->service->markPaid($order, 'NEW-REF');

        $this->assertEquals('EXISTING-REF', $result->payment_reference);
    }

    public function test_mark_paid_does_not_set_reference_when_null(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $result = $this->service->markPaid($order, null);

        $this->assertNull($result->payment_reference);
    }

    // ==================================================================
    // markPaymentFailed()
    // ==================================================================

    public function test_mark_payment_failed_sets_failed_status(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $result = $this->service->markPaymentFailed($order);

        $this->assertEquals(Order::PAYMENT_FAILED, $result->payment_status);
    }

    public function test_mark_payment_failed_preserves_order_status(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        $result = $this->service->markPaymentFailed($order);

        // markPaymentFailed only touches payment_status, not the order status.
        $this->assertEquals(Order::STATUS_PENDING, $result->status);
    }

    // ==================================================================
    // Full lifecycle
    // ==================================================================

    public function test_full_lifecycle_pending_to_delivered(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING);

        // pending → paid
        $this->service->markPaid($order);
        $order = $order->fresh();
        $this->assertEquals(Order::STATUS_PAID, $order->status);
        $this->assertNotNull($order->paid_at);

        // paid → processing
        $order = $this->service->transition($order, Order::STATUS_PROCESSING);
        $this->assertEquals(Order::STATUS_PROCESSING, $order->status);

        // processing → shipped
        $order = $this->service->transition($order, Order::STATUS_SHIPPED, 'SHIP-999');
        $this->assertEquals(Order::STATUS_SHIPPED, $order->status);
        $this->assertNotNull($order->shipped_at);
        $this->assertEquals('SHIP-999', $order->tracking_number);

        // shipped → delivered
        $order = $this->service->transition($order, Order::STATUS_DELIVERED);
        $this->assertEquals(Order::STATUS_DELIVERED, $order->status);
        $this->assertNotNull($order->delivered_at);

        // Stock was never restored (order completed normally).
        $this->assertEquals(8, $this->book->fresh()->stock);
        $this->assertFalse($order->stock_restored);
    }

    public function test_full_lifecycle_pending_to_cancelled(): void
    {
        $order = $this->createOrder(Order::STATUS_PENDING, stock: 8);
        $this->assertEquals(8, $this->book->fresh()->stock);

        $this->service->transition($order, Order::STATUS_CANCELLED);

        $order = $order->fresh();
        $this->assertEquals(Order::STATUS_CANCELLED, $order->status);
        $this->assertNotNull($order->cancelled_at);
        $this->assertEquals(10, $this->book->fresh()->stock);
        $this->assertTrue($order->stock_restored);
    }
}
