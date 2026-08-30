<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Ecommerce extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'low_stock_threshold',
        'pages',
        'language',
        'isbn',
        'sku',
        'is_active',
        'status',
        'is_featured',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
        'pages' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ARCHIVED = 'archived';

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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('status', self::STATUS_ACTIVE);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->active()
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()->where('is_featured', true);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->active()->whereColumn('stock', '<=', 'low_stock_threshold');
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    /**
     * Recompute the aggregated rating from approved reviews.
     */
    public function refreshRating(): void
    {
        $average = $this->approvedReviews()->average('rating');

        $this->forceFill([
            'rating' => $average !== null ? round((float) $average, 1) : 0.0,
        ])->save();
    }

    public function rating(): Attribute
    {
        return Attribute::make(
            get: fn (?float $value) => $value ?? 0.0,
        );
    }

    public function seoMetaTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->seo_title ?: ($this->title . ' — Elite Archive'),
        );
    }

    public function seoMetaDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->seo_description
                ?: ($this->author
                    ? sprintf('Buy "%s" by %s at Elite Archive.', $this->title, $this->author)
                    : sprintf('Buy "%s" at Elite Archive.', $this->title)),
        );
    }
}