<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->role === 'admin' || $order->user_id === $user->id;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }
}