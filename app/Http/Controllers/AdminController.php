<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidOrderTransition;
use App\Http\Controllers\Concerns\HandlesBookMedia;
use App\Http\Requests\UpdateSellerStatusRequest;
use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Seller;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    use HandlesBookMedia;

    public function __construct(protected OrderService $orders)
    {
    }

    // ==========================================
    // Admin Dashboard & Overview (SQL aggregation)
    // ==========================================

    public function dashboard(): View
    {
        $totalRevenue = (float) Order::where('payment_status', Order::PAYMENT_PAID)->sum('total');
        $paidOrderCount = Order::where('payment_status', Order::PAYMENT_PAID)->count();
        $totalOrders = (int) Order::count();
        $pendingOrders = (int) Order::where('status', Order::STATUS_PENDING)->count();
        $totalCustomers = (int) User::where('role', 'user')->count();
        $totalBooks = (int) Ecommerce::where('is_active', true)->count();
        $pendingSellers = (int) Seller::where('status', Seller::STATUS_PENDING)->count();
        $lowStockBooks = (int) Ecommerce::where('is_active', true)->whereRaw('stock <= COALESCE(low_stock_threshold, 0) AND stock > 0')->count();
        $outOfStockBooks = (int) Ecommerce::where('is_active', true)->where('stock', '<=', 0)->count();
        $averagePrice = (float) Ecommerce::where('is_active', true)->avg('price') ?? 0;
        $averageRating = (float) Ecommerce::where('is_active', true)->avg('rating') ?? 0;

        $recentOrders = Order::with('items')->latest()->limit(5)->get();
        $recentBooks = Ecommerce::latest()->limit(5)->get();

        $revenueByDay = Order::where('payment_status', Order::PAYMENT_PAID)
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('CAST(SUM(total) AS DECIMAL(10,2)) as revenue'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day', 'desc')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'paidOrderCount',
            'totalOrders',
            'pendingOrders',
            'totalCustomers',
            'totalBooks',
            'pendingSellers',
            'lowStockBooks',
            'outOfStockBooks',
            'averagePrice',
            'averageRating',
            'recentOrders',
            'recentBooks',
            'revenueByDay'
        ));
    }

    // ==========================================
    // Book Inventory Management (CRUD)
    // ==========================================

    public function index(Request $request): View
    {
        $query = Ecommerce::query();

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('author', 'like', "%{$term}%")
                    ->orWhere('isbn', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('stock') && in_array($request->stock, ['low', 'out'], true)) {
            if ($request->stock === 'low') {
                $query->whereRaw('stock <= COALESCE(low_stock_threshold, 0) AND stock > 0');
            } else {
                $query->where('stock', '<=', 0);
            }
        }

        $ecommerces = $query->with('seller')->latest()->paginate(15)->withQueryString();

        return view('admin.books.card', compact('ecommerces'));
    }

    public function carddetail(Ecommerce $ecommerce): View
    {
        $ecommerce->load('approvedReviews.user', 'images', 'seller');

        return view('admin.books.carddetail', compact('ecommerce'));
    }

    public function form(): View
    {
        return view('admin.books.form');
    }

    public function postBooks(\App\Http\Requests\StoreBookRequest $request): RedirectResponse
    {
        $book = $this->fillBook(new Ecommerce(), $request->validated());
        $book->seller_id = null;
        $book->rating = 0.0;

        if ($request->hasFile('cover')) {
            $book->image_url = $this->storeCover($request);
        } elseif (empty($book->image_url)) {
            $book->image_url = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800';
        }

        $book->save();

        $this->storeGallery($request, $book);

        return redirect()->route('admin.books.show', $book->slug)
            ->with('status', 'Book added to the collection.');
    }

    public function edit(Ecommerce $ecommerce): View
    {
        $this->authorize('update', $ecommerce);

        return view('admin.books.edit', compact('ecommerce'));
    }

    public function update(\App\Http\Requests\UpdateBookRequest $request, Ecommerce $ecommerce): RedirectResponse
    {
        $this->authorize('update', $ecommerce);

        $this->fillBook($ecommerce, $request->validated());

        if ($request->hasFile('cover')) {
            $ecommerce->image_url = $this->storeCover($request);
        }

        $ecommerce->save();

        $this->storeGallery($request, $ecommerce);

        return redirect()->route('admin.books.show', $ecommerce->slug)
            ->with('status', 'Book details updated successfully.');
    }

    public function destroy(Ecommerce $ecommerce): RedirectResponse
    {
        $this->authorize('delete', $ecommerce);

        if ($ecommerce->orderItems()->exists()) {
            $ecommerce->forceFill(['status' => Ecommerce::STATUS_ARCHIVED, 'is_active' => false])->save();

            return redirect()->route('admin.books.index')
                ->with('status', 'This book has purchase history and was archived instead of deleted.');
        }

        $ecommerce->delete();

        return redirect()->route('admin.books.index')
            ->with('status', 'Book moved to the trash.');
    }

    public function trash(Request $request): View
    {
        $query = Ecommerce::onlyTrashed()->with('seller');

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('author', 'like', "%{$term}%")
                    ->orWhere('isbn', 'like', "%{$term}%");
            });
        }

        $ecommerces = $query->latest('deleted_at')->paginate(15)->withQueryString();

        return view('admin.books.trash', compact('ecommerces'));
    }

    public function bookRestore(Request $request, int $id): RedirectResponse
    {
        $ecommerce = Ecommerce::onlyTrashed()->findOrFail($id);

        if (! $ecommerce->restore()) {
            return redirect()->route('admin.books.trash')->with('error', 'Unable to restore this book.');
        }

        return redirect()->route('admin.books.index')
            ->with('status', '“'.$ecommerce->title.'” restored to the collection.');
    }

    /**
     * Shared create/update mapping for admin + seller forms.
     *
     * @param  array<string,mixed>  $data
     */
    protected function fillBook(Ecommerce $book, array $data): Ecommerce
    {
        $titleChanged = ($data['title'] ?? null) && $data['title'] !== $book->title;

        if ($titleChanged || empty($book->slug)) {
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
        $book->sku = $data['sku'] ?? $book->sku ?? $this->generateSku($book->isbn, $book->title);
        $book->seo_title = $data['seo_title'] ?? $book->seo_title ?? null;
        $book->seo_description = $data['seo_description'] ?? $book->seo_description ?? null;
        $book->is_active = isset($data['is_active']) ? isset($data['is_active']) : ($book->is_active ?? true);
        $book->is_featured = isset($data['is_featured']) ? isset($data['is_featured']) : ($book->is_featured ?? false);
        $book->status = $data['status'] ?? $book->status ?? Ecommerce::STATUS_ACTIVE;

        if ($book->is_active && $book->status === Ecommerce::STATUS_ACTIVE && ! $book->published_at) {
            $book->published_at = now();
        }

        return $book;
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

    protected function uniqueSku(Ecommerce $book, string $candidate): string
    {
        $sku = $candidate;
        $original = $sku;
        $count = 2;
        while (Ecommerce::where('sku', $sku)->where('id', '!=', $book->id)->exists()) {
            $sku = $original.'-'.$count++;
        }

        return $sku;
    }

    protected function generateSku(?string $isbn, string $title): string
    {
        $base = $isbn
            ? preg_replace('/[^A-Za-z0-9]/', '', $isbn)
            : strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $title), 0, 8));

        $base = strtoupper(substr($base, 0, 16));
        if (empty($base)) {
            $base = 'BK'.random_int(1000, 9999);
        }

        return $this->uniqueSku(new Ecommerce(), $base);
    }

    // ==========================================
    // Customer Management
    // ==========================================

    public function customer(Request $request): View
    {
        $query = User::withTrashed()->where('role', 'user')
            ->withCount('orders')
            ->withSum(['orders' => fn ($q) => $q->where('payment_status', Order::PAYMENT_PAID)], 'total');

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.books.customer', compact('customers'));
    }

    public function customerRestore(Request $request, int $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        if (! $user->restore()) {
            return redirect()->route('admin.customers.index')->with('error', 'Unable to restore this customer.');
        }

        return redirect()->route('admin.customers.index')->with('status', 'Customer “'.$user->name.'” restored.');
    }

    // ==========================================
    // Reports & Analytics
    // ==========================================

    public function report(): View
    {
        $paidOrders = Order::where('payment_status', Order::PAYMENT_PAID);
        $totalRevenue = (float) (clone $paidOrders)->sum('total');
        $paidCount = (int) (clone $paidOrders)->count();
        $totalOrdersCount = (int) Order::count();
        $avgOrderValue = $paidCount > 0 ? round($totalRevenue / $paidCount, 2) : 0;
        $totalUnitsSold = (int) OrderItem::whereHas('order', fn ($q) => $q->whereIn('status', ['paid', 'processing', 'shipped', 'delivered']))->sum('quantity');

        // Monthly revenue for the last 12 months (paid orders only).
        $monthly = Order::where('payment_status', Order::PAYMENT_PAID)
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('CAST(SUM(total) AS DECIMAL(10,2)) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('orders', 'month');

        $monthlyRevenue = collect(range(0, 11))->reverse()->mapWithKeys(function ($i) use ($monthly) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $key = $monthStart->format('Y-m');
            $label = $monthStart->format('M y');

            return [$label => [
                'revenue' => (float) ($monthly[$key] ?? 0),
                'orders' => (int) ($monthly[$key] ?? 0),
            ]];
        })->reverse();

        $topBooks = OrderItem::select(
            'title',
            'ecommerce_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('CAST(SUM(line_total) AS DECIMAL(10,2)) as total_revenue')
        )
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['paid', 'processing', 'shipped', 'delivered']))
            ->groupBy('title', 'ecommerce_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $categoryBreakdown = DB::table('order_items')
            ->join('ecommerces', 'order_items.ecommerce_id', '=', 'ecommerces.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered'])
            ->select(
                'ecommerces.category',
                DB::raw('CAST(SUM(order_items.line_total) AS DECIMAL(10,2)) as category_revenue'),
                DB::raw('SUM(order_items.quantity) as items_count')
            )
            ->groupBy('ecommerces.category')
            ->orderByDesc('category_revenue')
            ->get();

        $recentOrders = Order::latest()->take(8)->get();

        return view('admin.books.report', compact(
            'totalRevenue',
            'totalOrdersCount',
            'avgOrderValue',
            'totalUnitsSold',
            'monthlyRevenue',
            'topBooks',
            'categoryBreakdown',
            'recentOrders'
        ));
    }

    public function reportsExport(): \Illuminate\Http\Response
    {
        $rows = OrderItem::select(
            'title',
            'author',
            'sku',
            'ecommerce_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('CAST(SUM(line_total) AS DECIMAL(10,2)) as total_revenue')
        )
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['paid', 'processing', 'shipped', 'delivered']))
            ->groupBy('title', 'author', 'sku', 'ecommerce_id')
            ->orderByDesc('total_sold')
            ->get();

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Title', 'Author', 'SKU', 'Units Sold', 'Revenue']);

        foreach ($rows as $row) {
            fputcsv($handle, [$row->title, $row->author, $row->sku, $row->total_sold, number_format((float) $row->total_revenue, 2, '.', '')]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bookelite-report-'.now()->format('Y-m-d').'.csv"',
        ]);
    }

    // ==========================================
    // Order Management
    // ==========================================

    public function orders(Request $request): View
    {
        $query = Order::with('user', 'items');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                    ->orWhere('shipping_name', 'like', "%{$term}%")
                    ->orWhere('shipping_email', 'like', "%{$term}%")
                    ->orWhere('payment_reference', 'like', "%{$term}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function orderShow(Order $order): View
    {
        $this->authorize('view', $order);
        $order->load('items', 'user');

        return view('admin.orders.show', [
            'order' => $order,
            'allowedTransitions' => $this->orders->allowedTransitions($order->status),
        ]);
    }

    public function orderMarkPaid(Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        if ($order->isPaid()) {
            return back()->with('error', 'This order is already marked as paid.');
        }

        $this->orders->markPaid($order);

        return back()->with('status', 'Payment confirmed for order '.$order->order_number.'.');
    }

    public function orderTransition(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => ['required', 'in:paid,processing,shipped,delivered,cancelled,refunded'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $this->orders->transition(
                $order,
                $validated['status'],
                $request->filled('tracking_number') ? $validated['tracking_number'] : null
            );
        } catch (InvalidOrderTransition $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('status', 'Order '.$order->order_number.' updated.');
    }

    // ==========================================
    // Seller Approval Workflow
    // ==========================================

    public function sellers(Request $request): View
    {
        $query = Seller::with('user')->withCount('books');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $sellers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.sellers.index', compact('sellers'));
    }

    public function sellerShow(Seller $seller): View
    {
        $seller->load('user', 'books');

        return view('admin.sellers.show', compact('seller'));
    }

    public function sellerUpdateStatus(UpdateSellerStatusRequest $request, Seller $seller): RedirectResponse
    {
        $seller->status = $request->status;
        $seller->is_verified = $request->status === 'approved';
        $seller->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        $seller->reviewed_at = now();
        $seller->is_active = $request->status !== 'suspended';
        $seller->save();

        $message = match ($request->status) {
            'approved' => 'Seller "'.$seller->store_name.'" was approved.',
            'rejected' => 'Seller "'.$seller->store_name.'" was rejected.',
            default => 'Seller "'.$seller->store_name.'" was suspended.',
        };

        return back()->with('status', $message);
    }

    // ==========================================
    // Review Moderation
    // ==========================================

    public function reviews(Request $request): View
    {
        $query = Review::with('user', 'book');

        if ($request->filled('status') && $request->status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($request->filled('status') && $request->status === 'approved') {
            $query->where('is_approved', true);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function reviewToggle(Review $review): RedirectResponse
    {
        $review->is_approved = ! $review->is_approved;
        $review->save();

        $review->book?->refreshRating();

        return back()->with('status', $review->is_approved
            ? 'Review approved and book rating updated.'
            : 'Review hidden and book rating updated.');
    }
}