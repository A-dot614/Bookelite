<?php

namespace Database\Seeders;

use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::create([
            'name' => 'Abdullah Curator',
            'email' => 'admin@watch.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1 (555) 019-8234',
            'address' => 'Curator Suite 400, Archive Tower, New York',
        ]);

        // 2. Create Seller Merchant User & Store
        $sellerUser = User::create([
            'name' => 'Julian Vance',
            'email' => 'seller@watch.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '+44 20 7946 0912',
            'address' => '14 Old Bond Street, Mayfair, London',
        ]);

        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'The Bodleian Antiquarian & Fine Prints',
            'bio' => 'Specializing in first-edition literature, architectural folios, and philosophical treatises from the 18th to 20th centuries.',
            'phone' => '+44 20 7946 0912',
            'address' => 'Mayfair, London, UK',
            'is_verified' => true,
            'is_active' => true,
        ]);

        // 3. Create Regular Customer Users
        $customer1 = User::create([
            'name' => 'Bilal Patron',
            'email' => 'user@watch.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '+1 (555) 392-1082',
            'address' => '221B Baker Street, Suite 4',
        ]);

        $customer2 = User::create([
            'name' => 'Eleanor Vance',
            'email' => 'eleanor@watch.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '+1 (555) 984-2391',
            'address' => '450 Fifth Avenue, New York, NY',
        ]);

        $customer3 = User::create([
            'name' => 'Marcus Sterling',
            'email' => 'marcus@watch.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '+33 1 42 68 55 00',
            'address' => '12 Rue de Rivoli, Paris',
        ]);

        // 4. Curated Masterpiece Books Catalog
        $booksData = [
            [
                'title' => 'Meditations: The Archival Edition',
                'author' => 'Marcus Aurelius',
                'category' => 'Philosophy',
                'genre' => 'Stoic Philosophy',
                'price' => 45.00,
                'rating' => 4.9,
                'stock' => 18,
                'pages' => 304,
                'language' => 'English / Latin',
                'isbn' => '978-0-14-044933-4',
                'image_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800',
                'description' => 'A timeless personal diary of resilience, ethics, and sovereign inner composure, printed on acid-free mould-made cotton paper with blind-debossed leather casing.',
            ],
            [
                'title' => 'The Architecture of the Renaissance in Venice',
                'author' => 'Pietro Fontana',
                'category' => 'Architecture',
                'genre' => 'Classical Design',
                'price' => 85.00,
                'rating' => 5.0,
                'stock' => 12,
                'pages' => 480,
                'language' => 'English',
                'isbn' => '978-1-85-998241-0',
                'image_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800',
                'description' => 'A comprehensive survey of Palladian arches, Venetian façades, and geometric proportions, featuring archival duotone lithographs and folding architectural plates.',
            ],
            [
                'title' => 'The Book of Tea & Japanese Aesthetics',
                'author' => 'Kakuzo Okakura',
                'category' => 'Philosophy',
                'genre' => 'Eastern Aesthetics',
                'price' => 38.00,
                'rating' => 4.8,
                'stock' => 25,
                'pages' => 192,
                'language' => 'English',
                'isbn' => '978-0-14-118556-9',
                'image_url' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=800',
                'description' => 'An illuminating meditation on Teaism, Zen simplicity, and harmony with the imperfect. Hand-bound in raw unbleached linen with gold foil spine typography.',
            ],
            [
                'title' => 'In Search of Lost Time (Boxed Folio)',
                'author' => 'Marcel Proust',
                'category' => 'Literature',
                'genre' => 'Modernist Fiction',
                'price' => 140.00,
                'rating' => 4.9,
                'stock' => 8,
                'pages' => 1240,
                'language' => 'English',
                'isbn' => '978-0-30-795714-6',
                'image_url' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=800',
                'description' => 'The seminal exploration of memory, involuntary recollection, and Parisian aristocracy. Presented in a three-volume slipcased edition with silk ribbon place-markers.',
            ],
            [
                'title' => 'The Elements of Typographic Style',
                'author' => 'Robert Bringhurst',
                'category' => 'Fine Art',
                'genre' => 'Design & Typography',
                'price' => 52.00,
                'rating' => 4.9,
                'stock' => 30,
                'pages' => 352,
                'language' => 'English',
                'isbn' => '978-0-88-179212-6',
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800',
                'description' => 'The definitive authority on type rhythm, proportion, tracking, and editorial restraint. Essential reading for designers and bibliophiles alike.',
            ],
            [
                'title' => 'Philosophiæ Naturalis Principia Mathematica',
                'author' => 'Sir Isaac Newton',
                'category' => 'Science',
                'genre' => 'History of Science',
                'price' => 110.00,
                'rating' => 4.7,
                'stock' => 6,
                'pages' => 620,
                'language' => 'English Translation',
                'isbn' => '978-0-52-107647-0',
                'image_url' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800',
                'description' => 'The foundational mathematical treatise detailing the laws of universal gravitation and classical mechanics, illustrated with original geometric proofs.',
            ],
            [
                'title' => 'Principles of Curation & Contemporary Galleries',
                'author' => 'Hans-Ulrich Obrist',
                'category' => 'Fine Art',
                'genre' => 'Curatorial Practice',
                'price' => 42.00,
                'rating' => 4.6,
                'stock' => 15,
                'pages' => 280,
                'language' => 'English',
                'isbn' => '978-0-50-023893-6',
                'image_url' => 'https://images.unsplash.com/photo-1495640388908-05fa85288e61?w=800',
                'description' => 'A masterclass in gallery spatial dynamics, art display philosophies, and historical exhibition design by one of the art world’s foremost curators.',
            ],
            [
                'title' => 'The Sovereign Mind: Letters to Lucilius',
                'author' => 'Seneca the Younger',
                'category' => 'Philosophy',
                'genre' => 'Stoic Moral Letters',
                'price' => 36.00,
                'rating' => 4.9,
                'stock' => 22,
                'pages' => 272,
                'language' => 'English',
                'isbn' => '978-0-14-044210-6',
                'image_url' => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?w=800',
                'description' => 'Practical wisdom on friendship, wealth, mortality, and tranquil living. Bound in textured Japanese cloth with gilt top edges.',
            ],
            [
                'title' => 'Bauhaus Monograph: Weimar to Dessau',
                'author' => 'Walter Gropius',
                'category' => 'Architecture',
                'genre' => 'Modernist Design',
                'price' => 78.00,
                'rating' => 4.8,
                'stock' => 14,
                'pages' => 400,
                'language' => 'English / German',
                'isbn' => '978-3-83-656080-1',
                'image_url' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=800',
                'description' => 'Original architectural drafts, curriculum manifests, workshop photographs, and furniture prototypes documenting the birth of modern industrial design.',
            ],
            [
                'title' => 'Mastery & Discipline: The Art of Living',
                'author' => 'George Leonard',
                'category' => 'Self Development',
                'genre' => 'Personal Excellence',
                'price' => 32.00,
                'rating' => 4.8,
                'stock' => 35,
                'pages' => 192,
                'language' => 'English',
                'isbn' => '978-0-45-226756-5',
                'image_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800',
                'description' => 'An essential guide to long-term dedication, deliberate practice, plateau navigation, and the joyful pursuit of craft.',
            ],
            [
                'title' => 'The Divine Comedy (Illustrated by Gustave Doré)',
                'author' => 'Dante Alighieri',
                'category' => 'Literature',
                'genre' => 'Classical Poetry',
                'price' => 125.00,
                'rating' => 5.0,
                'stock' => 10,
                'pages' => 528,
                'language' => 'English / Italian',
                'isbn' => '978-1-45-373305-9',
                'image_url' => 'https://images.unsplash.com/photo-1507842229450-76c12b7a974b?w=800',
                'description' => 'The immortal journey across Inferno, Purgatorio, and Paradiso, accompanied by all 136 full-page master engravings by Gustave Doré.',
            ],
            [
                'title' => 'Cosmos & Quantum Geometry',
                'author' => 'Carlo Rovelli',
                'category' => 'Science',
                'genre' => 'Theoretical Physics',
                'price' => 40.00,
                'rating' => 4.7,
                'stock' => 19,
                'pages' => 256,
                'language' => 'English',
                'isbn' => '978-0-39-918441-3',
                'image_url' => 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?w=800',
                'description' => 'A poetic synthesis of loop quantum gravity, relational spacetime, and the deepest unanswered mysteries of modern astrophysics.',
            ],
        ];

        $createdBooks = [];
        foreach ($booksData as $index => $bookItem) {
            $slug = Str::slug($bookItem['title']);
            
            // Assign some books to the demo seller
            $bookSellerId = ($index % 3 === 0) ? $seller->id : null;

            $createdBooks[] = Ecommerce::create([
                'seller_id' => $bookSellerId,
                'title' => $bookItem['title'],
                'slug' => $slug,
                'author' => $bookItem['author'],
                'description' => $bookItem['description'],
                'price' => $bookItem['price'],
                'rating' => $bookItem['rating'],
                'category' => $bookItem['category'],
                'genre' => $bookItem['genre'],
                'stock' => $bookItem['stock'],
                'pages' => $bookItem['pages'],
                'language' => $bookItem['language'],
                'isbn' => $bookItem['isbn'],
                'image_url' => $bookItem['image_url'],
                'is_active' => true,
            ]);
        }

        // 5. Seed Wishlist items for customers
        Wishlist::create(['user_id' => $customer1->id, 'ecommerce_id' => $createdBooks[0]->id]);
        Wishlist::create(['user_id' => $customer1->id, 'ecommerce_id' => $createdBooks[1]->id]);
        Wishlist::create(['user_id' => $customer1->id, 'ecommerce_id' => $createdBooks[3]->id]);

        // 6. Seed Sample Orders and OrderItems
        // Order 1 for Bilal
        $order1 = Order::create([
            'order_number' => 'EA-8921-1049',
            'user_id' => $customer1->id,
            'status' => 'paid',
            'subtotal' => 130.00,
            'shipping_cost' => 0.00,
            'total' => 130.00,
            'shipping_name' => 'Bilal Patron',
            'shipping_email' => 'user@watch.com',
            'shipping_phone' => '+1 (555) 392-1082',
            'shipping_address' => '221B Baker Street, Suite 4',
            'shipping_city' => 'London',
            'shipping_country' => 'United Kingdom',
            'shipping_zip' => 'NW1 6XE',
            'payment_method' => 'card',
            'payment_reference' => 'PAY-89210042',
            'notes' => 'Please include certificate of authenticity with the Marcus Aurelius folio.',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'ecommerce_id' => $createdBooks[0]->id,
            'title' => $createdBooks[0]->title,
            'author' => $createdBooks[0]->author,
            'price' => $createdBooks[0]->price,
            'quantity' => 1,
            'line_total' => $createdBooks[0]->price,
            'image_url' => $createdBooks[0]->image_url,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'ecommerce_id' => $createdBooks[1]->id,
            'title' => $createdBooks[1]->title,
            'author' => $createdBooks[1]->author,
            'price' => $createdBooks[1]->price,
            'quantity' => 1,
            'line_total' => $createdBooks[1]->price,
            'image_url' => $createdBooks[1]->image_url,
        ]);

        // Order 2 for Eleanor
        $order2 = Order::create([
            'order_number' => 'EA-4019-3382',
            'user_id' => $customer2->id,
            'status' => 'delivered',
            'subtotal' => 140.00,
            'shipping_cost' => 0.00,
            'total' => 140.00,
            'shipping_name' => 'Eleanor Vance',
            'shipping_email' => 'eleanor@watch.com',
            'shipping_phone' => '+1 (555) 984-2391',
            'shipping_address' => '450 Fifth Avenue',
            'shipping_city' => 'New York',
            'shipping_country' => 'United States',
            'shipping_zip' => '10018',
            'payment_method' => 'paypal',
            'payment_reference' => 'PAY-99321048',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'ecommerce_id' => $createdBooks[3]->id,
            'title' => $createdBooks[3]->title,
            'author' => $createdBooks[3]->author,
            'price' => $createdBooks[3]->price,
            'quantity' => 1,
            'line_total' => $createdBooks[3]->price,
            'image_url' => $createdBooks[3]->image_url,
        ]);

        // Order 3 for Marcus
        $order3 = Order::create([
            'order_number' => 'EA-7729-5810',
            'user_id' => $customer3->id,
            'status' => 'shipped',
            'subtotal' => 203.00,
            'shipping_cost' => 0.00,
            'total' => 203.00,
            'shipping_name' => 'Marcus Sterling',
            'shipping_email' => 'marcus@watch.com',
            'shipping_phone' => '+33 1 42 68 55 00',
            'shipping_address' => '12 Rue de Rivoli',
            'shipping_city' => 'Paris',
            'shipping_country' => 'France',
            'shipping_zip' => '75001',
            'payment_method' => 'card',
            'payment_reference' => 'PAY-77182934',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'ecommerce_id' => $createdBooks[4]->id,
            'title' => $createdBooks[4]->title,
            'author' => $createdBooks[4]->author,
            'price' => $createdBooks[4]->price,
            'quantity' => 1,
            'line_total' => $createdBooks[4]->price,
            'image_url' => $createdBooks[4]->image_url,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'ecommerce_id' => $createdBooks[2]->id,
            'title' => $createdBooks[2]->title,
            'author' => $createdBooks[2]->author,
            'price' => $createdBooks[2]->price,
            'quantity' => 1,
            'line_total' => $createdBooks[2]->price,
            'image_url' => $createdBooks[2]->image_url,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'ecommerce_id' => $createdBooks[10]->id,
            'title' => $createdBooks[10]->title,
            'author' => $createdBooks[10]->author,
            'price' => $createdBooks[10]->price,
            'quantity' => 1,
            'line_total' => $createdBooks[10]->price,
            'image_url' => $createdBooks[10]->image_url,
        ]);
    }
}
