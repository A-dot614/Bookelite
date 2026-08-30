<?php

namespace Tests\Feature;

use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
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

        // 2. Seller
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
        ]);

        // 3. Customer
        $this->customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // 4. Book
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
            'pages' => 300,
            'language' => 'English',
            'isbn' => '978-0-14-044933-4',
            'image_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800',
            'is_active' => true,
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

    public function test_checkout_and_order_placement()
    {
        // Add book to cart
        $this->post(route('cart.add', $this->book->slug), ['quantity' => 1]);

        // Place order as logged in customer
        $orderData = [
            'shipping_name' => 'Customer User',
            'shipping_email' => 'customer@test.com',
            'shipping_phone' => '+1 555 0192',
            'shipping_address' => '100 Broadway',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10001',
            'payment_method' => 'card',
            'notes' => 'Handle with care',
        ];

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), $orderData);
        $this->assertDatabaseHas('orders', [
            'shipping_email' => 'customer@test.com',
            'total' => 45.00,
            'status' => 'paid',
        ]);

        $order = Order::first();
        $response->assertRedirect(route('checkout.success', $order->id));

        // Verify stock decremented
        $this->assertEquals(9, $this->book->fresh()->stock);

        // Verify order items
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'ecommerce_id' => $this->book->id,
            'quantity' => 1,
            'price' => 45.00,
        ]);
    }

    public function test_wishlist_toggle_and_view()
    {
        // Toggle add to wishlist
        $response = $this->actingAs($this->customer)->post(route('wishlist.toggle', $this->book->slug));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $this->customer->id,
            'ecommerce_id' => $this->book->id,
        ]);

        // View wishlist
        $wishlistResponse = $this->actingAs($this->customer)->get(route('wishlist.index'));
        $wishlistResponse->assertStatus(200);
        $wishlistResponse->assertSee('Meditations of Marcus Aurelius');

        // Toggle remove from wishlist
        $responseRemove = $this->actingAs($this->customer)->post(route('wishlist.toggle', $this->book->slug));
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->customer->id,
            'ecommerce_id' => $this->book->id,
        ]);
    }

    public function test_admin_book_crud_and_dashboard()
    {
        // Dashboard
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Executive Dashboard');

        // Create book
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
        $this->assertDatabaseHas('ecommerces', ['title' => 'The Republic']);

        $created = Ecommerce::where('title', 'The Republic')->first();

        // Edit form
        $editResponse = $this->actingAs($this->admin)->get(route('admin.books.edit', $created->slug));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('The Republic');

        // Update book
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.books.update', $created->slug), array_merge($newBookData, [
            'price' => 55.00,
        ]));
        $updateResponse->assertRedirect();
        $this->assertEquals(55.00, $created->fresh()->price);

        // Customers page
        $custResponse = $this->actingAs($this->admin)->get(route('admin.customers.index'));
        $custResponse->assertStatus(200);
        $custResponse->assertSee('Customer User');

        // Reports page
        $repResponse = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $repResponse->assertStatus(200);
        $repResponse->assertSee('Financial');
        $repResponse->assertSee('Catalog Reports');

        // Delete book
        $delResponse = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $created->slug));
        $delResponse->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseMissing('ecommerces', ['id' => $created->id]);
    }

    public function test_seller_studio_workflow()
    {
        // Seller dashboard
        $response = $this->actingAs($this->sellerUser)->get(route('seller.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Antique Studio');

        // Seller books index
        $booksResponse = $this->actingAs($this->sellerUser)->get(route('seller.books.index'));
        $booksResponse->assertStatus(200);

        // Seller create book
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
}
