<?php

namespace App\Services\Payment;

class PaymentResult
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';

    public function __construct(
        public string $status,
        public ?string $reference = null,
        public string $message = '',
        public array $data = []
    ) {
    }

    public function success(): bool
    {
        return $this->status !== self::STATUS_FAILED;
    }
}