<?php

namespace Tests\Feature;

use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $sellerUser;
    protected Seller $seller;
    protected User $customer;
    protected Ecommerce $book;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Approved seller
        $this->sellerUser = User::create([
            'name' => 'Seller User',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->seller = Seller::create([
            'user_id' => $this->sellerUser->id,
            'store_name' => 'Antique Studio',
            'bio' => 'Rare antiquarian books',
            'is_verified' => true,
            'is_active' => true,
            'status' => Seller::STATUS_APPROVED,
        ]);

        // 3. Customer
        $this->customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // 4. Published book
        $this->book = Ecommerce::create([
            'title' => 'Meditations of Marcus Aurelius',
            'slug' => 'meditations-of-marcus-aurelius',
            'author' => 'Marcus Aurelius',
            'description' => 'Stoic philosophy notes.',
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
            'image_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800',
            'is_active' => true,
            'status' => Ecommerce::STATUS_ACTIVE,
            'published_at' => now(),
        ]);
    }

    public function test_home_page_loads_and_displays_catalog()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Meditations of Marcus Aurelius');
        $response->assertSee('Marcus Aurelius');
        $response->assertSee('$45.00');
    }

    public function test_catalog_search_and_filter()
    {
        $response = $this->get(route('home', ['q' => 'Marcus']));
        $response->assertStatus(200);
        $response->assertSee('Meditations of Marcus Aurelius');

        $responseEmpty = $this->get(route('home', ['q' => 'NonExistentBookName']));
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('No masterpieces found matching your search.');
    }

    public function test_detail_page_loads()
    {
        $response = $this->get(route('detail', $this->book->slug));
        $response->assertStatus(200);
        $response->assertSee('Meditations of Marcus Aurelius');
        $response->assertSee('Marcus Aurelius');
        $response->assertSee('$45.00');
        $response->assertSee('Philosophy');
        $response->assertSee('Acquire to Collection');
    }

    public function test_cart_workflow()
    {
        // Add to cart
        $response = $this->post(route('cart.add', $this->book->slug), ['quantity' => 2]);
        $response->assertRedirect(route('cart.index'));
        $this->assertEquals(2, session('cart')[$this->book->id]['quantity']);

        // View cart
        $cartResponse = $this->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Meditations of Marcus Aurelius');
        $cartResponse->assertSee('$90.00'); // 2 * 45

        // Update cart
        $updateResponse = $this->patch(route('cart.update', $this->book->id), ['quantity' => 1]);
        $updateResponse->assertRedirect(route('cart.index'));
        $this->assertEquals(1, session('cart')[$this->book->id]['quantity']);

        // Remove from cart
        $removeResponse = $this->delete(route('cart.remove', $this->book->id));
        $removeResponse->assertRedirect(route('cart.index'));
        $this->assertArrayNotHasKey($this->book->id, session('cart', []));
    }

    public function test_cart_quantity_is_clamped_to_available_stock()
    {
        $this->post(route('cart.add', $this->book->slug), ['quantity' => 99]);
        $this->assertEquals(10, session('cart')[$this->book->id]['quantity']);
    }

    public function test_checkout_and_order_placement()
    {
        // Add book to cart
        $this->post(route('cart.add', $this->book->slug), ['quantity' => 2]);

        $orderData = [
            'shipping_name' => 'Customer User',
            'shipping_email' => 'customer@test.com',
            'shipping_phone' => '+1 555 0192',
            'shipping_address' => '100 Broadway',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10001',
            'payment_method' => 'bank_transfer',
            'notes' => 'Handle with care',
        ];

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), $orderData);

        // Orders are always registered as pending / payment_pending until staff confirm.
        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'shipping_email' => 'customer@test.com',
            'total' => 90.00,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => 'bank_transfer',
        ]);
        $this->assertStringStartsWith('REF-', $order->payment_reference);

        $response->assertRedirect(route('checkout.success', $order->id));

        // Stock is reserved the moment the order is placed.
        $this->assertEquals(8, $this->book->fresh()->stock);

        // Order items are snapshots of the product at purchase time.
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'ecommerce_id' => $this->book->id,
            'quantity' => 2,
            'price' => 45.00,
            'line_total' => 90.00,
        ]);

        // Cart is cleared after checkout.
        $this->assertEmpty(session('cart', []));

        // The success page is accessible to the owner.
        $success = $this->actingAs($this->customer)->get(route('checkout.success', $order->id));
        $success->assertStatus(200);
        $success->assertSee('Order Registered');
    }

    public function test_checkout_never_marks_an_order_paid_automatically()
    {
        $this->post(route('cart.add', $this->book->slug), ['quantity' => 1]);

        $order = $this->placeOrder();

        $this->assertNotEquals(Order::STATUS_PAID, $order->status);
        $this->assertNotEquals(Order::PAYMENT_PAID, $order->payment_status);
        $this->assertNull($order->paid_at);
    }

    public function test_online_payment_is_rejected_when_provider_is_not_configured()
    {
        $this->post(route('cart.add', $this->book->slug), ['quantity' => 1]);

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), [
            'shipping_name' => 'Customer User',
            'shipping_email' => 'customer@test.com',
            'shipping_phone' => '+1 555 0192',
            'shipping_address' => '100 Broadway',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10001',
            'payment_method' => 'card',
            'notes' => null,
        ]);

        $response->assertSessionHasErrors('payment_method');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_admin_confirming_payment_and_cancelling_restores_stock()
    {
        $this->post(route('cart.add', $this->book->slug), ['quantity' => 2]);
        $order = $this->placeOrder();
        $this->assertEquals(8, $this->book->fresh()->stock);

        // Admin confirms the offline payment.
        $paid = $this->actingAs($this->admin)->post(route('admin.orders.mark-paid', $order->id));
        $paid->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PAID,
            'payment_status' => Order::PAYMENT_PAID,
        ]);
        $this->assertNotNull($order->fresh()->paid_at);

        // Admin transitions to processing.
        $processing = $this->actingAs($this->admin)->post(
            route('admin.orders.transition', $order->id),
            ['status' => Order::STATUS_PROCESSING]
        );
        $processing->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::STATUS_PROCESSING]);

        // Cancelling the order releases the reserved stock exactly once.
        $cancel = $this->actingAs($this->admin)->post(
            route('admin.orders.transition', $order->id),
            ['status' => Order::STATUS_CANCELLED]
        );
        $cancel->assertRedirect();
        $this->assertEquals(10, $this->book->fresh()->stock);

        // Stock must not be restored twice.
        $secondCancel = $this->actingAs($this->admin)->post(
            route('admin.orders.transition', $order->id),
            ['status' => Order::STATUS_CANCELLED]
        );
        $secondCancel->assertRedirect();
        $secondCancel->assertSessionHas('error');
        $this->assertEquals(10, $this->book->fresh()->stock);
    }

    public function test_wishlist_toggle_and_view()
    {
        $response = $this->actingAs($this->customer)->post(route('wishlist.toggle', $this->book->slug));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $this->customer->id,
            'ecommerce_id' => $this->book->id,
        ]);

        $wishlistResponse = $this->actingAs($this->customer)->get(route('wishlist.index'));
        $wishlistResponse->assertStatus(200);
        $wishlistResponse->assertSee('Meditations of Marcus Aurelius');

        $responseRemove = $this->actingAs($this->customer)->post(route('wishlist.toggle', $this->book->slug));
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->customer->id,
            'ecommerce_id' => $this->book->id,
        ]);
    }

    public function test_admin_book_crud_and_dashboard()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Executive Dashboard');

        $newBookData = [
            'title' => 'The Republic',
            'author' => 'Plato',
            'description' => 'Socratic dialogue concerning justice and the order of the just city-state.',
            'price' => 50.00,
            'category' => 'Philosophy',
            'genre' => 'Classical Greek',
            'stock' => 15,
            'pages' => 416,
            'language' => 'English',
            'isbn' => '978-0-14-045511-3',
        ];

        $postResponse = $this->actingAs($this->admin)->post(route('admin.books.store'), $newBookData);
        $postResponse->assertRedirect();
        $this->assertDatabaseHas('ecommerces', ['title' => 'The Republic', 'status' => Ecommerce::STATUS_ACTIVE]);

        $created = Ecommerce::where('title', 'The Republic')->first();

        $editResponse = $this->actingAs($this->admin)->get(route('admin.books.edit', $created->slug));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('The Republic');

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.books.update', $created->slug), array_merge($newBookData, [
            'price' => 55.00,
        ]));
        $updateResponse->assertRedirect();
        $this->assertEquals(55.00, $created->fresh()->price);

        $custResponse = $this->actingAs($this->admin)->get(route('admin.customers.index'));
        $custResponse->assertStatus(200);
        $custResponse->assertSee('Customer User');

        $repResponse = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $repResponse->assertStatus(200);
        $repResponse->assertSee('Financial');
        $repResponse->assertSee('Catalog Reports');

        $delResponse = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $created->slug));
        $delResponse->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseMissing('ecommerces', ['id' => $created->id]);
    }

    public function test_seller_studio_workflow()
    {
        // Approved sellers can reach their dashboard and studio.
        $response = $this->actingAs($this->sellerUser)->get(route('seller.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Antique Studio');

        $booksResponse = $this->actingAs($this->sellerUser)->get(route('seller.books.index'));
        $booksResponse->assertStatus(200);

        $sellerBookData = [
            'title' => 'First Edition Folio',
            'author' => 'Rare Author',
            'description' => 'A scarce first impression.',
            'price' => 195.00,
            'category' => 'Literature',
            'stock' => 2,
            'pages' => 220,
            'isbn' => '978-1-11-222333-4',
        ];

        $postBook = $this->actingAs($this->sellerUser)->post(route('seller.books.store'), $sellerBookData);
        $postBook->assertRedirect(route('seller.books.index'));
        $this->assertDatabaseHas('ecommerces', [
            'title' => 'First Edition Folio',
            'seller_id' => $this->seller->id,
        ]);
    }

    public function test_unapproved_seller_is_blocked_from_studio()
    {
        $pendingUser = User::create([
            'name' => 'Pending Seller',
            'email' => 'pending@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        Seller::create([
            'user_id' => $pendingUser->id,
            'store_name' => 'Not Ready Yet',
            'is_verified' => false,
            'is_active' => true,
            'status' => Seller::STATUS_PENDING,
        ]);

        $response = $this->actingAs($pendingUser)->get(route('seller.dashboard'));
        $response->assertRedirect(route('seller.register'));
    }

    public function test_reviews_require_a_paid_purchase()
    {
        // Customer without a purchase cannot review.
        $blocked = $this->actingAs($this->customer)->post(route('review.store', $this->book->slug), [
            'rating' => 5,
            'comment' => 'Wonderful stoic text.',
        ]);
        $blocked->assertForbidden();
        $this->assertDatabaseCount('reviews', 0);

        // Customer buys and pays for the book.
        $this->post(route('cart.add', $this->book->slug), ['quantity' => 1]);
        $order = $this->placeOrder();
        $this->actingAs($this->admin)->post(route('admin.orders.mark-paid', $order->id));

        // Now the review is allowed but held for moderation.
        $posted = $this->actingAs($this->customer)->post(route('review.store', $this->book->slug), [
            'rating' => 5,
            'comment' => 'Wonderful stoic text.',
        ]);
        $posted->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->customer->id,
            'ecommerce_id' => $this->book->id,
            'rating' => 5,
            'is_approved' => false,
        ]);

        // A second review is rejected by the one-per-book rule.
        $duplicate = $this->actingAs($this->customer)->post(route('review.store', $this->book->slug), [
            'rating' => 3,
            'comment' => 'Trying again.',
        ]);
        $duplicate->assertForbidden();

        // Admin moderation screen shows the pending review.
        $moderation = $this->actingAs($this->admin)->get(route('admin.reviews.index'));
        $moderation->assertStatus(200);
        $moderation->assertSee('Wonderful stoic text.');

        // Approving publishes it and recalibrates the title's rating.
        $toggle = $this->actingAs($this->admin)->post(route('admin.reviews.toggle', Review::first()->id));
        $toggle->assertRedirect();
        $this->assertEquals(5, $this->book->fresh()->rating);
        $this->assertEquals(5, $this->book->fresh()->approvedReviews()->first()->rating);
    }

    public function test_admin_seller_approval_workflow()
    {
        $pendingUser = User::create([
            'name' => 'New Merchant',
            'email' => 'merchant@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $pending = Seller::create([
            'user_id' => $pendingUser->id,
            'store_name' => 'Book Corner',
            'bio' => 'Small local collection.',
            'is_verified' => false,
            'is_active' => true,
            'status' => Seller::STATUS_PENDING,
        ]);

        // Admin sees the pending merchant in the queue.
        $list = $this->actingAs($this->admin)->get(route('admin.sellers.index'));
        $list->assertStatus(200);
        $list->assertSee('Book Corner');

        $show = $this->actingAs($this->admin)->get(route('admin.sellers.show', $pending->id));
        $show->assertStatus(200);
        $show->assertSee('Book Corner');

        // Approval unlocks the studio.
        $approve = $this->actingAs($this->admin)->post(route('admin.sellers.status', $pending->id), [
            'status' => Seller::STATUS_APPROVED,
        ]);
        $approve->assertRedirect();
        $this->assertDatabaseHas('sellers', [
            'id' => $pending->id,
            'status' => Seller::STATUS_APPROVED,
            'is_verified' => true,
        ]);

        $dashboard = $this->actingAs($pendingUser)->get(route('seller.dashboard'));
        $dashboard->assertStatus(200);
    }

    protected function placeOrder(): Order
    {
        $orderData = [
            'shipping_name' => 'Customer User',
            'shipping_email' => 'customer@test.com',
            'shipping_phone' => '+1 555 0192',
            'shipping_address' => '100 Broadway',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10001',
            'payment_method' => 'bank_transfer',
            'notes' => null,
        ];

        $this->actingAs($this->customer)->post(route('checkout.store'), $orderData);

        return Order::latest('id')->firstOrFail();
    }
}