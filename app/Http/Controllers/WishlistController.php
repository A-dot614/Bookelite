<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()
            ->with('book')
            ->latest()
            ->paginate(12);

        return view('site.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request, Ecommerce $ecommerce)
    {
        $user = auth()->user();
        $existing = Wishlist::where('user_id', $user->id)
            ->where('ecommerce_id', $ecommerce->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = '“' . $ecommerce->title . '” removed from your saved archive.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'ecommerce_id' => $ecommerce->id,
            ]);
            $status = 'added';
            $message = '“' . $ecommerce->title . '” saved to your archive wishlist.';
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
