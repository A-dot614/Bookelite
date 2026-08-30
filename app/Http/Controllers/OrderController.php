<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('site.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Check policy: user can only view their own orders unless admin
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access to order details.');
        }

        $order->load('items');

        return view('site.orders.show', compact('order'));
    }
}
