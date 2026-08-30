<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CheckoutService $checkout,
        protected PaymentManager $payments,
    ) {
    }

    public function index(): View|RedirectResponse
    {
        $contents = $this->cart->contents();

        if (empty($contents['items'])) {
            return redirect()->route('cart.index')
                ->with('error', 'Your collection bag is empty. Please select books before checkout.');
        }

        $summary = $this->cart->summary($contents['items']);

        return view('site.checkout', [
            'items' => $contents['items'],
            'summary' => $summary,
            'methods' => $this->payments->availableMethods(),
            'paymentInstructions' => config('payment.instructions'),
            'user' => auth()->user(),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->checkout->checkout($request->validated());
        } catch (CheckoutException $e) {
            return redirect()->route('checkout.index')->with('error', $e->getMessage());
        }

        $message = $order->payment_status === Order::PAYMENT_PAID
            ? 'Your order has been confirmed and payment received.'
            : 'Your order has been registered. Your payment is pending confirmation; you will be notified once it clears.';

        session(['checkout_order' => $order->id]);

        return redirect()->route('checkout.success', $order->id)
            ->with('status', $message);
    }

    public function success(Order $order): View
    {
        $ownsOrder = auth()->check()
            && (auth()->id() === $order->user_id || auth()->user()->role === 'admin');

        $justCheckedOut = session('checkout_order') === $order->id;

        abort_unless($ownsOrder || $justCheckedOut, 403, 'Unauthorized');

        $order->load('items');

        return view('site.order-success', compact('order'));
    }
}