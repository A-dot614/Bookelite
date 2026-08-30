<?php

namespace App\Http\Controllers;

use App\Exceptions\CartException;
use App\Models\Ecommerce;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cart)
    {
    }

    public function index(): View
    {
        $contents = $this->cart->contents();
        $summary = $this->cart->summary($contents['items']);

        return view('site.cart', [
            'items' => $contents['items'],
            'notice' => $contents['notice'],
            'summary' => $summary,
        ]);
    }

    public function add(Request $request, Ecommerce $ecommerce): RedirectResponse|JsonResponse
    {
        $quantity = (int) $request->input('quantity', 1);

        try {
            $result = $this->cart->add($ecommerce, $quantity);
        } catch (CartException $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'cart_count' => $this->cart->count(),
            ]);
        }

        if ($result['status'] === 'adjusted') {
            return redirect()->route('cart.index')->with('status', $result['message']);
        }

        return redirect()->route('cart.index')->with('status', $result['message']);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $quantity = (int) $request->input('quantity', 1);

        try {
            $result = $this->cart->update($id, $quantity);
        } catch (CartException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cart.index')->with('status', $result['message']);
    }

    public function remove(int $id): RedirectResponse
    {
        $this->cart->remove($id);

        return redirect()->route('cart.index')->with('status', 'Item removed from your collection.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()->route('cart.index')->with('status', 'Collection bag cleared.');
    }
}