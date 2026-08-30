<?php

namespace App\Policies;

use App\Models\User;

class WishlistPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
}