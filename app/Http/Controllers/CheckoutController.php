<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your collection bag is empty. Please select books before checkout.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = 0.00;
        $total = $subtotal + $shipping;

        $user = auth()->user();

        return view('site.checkout', compact('cart', 'subtotal', 'shipping', 'total', 'user'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your collection bag is empty.');
        }

        $validated = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['required', 'email', 'max:255'],
            'shipping_phone' => ['nullable', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_country' => ['required', 'string', 'max:100'],
            'shipping_zip' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'string', 'in:card,paypal,bank_transfer'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = 0.00;
        $total = $subtotal + $shipping;

        $order = DB::transaction(function () use ($validated, $cart, $subtotal, $shipping, $total) {
            $orderNumber = 'EA-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
                'status' => 'paid',
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
                'shipping_name' => $validated['shipping_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_phone' => $validated['shipping_phone'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_country' => $validated['shipping_country'],
                'shipping_zip' => $validated['shipping_zip'],
                'payment_method' => $validated['payment_method'],
                'payment_reference' => 'PAY-' . strtoupper(Str::random(8)),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ecommerce_id' => $item['id'],
                    'title' => $item['title'],
                    'author' => $item['author'] ?? 'Unknown Author',
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['price'] * $item['quantity'],
                    'image_url' => $item['image_url'] ?? null,
                ]);

                // Decrement inventory stock
                $book = Ecommerce::find($item['id']);
                if ($book && $book->stock > 0) {
                    $book->decrement('stock', min($item['quantity'], $book->stock));
                }
            }

            return $order;
        });

        // Clear shopping cart session
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id)
            ->with('status', 'Your order has been confirmed successfully!');
    }

    public function success(Order $order)
    {
        // If order belongs to a user and current user is logged in but different user, check policy
        if ($order->user_id && auth()->check() && auth()->id() !== $order->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $order->load('items');

        return view('site.order-success', compact('order'));
    }
}
