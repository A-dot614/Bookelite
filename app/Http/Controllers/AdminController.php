<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ==========================================
    // Admin Dashboard & Overview
    // ==========================================

    public function dashboard()
    {
        $books = Ecommerce::latest()->get();
        $orders = Order::latest()->get();
        $paidOrders = Order::whereIn('status', ['paid', 'shipped', 'delivered'])->get();
        
        $totalRevenue = $paidOrders->sum('total');
        $totalOrders = $orders->count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalBooks = $books->count();
        $averagePrice = $books->avg('price') ?? 0;
        $averageRating = $books->avg('rating') ?? 0;
        $recentBooks = $books->take(5);
        $recentOrders = $orders->take(5);

        return view('admin.dashboard', compact(
            'books',
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalBooks',
            'averagePrice',
            'averageRating',
            'recentBooks',
            'recentOrders'
        ));
    }

    // ==========================================
    // Book Inventory Management (CRUD)
    // ==========================================

    public function index(Request $request)
    {
        $query = Ecommerce::query();

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('author', 'like', "%{$searchTerm}%")
                  ->orWhere('isbn', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }

        $ecommerces = $query->latest()->paginate(15)->withQueryString();

        return view('admin.books.card', compact('ecommerces'));
    }

    public function carddetail(Ecommerce $ecommerce)
    {
        return view('admin.books.carddetail', compact('ecommerce'));
    }

    public function form()
    {
        return view('admin.books.form');
    }

    public function postBooks(Request $request)
    {
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

        return redirect()->route('admin.books.show', $book->slug)
            ->with('status', 'Book added to the collection.');
    }

    public function edit(Ecommerce $ecommerce)
    {
        return view('admin.books.edit', compact('ecommerce'));
    }

    public function update(Request $request, Ecommerce $ecommerce)
    {
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
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
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
        if (isset($validated['rating'])) {
            $ecommerce->rating = $validated['rating'];
        }
        $ecommerce->is_active = $request->has('is_active') ? (bool)$request->is_active : true;

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $ecommerce->image_url = asset('storage/' . $path);
        }

        $ecommerce->save();

        return redirect()->route('admin.books.show', $ecommerce->slug)
            ->with('status', 'Book details updated successfully.');
    }

    public function destroy(Ecommerce $ecommerce)
    {
        $ecommerce->delete();

        return redirect()->route('admin.books.index')
            ->with('status', 'Book removed from collection.');
    }

    // ==========================================
    // Customer Management
    // ==========================================

    public function customer(Request $request)
    {
        $query = User::where('role', 'user')->withCount('orders')->withSum(['orders' => function ($q) {
            $q->whereIn('status', ['paid', 'shipped', 'delivered']);
        }], 'total');

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.books.customer', compact('customers'));
    }

    // ==========================================
    // Reports & Analytics
    // ==========================================

    public function report()
    {
        $paidOrders = Order::whereIn('status', ['paid', 'shipped', 'delivered'])->get();
        $totalRevenue = $paidOrders->sum('total');
        $totalOrdersCount = Order::count();
        $avgOrderValue = $paidOrders->count() > 0 ? ($totalRevenue / $paidOrders->count()) : 0;

        // Top 5 selling titles
        $topBooks = OrderItem::select('title', 'ecommerce_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(line_total) as total_revenue'))
            ->groupBy('title', 'ecommerce_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Revenue by category
        $categoryBreakdown = DB::table('order_items')
            ->join('ecommerces', 'order_items.ecommerce_id', '=', 'ecommerces.id')
            ->select('ecommerces.category', DB::raw('SUM(order_items.line_total) as category_revenue'), DB::raw('SUM(order_items.quantity) as items_count'))
            ->groupBy('ecommerces.category')
            ->orderByDesc('category_revenue')
            ->get();

        // Recent orders
        $recentOrders = Order::latest()->take(8)->get();

        return view('admin.books.report', compact(
            'totalRevenue',
            'totalOrdersCount',
            'avgOrderValue',
            'topBooks',
            'categoryBreakdown',
            'recentOrders'
        ));
    }
}
