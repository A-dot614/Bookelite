<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{
    // ==========================================
    // Public Catalog & Pages
    // ==========================================

    public function index(Request $request)
    {
        $query = Ecommerce::where('is_active', true);

        // Search by keywords (title, author, description, isbn)
        if ($request->filled('q')) {
            $searchTerm = trim($request->q);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('author', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('isbn', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Price range filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Sorting
        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'rating' => $query->orderBy('rating', 'desc'),
                'title_asc' => $query->orderBy('title', 'asc'),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $ecommerces = $query->paginate(12)->withQueryString();

        // Get available categories for filter pills
        $categories = Ecommerce::where('is_active', true)
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('site.home', compact('ecommerces', 'categories'));
    }

    public function detail(Ecommerce $ecommerce)
    {
        return view('site.detail', compact('ecommerce'));
    }

    public function service()
    {
        return view('site.service');
    }

    public function about()
    {
        return view('site.about');
    }

    public function contact()
    {
        return view('site.contact');
    }
}