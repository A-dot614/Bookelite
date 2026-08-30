<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SellerController extends Controller
{
    // ==========================================
    // Onboarding & Registration
    // ==========================================

    public function create(): View|RedirectResponse
    {
        $seller = auth()->user()->seller;

        return view('seller.register', ['seller' => $seller]);
    }

    public function store(StoreSellerRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->seller) {
            return redirect()->route('seller.register')
                ->with('status', 'You already have a merchant profile.');
        }

        Seller::create([
            'user_id' => $user->id,
            'store_name' => $request->store_name,
            'bio' => $request->bio ?? null,
            'phone' => $request->phone ?? null,
            'address' => $request->address ?? null,
            'is_verified' => false,
            'is_active' => true,
            'status' => Seller::STATUS_PENDING,
        ]);

        return redirect()->route('seller.register')
            ->with('status', 'Thank you! Your shop application is under review. You will be able to list books once approved.');
    }

    // ==========================================
    // Seller Dashboard
    // ==========================================

    public function dashboard(): View
    {
        $seller = auth()->user()->seller;
        $bookIds = $seller->books()->pluck('id');

        $totalBooks = $seller->books()->count();

        // Aggregate over order items that belong to this seller AND are tied to
        // an order that has not been cancelled/refunded.
        $pipeline = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_PAID,
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
        ])
            ->whereHas('items', fn ($q) => $q->whereIn('ecommerce_id', $bookIds));

        $revenue = (float) (clone $pipeline)->sum('total');
        $pendingPaymentRevenue = (float) (clone $pipeline)
            ->where('payment_status', '!=', Order::PAYMENT_PAID)
            ->sum('total');
        $totalSoldUnits = (int) (clone $pipeline)->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.ecommerce_id', $bookIds)
            ->distinct('order_items.id')
            ->sum('order_items.quantity');

        $recentBooks = $seller->books()->latest()->take(5)->get();
        $recentOrderItems = OrderItem::whereIn('ecommerce_id', $bookIds)
            ->with('order')
            ->whereHas('order', fn ($q) => $q->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
            ]))
            ->latest()
            ->take(6)
            ->get();

        return view('seller.dashboard', compact(
            'seller',
            'totalBooks',
            'revenue',
            'pendingPaymentRevenue',
            'totalSoldUnits',
            'recentBooks',
            'recentOrderItems'
        ));
    }

    // ==========================================
    // Seller Catalog Management
    // ==========================================

    public function books(Request $request): View
    {
        $seller = auth()->user()->seller;
        $query = $seller->books();

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('author', 'like', "%{$term}%")
                    ->orWhere('isbn', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        return view('seller.books.index', compact('books', 'seller'));
    }

    public function createBook(): View
    {
        return view('seller.books.create');
    }

    public function storeBook(StoreBookRequest $request): RedirectResponse
    {
        $seller = auth()->user()->seller;

        $book = new Ecommerce();
        $book->seller_id = $seller->id;
        $book->rating = 0.0;
        $book->is_active = $request->boolean('is_active', true);
        $book->status = $request->input('status', Ecommerce::STATUS_ACTIVE);

        $this->populateBook($book, $request->validated());

        if ($request->hasFile('cover')) {
            $book->image_url = $this->storeCover($request);
        } elseif (empty($book->image_url)) {
            $book->image_url = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800';
        }

        $book->save();

        return redirect()->route('seller.books.index')
            ->with('status', '“'.$book->title.'” added to your seller inventory.');
    }

    public function editBook(Ecommerce $ecommerce): View
    {
        $this->authorize('update', $ecommerce);

        return view('seller.books.edit', compact('ecommerce'));
    }

    public function updateBook(UpdateBookRequest $request, Ecommerce $ecommerce): RedirectResponse
    {
        $this->authorize('update', $ecommerce);

        $this->populateBook($ecommerce, $request->validated());
        $ecommerce->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('cover')) {
            $ecommerce->image_url = $this->storeCover($request);
        }

        $ecommerce->save();

        return redirect()->route('seller.books.index')
            ->with('status', '“'.$ecommerce->title.'” updated successfully.');
    }

    public function destroyBook(Ecommerce $ecommerce): RedirectResponse
    {
        $this->authorize('delete', $ecommerce);

        // Preserve order history: archive instead of hard delete when used.
        if ($ecommerce->orderItems()->exists()) {
            $ecommerce->forceFill(['status' => Ecommerce::STATUS_ARCHIVED, 'is_active' => false])->save();

            return redirect()->route('seller.books.index')
                ->with('status', 'This book has order history and was archived instead of deleted.');
        }

        $ecommerce->delete();

        return redirect()->route('seller.books.index')
            ->with('status', 'Book removed from your inventory.');
    }

    // ==========================================
    // Seller Orders
    // ==========================================

    public function orders(): View
    {
        $seller = auth()->user()->seller;
        $bookIds = $seller->books()->pluck('id');

        $orderItems = OrderItem::whereIn('ecommerce_id', $bookIds)
            ->with(['order', 'book'])
            ->latest()
            ->paginate(15);

        return view('seller.orders.index', compact('orderItems', 'seller'));
    }

    /**
     * Shared mapping for seller book forms.
     *
     * @param  array<string,mixed>  $data
     */
    protected function populateBook(Ecommerce $book, array $data): void
    {
        if (empty($book->slug)) {
            $book->slug = $this->uniqueSlug($data['title']);
        } elseif ($data['title'] !== $book->title) {
            $book->slug = $this->uniqueSlug($data['title']);
        }

        $book->title = $data['title'];
        $book->author = $data['author'];
        $book->description = $data['description'];
        $book->price = $data['price'];
        $book->category = $data['category'] ?? $book->category ?? 'General';
        $book->genre = $data['genre'] ?? $book->genre ?? 'Literature';
        $book->stock = (int) ($data['stock'] ?? $book->stock ?? 10);
        $book->low_stock_threshold = (int) ($data['low_stock_threshold'] ?? $book->low_stock_threshold ?? 5);
        $book->pages = (int) ($data['pages'] ?? $book->pages ?? 300);
        $book->language = $data['language'] ?? $book->language ?? 'English';
        $book->isbn = $data['isbn'] ?? $book->isbn ?? null;
        $book->sku = $data['sku'] ?? $book->sku ?? $this->generateSku($book);
        $book->seo_title = $data['seo_title'] ?? $book->seo_title ?? null;
        $book->seo_description = $data['seo_description'] ?? $book->seo_description ?? null;
        $book->is_featured = isset($data['is_featured']) ? $data['is_featured'] : ($book->is_featured ?? false);

        if ($book->status === Ecommerce::STATUS_ACTIVE && $book->is_active && ! $book->published_at) {
            $book->published_at = now();
        }
    }

    protected function uniqueSlug(string $title): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $original = $slug;
        $count = 2;
        while (Ecommerce::where('slug', $slug)->exists()) {
            $slug = $original.'-'.$count++;
        }

        return $slug;
    }

    protected function generateSku(Ecommerce $book): string
    {
        $base = $book->isbn
            ? preg_replace('/[^A-Za-z0-9]/', '', $book->isbn)
            : strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $book->title), 0, 8));

        $base = strtoupper(substr($base, 0, 16));
        if (empty($base)) {
            $base = 'BK'.random_int(1000, 9999);
        }

        $candidate = $base;
        $count = 2;
        while (Ecommerce::where('sku', $candidate)->exists()) {
            $candidate = $base.'-'.$count++;
        }

        return $candidate;
    }

    protected function storeCover(Request $request): string
    {
        $path = $request->file('cover')->store('covers', 'public');

        return asset('storage/'.$path);
    }
}