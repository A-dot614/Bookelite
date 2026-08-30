<?php

namespace App\Services\Payment;

use App\Exceptions\CheckoutException;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

/**
 * Stripe provider driven through the public API (no SDK required).
 *
 * Only activated when a secret key is configured. Creating an intent leaves
 * the order in a payment_pending state; the order is confirmed paid via the
 * order-management workflow once capture succeeds.
 */
class StripePaymentProvider implements PaymentProvider
{
    public function name(): string
    {
        return 'stripe';
    }

    public function isConfigured(): bool
    {
        return ! empty(config('payment.providers.stripe.secret_key'));
    }

    public function methods(): array
    {
        return ['card'];
    }

    public function charge(Order $order, array $payload = []): PaymentResult
    {
        if (! $this->isConfigured()) {
            throw new CheckoutException('Card payments are not configured for this store.');
        }

        $amountMinor = (int) round($order->total * 100);
        $currency = strtolower($order->currency ?: 'usd');

        try {
            $response = Http::withBasicAuth(config('payment.providers.stripe.secret_key'), '')
                ->asForm()
                ->post('https://api.stripe.com/v1/payment_intents', [
                    'amount' => $amountMinor,
                    'currency' => $currency,
                    'automatic_payment_methods' => ['enabled' => 'true'],
                ]);
        } catch (\Throwable $e) {
            throw new CheckoutException('Payment could not be initiated. Please try again.');
        }

        if (! $response->successful()) {
            throw new CheckoutException('Payment could not be initiated: '.($response->json('error.message') ?? 'provider error'));
        }

        $intentId = $response->json('id');

        return new PaymentResult(
            status: PaymentResult::STATUS_PENDING,
            reference: $intentId,
            message: 'Payment initiated — awaiting confirmation.',
            data: ['client_secret' => $response->json('client_secret')],
        );
    }
}