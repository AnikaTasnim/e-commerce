<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOption extends Model
{
    protected $fillable = ['name', 'key', 'charge', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['charge' => 'integer', 'is_active' => 'boolean'];
    }
}
