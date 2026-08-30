<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SellerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Seller $seller): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role !== 'admin' && ! $user->seller()->exists();
    }

    public function update(User $user, Seller $seller): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $seller->user_id === $user->id && ! $seller->isRejected();
    }

    public function delete(User $user, Seller $seller): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Seller $seller): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Seller $seller): bool
    {
        return $user->role === 'admin';
    }
}