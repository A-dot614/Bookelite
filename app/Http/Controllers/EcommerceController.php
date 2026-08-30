<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EcommerceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ecommerce::published();

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('author', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('isbn', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('genre') && $request->genre !== 'all') {
            $query->where('genre', $request->genre);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', (float) $request->rating);
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        match ($request->sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderBy('rating', 'desc')->orderBy('title', 'asc'),
            'title_asc' => $query->orderBy('title', 'asc'),
            'featured' => $query->orderBy('is_featured', 'desc')->latest(),
            default => $query->latest(),
        };

        $ecommerces = $query->paginate(config('ecommerce.per_page', 12))->withQueryString();

        $categories = Ecommerce::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $genres = Ecommerce::published()
            ->whereNotNull('genre')
            ->select('genre')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre');

        return view('site.home', compact('ecommerces', 'categories', 'genres'));
    }

    public function detail(Ecommerce $ecommerce): View
    {
        $isPublished = $ecommerce->is_active && $ecommerce->status === Ecommerce::STATUS_ACTIVE;

        if (! $isPublished && ! ($this->canPreview($ecommerce))) {
            abort(404);
        }

        $ecommerce->load([
            'approvedReviews.user',
            'images',
            'seller',
        ]);

        $ecommerce->loadCount('approvedReviews');

        $related = Ecommerce::published()
            ->where('id', '!=', $ecommerce->id)
            ->where(function ($q) use ($ecommerce) {
                $q->where('category', $ecommerce->category)
                    ->orWhere('genre', $ecommerce->genre)
                    ->orWhere('author', $ecommerce->author);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('site.detail', compact('ecommerce', 'related'));
    }

    protected function canPreview(Ecommerce $book): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        return $user->seller && $user->seller->id === $book->seller_id;
    }

    public function service(): View
    {
        return view('site.service');
    }

    public function about(): View
    {
        return view('site.about');
    }

    public function contact(): View
    {
        return view('site.contact');
    }
}