<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'discount_amount',
        'coupon_id',
        'coupon_code',
        'total',
        'currency',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_country',
        'shipping_zip',
        'payment_method',
        'payment_reference',
        'tracking_number',
        'notes',
        'stock_restored',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'refunded_at',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'shipping_cost' => 'float',
        'tax_amount' => 'float',
        'discount_amount' => 'float',
        'total' => 'float',
        'stock_restored' => 'boolean',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PAID, self::STATUS_DELIVERED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::STATUS_PROCESSING => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            self::STATUS_SHIPPED => 'bg-blue-50 text-blue-700 border-blue-200',
            self::STATUS_CANCELLED => 'bg-red-50 text-red-700 border-red-200',
            self::STATUS_REFUNDED => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match ($this->payment_status) {
            self::PAYMENT_PAID => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::PAYMENT_FAILED => 'bg-red-50 text-red-700 border-red-200',
            self::PAYMENT_REFUNDED => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }
}