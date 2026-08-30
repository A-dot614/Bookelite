<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecommerce_id',
        'quantity_change',
        'previous_stock',
        'new_stock',
        'reason',
        'order_id',
        'user_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'quantity_change' => 'integer',
        'previous_stock' => 'integer',
        'new_stock' => 'integer',
    ];

    public function ecommerce(): BelongsTo
    {
        return $this->belongsTo(Ecommerce::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}