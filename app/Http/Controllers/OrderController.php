<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:24', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'address' => ['required', 'string', 'max:1000'],
            'district' => ['required', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:120'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'shipping_option_id' => ['required', 'integer', 'exists:shipping_options,id'],
            'payment_method' => ['required', 'in:cod'],
        ], [
            'phone.regex' => 'Enter a valid Bangladeshi mobile number.',
        ]);

        $order = DB::transaction(function () use ($data, $product) {
            $lockedProduct = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();
            if ($lockedProduct->status !== 'active') {
                throw ValidationException::withMessages(['product' => 'This product is no longer available.']);
            }

            $variant = ProductVariant::query()
                ->whereKey($data['product_variant_id'])
                ->where('product_id', $lockedProduct->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();
            if (! $variant) {
                throw ValidationException::withMessages(['product_variant_id' => 'Choose an available option for this product.']);
            }

            $quantity = (int) $data['quantity'];
            if ($variant->stock_quantity < $quantity || $lockedProduct->stock_quantity < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'The selected quantity is no longer available.']);
            }

            $shipping = ShippingOption::query()
                ->whereKey($data['shipping_option_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();
            if (! $shipping) {
                throw ValidationException::withMessages(['shipping_option_id' => 'Choose an available delivery option.']);
            }

            $regularUnitPrice = (float) $lockedProduct->regular_price + (float) $variant->price_adjustment;
            $unitPrice = max(0, $lockedProduct->currentPrice() + (float) $variant->price_adjustment);
            $subtotal = round($unitPrice * $quantity, 2);
            $discount = round(max(0, $regularUnitPrice - $unitPrice) * $quantity, 2);
            $delivery = (float) $shipping->charge;

            $order = Order::query()->create([
                'order_number' => 'US-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'district' => $data['district'],
                'area' => $data['area'] ?? null,
                'customer_notes' => $data['customer_notes'] ?? null,
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'delivery_charge' => $delivery,
                'total' => round($subtotal + $delivery, 2),
                'payment_method' => 'cod',
                'status' => 'pending',
                'shipping_option_id' => $shipping->id,
                'shipping_name_snapshot' => $shipping->name,
            ]);

            $order->items()->create([
                'product_id' => $lockedProduct->id,
                'product_variant_id' => $variant->id,
                'product_name' => $lockedProduct->name,
                'variant_snapshot' => ['name' => $variant->name, 'value' => $variant->value],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $subtotal,
            ]);

            $variant->decrement('stock_quantity', $quantity);
            $lockedProduct->decrement('stock_quantity', $quantity);

            return $order;
        }, attempts: 3);

        return redirect()
            ->route('products.show', $product)
            ->with('order_confirmation', [
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'phone' => $order->phone,
                'total' => $order->total,
            ]);
    }
}
