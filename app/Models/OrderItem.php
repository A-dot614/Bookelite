<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ecommerce_id',
        'title',
        'author',
        'price',
        'quantity',
        'line_total',
        'image_url',
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'line_total' => 'float',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Ecommerce::class, 'ecommerce_id');
    }

    public function ecommerce(): BelongsTo
    {
        return $this->belongsTo(Ecommerce::class, 'ecommerce_id');
    }
}
