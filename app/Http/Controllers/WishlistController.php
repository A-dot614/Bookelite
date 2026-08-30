<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = auth()->user()->wishlists()
            ->with('book')
            ->whereHas('book', function ($q) {
                $q->where('is_active', true)->where('status', Ecommerce::STATUS_ACTIVE);
            })
            ->latest()
            ->paginate(12);

        return view('site.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request, Ecommerce $ecommerce): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        $hidden = ! $ecommerce->is_active || $ecommerce->status !== Ecommerce::STATUS_ACTIVE;

        if ($hidden && $user->role !== 'admin') {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'That book is no longer available.'], 422)
                : back()->with('error', 'That book is no longer available.');
        }

        $existing = Wishlist::where('user_id', $user->id)
            ->where('ecommerce_id', $ecommerce->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = '“'.$ecommerce->title.'” removed from your saved archive.';
        } else {
            // Guard against duplicate rows created by double-submission.
            Wishlist::updateOrCreate(
                ['user_id' => $user->id, 'ecommerce_id' => $ecommerce->id],
                ['user_id' => $user->id, 'ecommerce_id' => $ecommerce->id],
            );
            $status = 'added';
            $message = '“'.$ecommerce->title.'” saved to your archive wishlist.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
                'wishlist_count' => $user->wishlists()->count(),
            ]);
        }

        return back()->with('status', $message);
    }
}