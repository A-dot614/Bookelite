<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentProvider
{
    /**
     * Short provider identifier (e.g. "manual", "stripe").
     */
    public function name(): string;

    /**
     * Whether the provider is fully configured to accept payments.
     */
    public function isConfigured(): bool;

    /**
     * Payment methods this provider is able to process.
     *
     * @return string[] card|paypal|bank_transfer|cod
     */
    public function methods(): array;

    /**
     * Initiate payment for an order. Returns a result describing the ongoing
     * payment state; providers must never confirm payment unless it was
     * actually received.
     */
    public function charge(Order $order, array $payload = []): PaymentResult;
}