<?php

namespace App\Policies;

use App\Models\Ecommerce;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user, Ecommerce $book): bool
    {
        if ($user->role === 'admin') {
            return false;
        }

        $requiresPurchase = config('ecommerce.reviews_require_purchase', true);

        if ($requiresPurchase) {
            $purchased = $user->orders()
                ->whereHas('items', fn ($q) => $q->where('ecommerce_id', $book->id))
                ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->exists();

            if (! $purchased) {
                return false;
            }
        }

        return ! $book->reviews()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function update(User $user, Review $review): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $review->user_id === $user->id && ! $review->is_approved;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->role === 'admin' || $review->user_id === $user->id;
    }
}