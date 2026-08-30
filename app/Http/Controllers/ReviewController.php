<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Ecommerce;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Ecommerce $ecommerce): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please sign in to write a review.');
        }

        $this->authorize('create-review', $ecommerce);

        $review = $ecommerce->reviews()->create([
            'user_id' => $user->id,
            'order_id' => $this->eligibleOrderId($user, $ecommerce),
            'rating' => $request->rating,
            'body' => $request->comment,
            'is_approved' => config('ecommerce.reviews_require_purchase', true) ? false : true,
        ]);

        if ($review->is_approved) {
            $ecommerce->refreshRating();
        }

        return back()->with('status', 'Thank you! Your review has been '.($review->is_approved ? 'published.' : 'submitted for moderation.'));
    }

    protected function eligibleOrderId($user, Ecommerce $ecommerce): ?int
    {
        return $user->orders()
            ->whereHas('items', fn ($q) => $q->where('ecommerce_id', $ecommerce->id))
            ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
            ->latest()
            ->value('id');
    }
}