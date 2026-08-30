<?php

namespace App\Policies;

use App\Models\Ecommerce;
use App\Models\User;

class EcommercePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ecommerce $book): bool
    {
        return true;
    }

    public function update(User $user, Ecommerce $book): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $book->seller_id !== null
            && $user->seller
            && $user->seller->id === $book->seller_id
            && $user->seller->isApproved();
    }

    public function delete(User $user, Ecommerce $book): bool
    {
        return $this->update($user, $book);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin'
            || ($user->seller !== null && $user->seller->isApproved());
    }
}