<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path', 'alt_text', 'is_primary', 'sort_order'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function getImageUrlAttribute(): string
    {
        if (blank($this->image_path)) {
            return '';
        }

        if (Str::startsWith($this->image_path, ['http://', 'https://', '//'])) {
            return $this->image_path;
        }

        $localPath = ltrim($this->image_path, '/');

        if (Str::startsWith($localPath, 'images/')) {
            return '/'.$localPath;
        }

        return '/'.(Str::startsWith($localPath, 'storage/') ? $localPath : 'storage/'.$localPath);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
