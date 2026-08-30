<?php

namespace App\Services\Payment;

class PaymentManager
{
    public function provider(): PaymentProvider
    {
        return match (config('payment.driver')) {
            'stripe' => app(StripePaymentProvider::class),
            default => app(ManualPaymentProvider::class),
        };
    }

    /**
     * Payment methods currently selectable at checkout.
     *
     * Offline methods are always available; gateway methods (e.g. card) are
     * added only when the configured provider supports them.
     *
     * @return string[]
     */
    public function availableMethods(): array
    {
        return array_values(array_unique(array_merge(['bank_transfer', 'cod'], $this->provider()->methods())));
    }

    public function supports(string $method): bool
    {
        return in_array($method, $this->availableMethods(), true);
    }
}