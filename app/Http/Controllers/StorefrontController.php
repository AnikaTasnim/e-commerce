<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

final class StorefrontController extends Controller
{
    public function __invoke(): View
    {
        // Temporary catalog data for the storefront milestone; persistence comes later.
        $products = [
            ['id' => 'linen-shirt', 'name' => 'Everyday Linen Shirt', 'category' => 'Men', 'price' => 1490, 'was' => 1890, 'color' => 'Sand / Olive / White', 'sizes' => ['S', 'M', 'L', 'XL'], 'tag' => 'Bestseller', 'image' => 'photo-1598033129183-c4f50c736f10', 'description' => 'Easy, breathable linen-blend comfort for long days and slow weekends. A relaxed fit, thoughtful details, and a feel you will reach for again.'],
            ['id' => 'studio-kurti', 'name' => 'Studio Cotton Kurti', 'category' => 'Women', 'price' => 1790, 'was' => 2190, 'color' => 'Indigo / Ivory', 'sizes' => ['S', 'M', 'L', 'XL'], 'tag' => 'New arrival', 'image' => 'photo-1585487000160-6ebcfceb0d03', 'description' => 'A modern everyday essential in soft cotton, finished with a clean silhouette that moves easily from workdays to weekends.'],
            ['id' => 'weekend-polo', 'name' => 'Weekend Knit Polo', 'category' => 'Men', 'price' => 1290, 'was' => 1590, 'color' => 'Navy / Cream', 'sizes' => ['M', 'L', 'XL', 'XXL'], 'tag' => 'Limited offer', 'image' => 'photo-1625910513413-5fc45e3c5e5a', 'description' => 'A soft textured knit polo with a polished collar and an easy, comfortable fit. Made for everyday plans.'],
            ['id' => 'soft-weave-scarf', 'name' => 'Soft Weave Scarf', 'category' => 'Accessories', 'price' => 790, 'was' => 990, 'color' => 'Terracotta / Sand', 'sizes' => ['One size'], 'tag' => 'Easy favorite', 'image' => 'photo-1601924994987-69e26d50dc26', 'description' => 'A lightweight layer with a subtle woven texture. An effortless finishing touch, season after season.'],
            ['id' => 'city-tote', 'name' => 'Daily Carry Tote', 'category' => 'Accessories', 'price' => 1090, 'was' => 1390, 'color' => 'Natural / Black', 'sizes' => ['One size'], 'tag' => 'Bestseller', 'image' => 'photo-1590874103328-eac38a683ce7', 'description' => 'Room for the everyday, with a clean profile and sturdy handles. Your new dependable plus-one.'],
            ['id' => 'relaxed-trouser', 'name' => 'Relaxed Tailored Trouser', 'category' => 'Women', 'price' => 1590, 'was' => 1990, 'color' => 'Stone / Black', 'sizes' => ['S', 'M', 'L', 'XL'], 'tag' => 'Just landed', 'image' => 'photo-1506629905607-d9c297d8f0c8', 'description' => 'A comfortable high-rise shape with a clean, tailored finish. An easy match for all your favorite tops.'],
        ];

        foreach ($products as &$product) {
            $product['discount_percent'] = (int) round((1 - $product['price'] / $product['was']) * 100);
        }
        unset($product);

        return view('storefront.index', compact('products'));
    }
}
