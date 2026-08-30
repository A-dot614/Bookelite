<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Offline payment provider. Supports bank transfer and cash on delivery.
 *
 * Orders placed through it are always created in a payment_pending state —
 * this driver cannot and does not confirm payment itself. Confirmation is a
 * deliberate, recorded action performed by staff.
 */
class ManualPaymentProvider implements PaymentProvider
{
    public function name(): string
    {
        return 'manual';
    }

    public function isConfigured(): bool
    {
        return true;
    }

    public function methods(): array
    {
        return ['bank_transfer', 'cod'];
    }

    public function charge(Order $order, array $payload = []): PaymentResult
    {
        $reference = 'REF-'.strtoupper(Str::random(4)).'-'.random_int(1000, 9999);

        return new PaymentResult(
            status: PaymentResult::STATUS_PENDING,
            reference: $reference,
            message: 'Payment instruction issued — awaiting confirmation.',
        );
    }
}