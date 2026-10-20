<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

    protected $fillable = [
        'order_number', 'customer_name', 'phone', 'address', 'district', 'area', 'customer_notes',
        'subtotal', 'discount_total', 'delivery_charge', 'total', 'payment_method', 'status',
        'shipping_option_id', 'shipping_name_snapshot',
    ];

    protected function casts(): array
    {
        return ['subtotal' => 'decimal:2', 'discount_total' => 'decimal:2', 'delivery_charge' => 'decimal:2', 'total' => 'decimal:2'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingOption(): BelongsTo
    {
        return $this->belongsTo(ShippingOption::class);
    }
}
