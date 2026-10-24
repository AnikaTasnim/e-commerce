<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\District;
use App\Models\ShippingOption;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->whereIn('category', ['CAKES', 'DESSERTS'])
            ->with('images')
            ->orderByDesc('featured')
            ->latest()
            ->get();

        return view('storefront.index', compact('products'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->status === 'active' && in_array($product->category, ['CAKES', 'DESSERTS'], true), 404);

        $product->load([
            'images',
            'variants' => fn ($query) => $query->where('is_active', true),
        ]);
        $shippingOptions = ShippingOption::query()->where('is_active', true)->orderBy('sort_order')->get();
        $districts = District::query()->where('is_active', true)
            ->with(['areas' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')->orderBy('name')->get();
        $confirmation = session('order_confirmation');

        return view('storefront.products.show', compact('product', 'shippingOptions', 'districts', 'confirmation'));
    }
}
