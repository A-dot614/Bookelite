# Bookelite — System Analysis & Feature Implementation Prompt
# Ready to paste into OpenCode (or any AI coding assistant)

---

## CONTEXT: What this project is

**Bookelite** is a Laravel 11 + Blade + Tailwind CSS book e-commerce website.
- Stack: PHP/Laravel 11, Blade templating, Tailwind CSS, Alpine.js (implied), MySQL
- Design: Light, editorial, serif-heavy aesthetic ("read with distinction" luxury feel)
- Current state: Early scaffold — beautiful frontend shell, almost no real e-commerce logic

---

## CURRENT SYSTEM — What exists

### Database / Models
| Table | Columns | Problems |
|-------|---------|----------|
| `ecommerces` | id, title, slug, description, price, image_url, rating (string!), is_active, timestamps | No category, author, stock, genre, ISBN, publisher |
| `users` | id, name, email, password, role (admin/user), timestamps | No phone, address, avatar |
| `sellers` | id, timestamps ONLY | Completely empty — no name, email, user_id, nothing |

### Controllers
- `EcommerceController` — only index, detail, about, contact, service (read-only, no cart/checkout)
- `AdminController` — dashboard stats, book listing, single book creation (no edit/delete)
- `SellerController` — all methods are empty stubs `//`
- Auth controllers — standard Laravel Breeze (login, register, password reset, email verify)

### Routes
- Public: `/`, `/detail/{slug}`, `/about`, `/service`, `/contact`
- Admin (auth + role): `/admin/`, `/admin/books`, `/admin/books/create`, `/admin/books/{slug}`, `/admin/customers`, `/admin/reports`
- Auth: login, register, password, verify-email
- **MISSING**: cart, checkout, orders, payment, search, wishlist, reviews, seller dashboard, product edit/delete, categories, user profile orders

### Views
- `site/home.blade.php` — hero + book listing cards
- `site/detail.blade.php` — single book detail (price hardcoded as `$30.00`, author hardcoded as "James Anderson", category/pages/language all static strings)
- `admin/dashboard.blade.php` — stats cards (total books, avg price, avg rating, recent books)
- `admin/books/form.blade.php` — create book form
- `admin/books/card.blade.php` — admin book listing
- `admin/books/carddetail.blade.php` — admin single book view

### What works
- User registration/login/logout
- Admin can create books (title, description, price, image upload or default URL)
- Public can browse books and view detail pages
- Admin dashboard shows aggregate stats

---

## GAPS — What is missing (full e-commerce audit)

### 🔴 CRITICAL (site cannot sell anything without these)
1. **Shopping Cart** — no cart table, no session cart, no CartController
2. **Checkout** — no order creation, no address collection
3. **Orders** — no `orders` or `order_items` tables/models
4. **Payment Integration** — no Stripe/PayPal/any gateway
5. **Book Edit & Delete** — AdminController has no `edit()`, `update()`, `destroy()` methods; no routes for them
6. **$30.00 is hardcoded** in detail view — price never pulls from DB in the "Investment" display block

### 🟠 IMPORTANT (expected by any e-commerce user)
7. **Search & Filter** — no search bar, no filter by category/price/rating
8. **Categories/Genres** — no category table, no relationship, "Self Development" is hardcoded text
9. **Author field** — "James Anderson" hardcoded; no author column in DB
10. **Stock/Inventory** — no quantity column, no out-of-stock state
11. **Wishlist/Favorites** — no wishlist table or UI
12. **Product Reviews & Ratings** — rating is a plain string, no reviews table, no user-submitted ratings
13. **Order History** — users cannot see past orders
14. **Email Notifications** — no order confirmation email, no shipping update
15. **Seller Dashboard** — SellerController is 100% empty stubs; sellers table has only `id` and timestamps

### 🟡 NICE TO HAVE (professional e-commerce quality)
16. **Pagination** — `Ecommerce::all()` loads everything; no pagination on listings
17. **Image Gallery** — single image_url only; no multi-image support
18. **Related/Recommended Books** — no recommendation logic
19. **Coupon/Discount Codes** — no coupons table
20. **Admin Reports** — report route renders a view that doesn't exist (`admin/books/report`)
21. **Admin Customers view** — customer route renders a view that doesn't exist (`admin/books/customer`)
22. **Soft Deletes** — no soft delete on books or users
23. **SEO Meta Tags** — no dynamic `<title>` or `<meta description>` per page
24. **breadcrumbs** — no navigation breadcrumbs on detail or admin pages

---

## IMPLEMENTATION INSTRUCTIONS FOR OPENCODE

### PHASE 1 — Fix broken/hardcoded things first

```
Fix the following in the existing Bookelite Laravel project:

1. In `resources/views/site/detail.blade.php`:
   - Replace the hardcoded `$30.00` with `${{ number_format($ecommerce->price, 2) }}`
   - Replace hardcoded "James Anderson" with `{{ $ecommerce->author ?? 'Unknown Author' }}`
   - Replace the hardcoded foreach array for Category/Pages/Language/Format with real DB fields once migration is updated

2. In `database/migrations/2026_01_27_120617_create_ecommerces_table.php`, add:
   - `$table->string('author')->nullable();`
   - `$table->string('category')->default('General');`
   - `$table->string('genre')->nullable();`
   - `$table->integer('stock')->default(0);`
   - `$table->integer('pages')->nullable();`
   - `$table->string('language')->default('English');`
   - `$table->string('isbn')->nullable()->unique();`
   - Change `$table->string('rating')` to `$table->decimal('rating', 3, 1)->default(0.0);`

3. Update `app/Models/Ecommerce.php` to add `$fillable` array with all columns.

4. Update `app/Http/Controllers/AdminController.php`:
   - Add `edit(Ecommerce $ecommerce)` method returning `view('admin.books.edit', compact('ecommerce'))`
   - Add `update(Request $request, Ecommerce $ecommerce)` with validation and save
   - Add `destroy(Ecommerce $ecommerce)` with `$ecommerce->delete()` and redirect

5. Add missing routes in `routes/web.php` inside the admin prefix group:
   - `Route::get('books/{ecommerce:slug}/edit', [AdminController::class,'edit'])->name('books.edit');`
   - `Route::put('books/{ecommerce:slug}', [AdminController::class,'update'])->name('books.update');`
   - `Route::delete('books/{ecommerce:slug}', [AdminController::class,'destroy'])->name('books.destroy');`
```

---

### PHASE 2 — Shopping Cart (session-based)

```
Add a session-based shopping cart to Bookelite (Laravel 11, Blade, Tailwind):

1. Create `app/Http/Controllers/CartController.php` with:
   - `index()` — show cart (session data)
   - `add(Request $request, Ecommerce $ecommerce)` — add item to session cart
   - `update(Request $request, $id)` — update quantity
   - `remove($id)` — remove item from cart
   - `clear()` — empty cart
   Cart stored as: `session(['cart' => [book_id => ['id', 'title', 'price', 'quantity', 'image_url']]])`

2. Add routes in `routes/web.php`:
   - `Route::get('/cart', [CartController::class,'index'])->name('cart.index');`
   - `Route::post('/cart/{ecommerce:slug}', [CartController::class,'add'])->name('cart.add');`
   - `Route::patch('/cart/{id}', [CartController::class,'update'])->name('cart.update');`
   - `Route::delete('/cart/{id}', [CartController::class,'remove'])->name('cart.remove');`

3. Create `resources/views/site/cart.blade.php` using the same light editorial design:
   - Line items with book cover, title, price, quantity input, remove button
   - Subtotal per line, grand total
   - "Proceed to Checkout" button
   - "Continue Browsing" link

4. On `site/detail.blade.php`, convert the "Add to Collection" button to a POST form:
   ```blade
   <form action="{{ route('cart.add', $ecommerce->slug) }}" method="POST">
       @csrf
       <input type="hidden" name="quantity" value="1">
       <button type="submit" class="...existing classes...">Add to Collection</button>
   </form>
   ```

5. In `resources/views/components/common/header.blade.php`, add cart icon with count badge:
   ```blade
   <a href="{{ route('cart.index') }}" class="relative">
       <svg>...cart icon...</svg>
       @if(session('cart') && count(session('cart')) > 0)
           <span class="absolute -top-2 -right-2 bg-slate-900 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
               {{ count(session('cart')) }}
           </span>
       @endif
   </a>
   ```
```

---

### PHASE 3 — Orders & Checkout

```
Add orders and checkout to Bookelite (Laravel 11, Blade, Tailwind):

1. Create migrations:

   `orders` table:
   - id, user_id (FK nullable for guests), status (enum: pending/paid/shipped/delivered/cancelled),
     subtotal, shipping_cost, total, shipping_name, shipping_address, shipping_city,
     shipping_country, shipping_zip, payment_method, payment_reference, notes, timestamps

   `order_items` table:
   - id, order_id (FK), ecommerce_id (FK), title (snapshot), price (snapshot),
     quantity, line_total, timestamps

2. Create models `Order` and `OrderItem` with relationships:
   - Order: belongsTo(User), hasMany(OrderItem)
   - OrderItem: belongsTo(Order), belongsTo(Ecommerce)
   - User: hasMany(Order)

3. Create `app/Http/Controllers/CheckoutController.php`:
   - `index()` — show checkout form (pre-fill if user logged in)
   - `store(Request $request)` — validate, create Order + OrderItems from cart session, clear cart, redirect to success
   
4. Add routes:
   - `Route::get('/checkout', [CheckoutController::class,'index'])->name('checkout.index');`
   - `Route::post('/checkout', [CheckoutController::class,'store'])->name('checkout.store');`
   - `Route::get('/order/{order}/success', [CheckoutController::class,'success'])->name('checkout.success');`

5. Create `resources/views/site/checkout.blade.php`:
   - Two-column layout: shipping form (left) + order summary (right)
   - Fields: name, email, phone, address, city, country, postal code
   - Styled in the same editorial Tailwind design (serif headings, gold accents, slate palette)

6. Create `resources/views/site/order-success.blade.php`:
   - Confirmation message with order reference number
   - Summary of what was ordered
   - "Continue Browsing" CTA
```

---

### PHASE 4 — Search & Filter

```
Add search and filter to the book listing in Bookelite:

1. Update `EcommerceController::index()`:
   ```php
   public function index(Request $request) {
       $query = Ecommerce::where('is_active', true);
       
       if ($request->filled('q')) {
           $query->where(function($q) use ($request) {
               $q->where('title', 'like', "%{$request->q}%")
                 ->orWhere('author', 'like', "%{$request->q}%")
                 ->orWhere('description', 'like', "%{$request->q}%");
           });
       }
       if ($request->filled('category')) {
           $query->where('category', $request->category);
       }
       if ($request->filled('min_price')) {
           $query->where('price', '>=', $request->min_price);
       }
       if ($request->filled('max_price')) {
           $query->where('price', '<=', $request->max_price);
       }
       if ($request->filled('sort')) {
           match($request->sort) {
               'price_asc' => $query->orderBy('price'),
               'price_desc' => $query->orderByDesc('price'),
               'rating' => $query->orderByDesc('rating'),
               default => $query->latest(),
           };
       } else {
           $query->latest();
       }
       
       $ecommerces = $query->paginate(12);
       $categories = Ecommerce::distinct()->pluck('category');
       return view('site.home', compact('ecommerces', 'categories'));
   }
   ```

2. Add search bar + filter controls to `site/home.blade.php` above the book grid:
   - Text search input
   - Category dropdown
   - Price range inputs (min/max)
   - Sort dropdown (Newest, Price Low→High, Price High→Low, Top Rated)
   - All wrapped in a `<form method="GET">` with `action="{{ route('home') }}"`

3. Replace `Ecommerce::all()` with the paginated query and add `{{ $ecommerces->links() }}` below the grid.

4. Style the pagination to match the editorial design (remove default Laravel pagination styles; use Tailwind).
```

---

### PHASE 5 — User Order History & Wishlist

```
Add order history and wishlist for authenticated users in Bookelite:

ORDER HISTORY:
1. Add route: `Route::get('/orders', [OrderController::class,'index'])->name('orders.index')->middleware('auth');`
2. Add route: `Route::get('/orders/{order}', [OrderController::class,'show'])->name('orders.show')->middleware('auth');`
3. Create `OrderController` with `index()` (list user's orders) and `show()` (single order detail with policy check)
4. Create views `site/orders/index.blade.php` and `site/orders/show.blade.php`

WISHLIST:
1. Create `wishlists` table migration: `id, user_id (FK), ecommerce_id (FK), timestamps` with unique index on (user_id, ecommerce_id)
2. Create `Wishlist` model with belongsTo(User) and belongsTo(Ecommerce)
3. Create `WishlistController` with toggle method (add if not exists, remove if exists)
4. Add route: `Route::post('/wishlist/{ecommerce:slug}', [WishlistController::class,'toggle'])->name('wishlist.toggle')->middleware('auth');`
5. Add heart icon button to each book card and detail page that POSTs to the toggle route
6. Add wishlist count to header nav
7. Create `site/wishlist.blade.php` listing saved books
```

---

### PHASE 6 — Admin Completions

```
Complete the admin panel in Bookelite:

1. Create `resources/views/admin/books/edit.blade.php` (copy structure from form.blade.php, pre-fill values with $ecommerce->*)

2. Fix `resources/views/admin/books/customer.blade.php` (currently this file doesn't exist, route 404s):
   - Create the view listing all users with role='user'
   - Show: name, email, registered date, order count, total spent

3. Fix `resources/views/admin/books/report.blade.php` (currently doesn't exist):
   - Show: total revenue (sum of paid order totals), orders per month chart data, top 5 selling books, revenue by category
   - Use Chart.js or inline SVG for simple charts

4. In `admin/dashboard.blade.php`, add real stats once orders exist:
   - Total Revenue (sum of paid orders)
   - Total Orders
   - Total Customers
   - Keep existing: Total Books, Average Price, Average Rating

5. Add Edit and Delete buttons to `admin/books/carddetail.blade.php` and `admin/books/card.blade.php`
```

---

### PHASE 7 — Seller Dashboard

```
Complete the Seller system in Bookelite (currently the sellers table only has id + timestamps and all SellerController methods are empty):

1. Update `sellers` migration to add:
   - user_id (FK to users), store_name, bio, phone, address, avatar_url, is_verified (bool), is_active (bool)

2. Add relationship: User hasOne Seller, Seller belongsTo User

3. Add seller_id FK to ecommerces table so books can be owned by a seller

4. Create seller registration flow:
   - Route: `/seller/register` → SellerController::create() + store()
   - View: form for store_name, bio, phone, address

5. Create seller dashboard (protected by seller middleware):
   - `/seller/dashboard` — overview stats (my books, total sales, revenue)
   - `/seller/books` — seller's own book listing with edit/delete
   - `/seller/books/create` — add a new book (auto-assigns seller_id)
   - `/seller/orders` — orders containing seller's books

6. Create `app/Http/Middleware/SellerCheck.php` that checks `auth()->user()->seller !== null`

7. Fill in all SellerController methods using the existing AdminController as a reference pattern
```

---

## DESIGN SYSTEM REFERENCE (for all new views)

Keep every new Blade view consistent with the existing design language:

```
Color palette:
- Background: bg-[#fafafa] or bg-[#fcfcfc]
- Primary text: text-slate-900
- Secondary text: text-slate-500 or text-slate-400
- Accent (gold): text-gold / bg-gold (defined as custom Tailwind color)
- Borders: border-slate-100 or border-slate-200

Typography:
- Headings: font-serif (large, tracked tight)
- Labels: text-[9px] or text-[10px] font-black uppercase tracking-[0.3em] to tracking-[0.5em]
- Body: text-slate-600 leading-relaxed font-light or font-serif italic

Buttons:
- Primary: bg-slate-900 text-white py-4 px-10 text-xs font-black uppercase tracking-[0.3em] hover:bg-slate-700 transition-colors
- Secondary: border border-slate-200 text-slate-600 hover:border-slate-900 hover:text-slate-900

Cards:
- bg-white border border-slate-100 shadow (subtle, not heavy)
- Hover: shadow-xl -translate-y-1 transition-all duration-300

Animations:
- Use the existing .reveal / .reveal-delay-1 / .reveal-delay-2 classes with fadeInUp keyframes
- Transitions: duration-300 to duration-1000 ease-[cubic-bezier(0.23,1,0.32,1)]

Layout:
- max-w-7xl mx-auto px-6 for page containers
- Grid: grid lg:grid-cols-12 with gap-16 or gap-20
```

---

## SUGGESTED IMPLEMENTATION ORDER

1. Phase 1 (fix hardcoding + admin CRUD) — 1–2 hours
2. Phase 4 (search/filter) — 1 hour
3. Phase 2 (cart) — 2–3 hours
4. Phase 3 (checkout + orders) — 3–4 hours
5. Phase 6 (admin completions) — 2 hours
6. Phase 5 (wishlist + order history) — 2 hours
7. Phase 7 (seller dashboard) — 4–6 hours

**Total estimated effort: ~16–20 hours of focused development**
