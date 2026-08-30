<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ecommerce extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'title',
        'slug',
        'author',
        'description',
        'price',
        'image_url',
        'rating',
        'category',
        'genre',
        'stock',
        'pages',
        'language',
        'isbn',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'stock' => 'integer',
        'pages' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the route key for implicit model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
