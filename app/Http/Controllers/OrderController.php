<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Area;
use App\Models\District;
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
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'area_id' => ['required', 'integer', 'exists:areas,id'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'shipping_option_id' => ['required', 'integer', 'exists:shipping_options,id'],
            'payment_method' => ['required', 'in:cod'],
            'return_to' => ['nullable', 'in:home'],
        ], [
            'customer_name.required' => 'আপনার নাম লিখুন।',
            'customer_name.string' => 'নামটি সঠিকভাবে লিখুন।',
            'customer_name.max' => 'নামের ঘরটি ১২০ অক্ষরের মধ্যে রাখুন।',
            'phone.required' => 'মোবাইল নম্বর লিখুন।',
            'phone.string' => 'মোবাইল নম্বরটি সঠিকভাবে লিখুন।',
            'phone.max' => 'মোবাইল নম্বরটি সঠিকভাবে লিখুন।',
            'phone.regex' => 'সঠিক বাংলাদেশি মোবাইল নম্বর লিখুন।',
            'address.required' => 'সম্পূর্ণ ঠিকানা লিখুন।',
            'address.string' => 'সম্পূর্ণ ঠিকানা লিখুন।',
            'address.max' => 'ঠিকানাটি ১,০০০ অক্ষরের মধ্যে রাখুন।',
            'district_id.required' => 'জেলা বেছে নিন।',
            'district_id.integer' => 'তালিকা থেকে একটি বৈধ জেলা বেছে নিন।',
            'district_id.exists' => 'তালিকা থেকে একটি বৈধ জেলা বেছে নিন।',
            'area_id.required' => 'এলাকা বেছে নিন।',
            'area_id.integer' => 'তালিকা থেকে একটি বৈধ এলাকা বেছে নিন।',
            'area_id.exists' => 'তালিকা থেকে একটি বৈধ এলাকা বেছে নিন।',
            'product_variant_id.required' => 'পণ্যের সাইজ বা অপশন বেছে নিন।',
            'product_variant_id.integer' => 'তালিকা থেকে একটি বৈধ অপশন বেছে নিন।',
            'product_variant_id.exists' => 'তালিকা থেকে একটি বৈধ অপশন বেছে নিন।',
            'quantity.required' => 'পণ্যের পরিমাণ লিখুন।',
            'quantity.integer' => 'পণ্যের পরিমাণ সংখ্যা হতে হবে।',
            'quantity.min' => 'অন্তত ১টি পণ্য অর্ডার করতে হবে।',
            'quantity.max' => 'একবারে সর্বোচ্চ ২০টি পণ্য অর্ডার করা যাবে।',
            'shipping_option_id.required' => 'ডেলিভারি এলাকা বেছে নিন।',
            'shipping_option_id.integer' => 'তালিকা থেকে একটি বৈধ ডেলিভারি অপশন বেছে নিন।',
            'shipping_option_id.exists' => 'তালিকা থেকে একটি বৈধ ডেলিভারি অপশন বেছে নিন।',
            'payment_method.required' => 'পেমেন্ট পদ্ধতি বেছে নিন।',
            'payment_method.in' => 'এই মুহূর্তে ক্যাশ অন ডেলিভারি সুবিধা রয়েছে।',
            'customer_notes.string' => 'অর্ডারের নোটটি সঠিকভাবে লিখুন।',
            'customer_notes.max' => 'অর্ডারের নোটটি ১,০০০ অক্ষরের মধ্যে রাখুন।',
        ]);

        $order = DB::transaction(function () use ($data, $product) {
            $lockedProduct = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();
            if ($lockedProduct->status !== 'active') {
                throw ValidationException::withMessages(['product' => 'এই পণ্যটি আর পাওয়া যাচ্ছে না।']);
            }

            $variant = ProductVariant::query()
                ->whereKey($data['product_variant_id'])
                ->where('product_id', $lockedProduct->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();
            if (! $variant) {
                throw ValidationException::withMessages(['product_variant_id' => 'পণ্যটির একটি উপলভ্য সাইজ বা অপশন বেছে নিন।']);
            }

            $quantity = (int) $data['quantity'];
            if ($variant->stock_quantity < $quantity || $lockedProduct->stock_quantity < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'আপনার বেছে নেওয়া পরিমাণটি আর মজুত নেই।']);
            }

            $shipping = ShippingOption::query()
                ->whereKey($data['shipping_option_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();
            if (! $shipping) {
                throw ValidationException::withMessages(['shipping_option_id' => 'একটি উপলভ্য ডেলিভারি অপশন বেছে নিন।']);
            }

            $district = District::query()->whereKey($data['district_id'])->where('is_active', true)->first();
            $area = Area::query()->whereKey($data['area_id'])
                ->where('district_id', $data['district_id'])->where('is_active', true)->first();
            if (! $district || ! $area) {
                throw ValidationException::withMessages(['area_id' => 'বেছে নেওয়া জেলার একটি উপলভ্য এলাকা নির্বাচন করুন।']);
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
                'district' => $district->name,
                'area' => $area->name,
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
                'product_name' => $lockedProduct->bangla_name ?: $lockedProduct->name,
                'variant_snapshot' => ['name' => $variant->name, 'value' => $variant->value],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $subtotal,
            ]);

            $variant->decrement('stock_quantity', $quantity);
            $lockedProduct->decrement('stock_quantity', $quantity);

            return $order;
        }, attempts: 3);

        $redirect = ($data['return_to'] ?? null) === 'home'
            ? redirect()->to(route('storefront.home').'#order')
            : redirect()->route('products.show', $product);

        return $redirect->with('order_confirmation', [
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'phone' => $order->phone,
                'total' => $order->total,
            ]);
    }
}
