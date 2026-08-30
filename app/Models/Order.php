<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_country',
        'shipping_zip',
        'payment_method',
        'payment_reference',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'shipping_cost' => 'float',
        'total' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid', 'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }
}
