<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'bangla_name', 'slug', 'sku', 'category', 'description', 'short_description',
        'regular_price', 'discount_price', 'stock_quantity', 'status',
        'featured', 'main_image', 'material', 'quality_note',
        'features',
    ];

    protected function casts(): array
    {
        return ['regular_price' => 'decimal:2', 'discount_price' => 'decimal:2', 'featured' => 'boolean', 'features' => 'array'];
    }

    public function getMainImageUrlAttribute(): string
    {
        $path = $this->main_image;

        if (blank($path)) {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $localPath = ltrim($path, '/');

        return '/'.(Str::startsWith($localPath, 'storage/') ? $localPath : 'storage/'.$localPath);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function currentPrice(): float
    {
        return (float) ($this->discount_price ?? $this->regular_price);
    }

    public function discountPercent(): int
    {
        return $this->discount_price && $this->regular_price > 0
            ? (int) round((1 - $this->discount_price / $this->regular_price) * 100)
            : 0;
    }
}
