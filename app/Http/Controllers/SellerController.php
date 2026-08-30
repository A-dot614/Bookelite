<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    // ==========================================
    // Onboarding & Registration
    // ==========================================

    public function create()
    {
        if (auth()->user()->seller) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.register');
    }

    public function store(Request $request)
    {
        if (auth()->user()->seller) {
            return redirect()->route('seller.dashboard');
        }

        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $seller = Seller::create([
            'user_id' => auth()->id(),
            'store_name' => $validated['store_name'],
            'bio' => $validated['bio'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_verified' => true,
            'is_active' => true,
        ]);

        return redirect()->route('seller.dashboard')
            ->with('status', 'Welcome to the Curator & Seller Studio! Your bookstore has been established.');
    }

    // ==========================================
    // Seller Dashboard
    // ==========================================

    public function dashboard()
    {
        $seller = auth()->user()->seller;
        $bookIds = $seller->books()->pluck('id');

        $totalBooks = $seller->books()->count();

        // Orders containing seller's books
        $sellerOrderItems = OrderItem::whereIn('ecommerce_id', $bookIds)->get();
        $totalRevenue = $sellerOrderItems->sum('line_total');
        $totalSoldUnits = $sellerOrderItems->sum('quantity');

        $recentBooks = $seller->books()->latest()->take(5)->get();
        $recentOrderItems = OrderItem::whereIn('ecommerce_id', $bookIds)->with('order')->latest()->take(6)->get();

        return view('seller.dashboard', compact(
            'seller',
            'totalBooks',
            'totalRevenue',
            'totalSoldUnits',
            'recentBooks',
            'recentOrderItems'
        ));
    }

    // ==========================================
    // Seller Catalog Management
    // ==========================================

    public function books(Request $request)
    {
        $seller = auth()->user()->seller;
        $query = $seller->books();

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('author', 'like', "%{$searchTerm}%")
                  ->orWhere('isbn', 'like', "%{$searchTerm}%");
            });
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        return view('seller.books.index', compact('books', 'seller'));
    }

    public function createBook()
    {
        return view('seller.books.create');
    }

    public function storeBook(Request $request)
    {
        $seller = auth()->user()->seller;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'genre' => ['nullable', 'string', 'max:100'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'language' => ['nullable', 'string', 'max:50'],
            'isbn' => ['nullable', 'string', 'max:50', 'unique:ecommerces,isbn'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 2;

        while (Ecommerce::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $book = new Ecommerce();
        $book->seller_id = $seller->id;
        $book->title = $validated['title'];
        $book->slug = $slug;
        $book->author = $validated['author'];
        $book->description = $validated['description'];
        $book->price = $validated['price'];
        $book->category = $validated['category'] ?? 'General';
        $book->genre = $validated['genre'] ?? 'Literature';
        $book->stock = $validated['stock'] ?? 10;
        $book->pages = $validated['pages'] ?? 300;
        $book->language = $validated['language'] ?? 'English';
        $book->isbn = $validated['isbn'] ?? null;
        $book->rating = 5.0;
        $book->is_active = true;
        $book->image_url = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=700';

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $book->image_url = asset('storage/' . $path);
        }

        $book->save();

        return redirect()->route('seller.books.index')
            ->with('status', '“' . $book->title . '” added to your seller inventory.');
    }

    public function editBook(Ecommerce $ecommerce)
    {
        $seller = auth()->user()->seller;

        if ($ecommerce->seller_id !== $seller->id) {
            abort(403, 'Unauthorized access to book.');
        }

        return view('seller.books.edit', compact('ecommerce'));
    }

    public function updateBook(Request $request, Ecommerce $ecommerce)
    {
        $seller = auth()->user()->seller;

        if ($ecommerce->seller_id !== $seller->id) {
            abort(403, 'Unauthorized access to book.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'genre' => ['nullable', 'string', 'max:100'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'language' => ['nullable', 'string', 'max:50'],
            'isbn' => ['nullable', 'string', 'max:50', 'unique:ecommerces,isbn,' . $ecommerce->id],
            'is_active' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($validated['title'] !== $ecommerce->title) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $count = 2;
            while (Ecommerce::where('slug', $slug)->where('id', '!=', $ecommerce->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $ecommerce->slug = $slug;
        }

        $ecommerce->title = $validated['title'];
        $ecommerce->author = $validated['author'];
        $ecommerce->description = $validated['description'];
        $ecommerce->price = $validated['price'];
        $ecommerce->category = $validated['category'] ?? $ecommerce->category;
        $ecommerce->genre = $validated['genre'] ?? $ecommerce->genre;
        $ecommerce->stock = $validated['stock'] ?? $ecommerce->stock;
        $ecommerce->pages = $validated['pages'] ?? $ecommerce->pages;
        $ecommerce->language = $validated['language'] ?? $ecommerce->language;
        $ecommerce->isbn = $validated['isbn'] ?? $ecommerce->isbn;
        $ecommerce->is_active = $request->has('is_active') ? (bool)$request->is_active : true;

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $ecommerce->image_url = asset('storage/' . $path);
        }

        $ecommerce->save();

        return redirect()->route('seller.books.index')
            ->with('status', '“' . $ecommerce->title . '” updated successfully.');
    }

    public function destroyBook(Ecommerce $ecommerce)
    {
        $seller = auth()->user()->seller;

        if ($ecommerce->seller_id !== $seller->id) {
            abort(403, 'Unauthorized access to book.');
        }

        $ecommerce->delete();

        return redirect()->route('seller.books.index')
            ->with('status', 'Book removed from your inventory.');
    }

    // ==========================================
    // Seller Orders
    // ==========================================

    public function orders()
    {
        $seller = auth()->user()->seller;
        $bookIds = $seller->books()->pluck('id');

        $orderItems = OrderItem::whereIn('ecommerce_id', $bookIds)
            ->with(['order', 'book'])
            ->latest()
            ->paginate(15);

        return view('seller.orders.index', compact('orderItems', 'seller'));
    }
}
