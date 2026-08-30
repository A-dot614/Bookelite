<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = $subtotal > 0 ? 0.00 : 0.00; // Complimentary shipping
        $total = $subtotal + $shipping;

        return view('site.cart', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request, Ecommerce $ecommerce)
    {
        $quantity = (int) $request->input('quantity', 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Check if stock is available
        if ($ecommerce->stock < 1) {
            return back()->with('error', 'This book is currently out of stock.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$ecommerce->id])) {
            $newQuantity = $cart[$ecommerce->id]['quantity'] + $quantity;
            if ($ecommerce->stock > 0 && $newQuantity > $ecommerce->stock) {
                $newQuantity = $ecommerce->stock;
            }
            $cart[$ecommerce->id]['quantity'] = $newQuantity;
        } else {
            $cart[$ecommerce->id] = [
                'id' => $ecommerce->id,
                'slug' => $ecommerce->slug,
                'title' => $ecommerce->title,
                'author' => $ecommerce->author ?? 'Unknown Author',
                'price' => (float) $ecommerce->price,
                'quantity' => min($quantity, $ecommerce->stock > 0 ? $ecommerce->stock : 1),
                'image_url' => $ecommerce->image_url,
                'max_stock' => $ecommerce->stock,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Added to your archive collection.',
                'cart_count' => count($cart),
            ]);
        }

        return redirect()->route('cart.index')
            ->with('status', '“' . $ecommerce->title . '” has been added to your collection.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $ecommerce = Ecommerce::find($id);
            $qty = (int) $request->quantity;
            if ($ecommerce && $ecommerce->stock > 0 && $qty > $ecommerce->stock) {
                $qty = $ecommerce->stock;
            }
            $cart[$id]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('status', 'Collection bag updated.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('status', 'Item removed from collection.');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')
            ->with('status', 'Collection bag cleared.');
    }
}
