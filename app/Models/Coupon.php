<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isUsable(float $subtotal): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->usage_limit !== null && (int) $this->used_count >= (int) $this->usage_limit) {
            return false;
        }

        if ($this->starts_at !== null && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at !== null && now()->gt($this->expires_at)) {
            return false;
        }

        if ($this->min_order_amount !== null && $subtotal < (float) $this->min_order_amount) {
            return false;
        }

        return true;
    }

    public function discountFor(float $subtotal): float
    {
        $subtotal = round($subtotal, 2);

        if ($this->type === self::TYPE_PERCENTAGE) {
            $discount = round($subtotal * ((float) $this->value / 100), 2);

            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }

            return round($discount, 2);
        }

        return round(min((float) $this->value, $subtotal), 2);
    }

    public function typeLabel(): string
    {
        if ($this->type === self::TYPE_FIXED) {
            return config('ecommerce.currency_symbol', '$').number_format((float) $this->value, 2).' off';
        }

        return rtrim(rtrim(number_format((float) $this->value, 2), '0'), '.').'% off';
    }
}