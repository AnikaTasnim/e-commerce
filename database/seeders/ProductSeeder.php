<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            ['linen-shirt', 'Everyday Linen Shirt', 'MEN', 1890, 1490, 'Sand / Olive / White', 'Linen blend', 'Breathable, pre-washed fabric with a relaxed everyday fit.', 'Easy, breathable linen-blend comfort for long days and slow weekends.', 'photo-1598033129183-c4f50c736f10', 'photo-1576566588028-4147f3842f27', ['S', 'M', 'L', 'XL']],
            ['studio-kurti', 'Studio Cotton Kurti', 'WOMEN', 2190, 1790, 'Indigo / Ivory', 'Soft cotton', 'Soft hand-feel cotton with a clean, comfortable silhouette.', 'A modern everyday essential that moves easily from workdays to weekends.', 'photo-1585487000160-6ebcfceb0d03', 'photo-1610030469983-98e550d6193c', ['S', 'M', 'L', 'XL']],
            ['weekend-polo', 'Weekend Knit Polo', 'MEN', 1590, 1290, 'Navy / Cream', 'Cotton knit', 'A soft textured knit with a polished collar and easy fit.', 'A comfortable, considered polo for everyday plans.', 'photo-1625910513413-5fc45e3c5e5a', 'photo-1627225924765-552d49cf47ad', ['M', 'L', 'XL', 'XXL']],
            ['soft-weave-scarf', 'Soft Weave Scarf', 'ACCESSORIES', 990, 790, 'Terracotta / Sand', 'Lightweight weave', 'Lightweight woven fabric designed for year-round layering.', 'An effortless finishing touch, season after season.', 'photo-1601924994987-69e26d50dc26', 'photo-1520903920243-00d872a2d1c9', ['One size']],
            ['city-tote', 'Daily Carry Tote', 'ACCESSORIES', 1390, 1090, 'Natural / Black', 'Cotton canvas', 'Durable canvas, sturdy handles, and a practical everyday shape.', 'Room for the everyday, with a clean profile and sturdy handles.', 'photo-1590874103328-eac38a683ce7', 'photo-1548036328-c9fa89d128fa', ['One size']],
            ['relaxed-trouser', 'Relaxed Tailored Trouser', 'WOMEN', 1990, 1590, 'Stone / Black', 'Viscose blend', 'Soft drape with a comfortable high rise and a tailored finish.', 'An easy match for your favorite tops, from desk to dinner.', 'photo-1506629905607-d9c297d8f0c8', 'photo-1594633312681-425c7b97ccd1', ['S', 'M', 'L', 'XL']],
        ];

        foreach ($catalog as $index => [$slug, $name, $category, $regular, $discount, $colors, $material, $quality, $short, $photo, $detailPhoto, $sizes]) {
            $imageUrl = "https://images.unsplash.com/{$photo}?auto=format&fit=crop&w=1200&q=85";
            $product = Product::firstOrCreate(['slug' => $slug], [
                'name' => $name,
                'sku' => strtoupper(str_replace('-', '', $slug)),
                'category' => $category,
                'description' => "{$short} Available in {$colors}. Choose your preferred size and place your order on this page. Easy exchange support and cash on delivery across Bangladesh.",
                'short_description' => $short,
                'regular_price' => $regular,
                'discount_price' => $discount,
                'stock_quantity' => count($sizes) * 12,
                'status' => 'active',
                'featured' => $index < 3,
                'main_image' => $imageUrl,
                'material' => $material,
                'quality_note' => $quality,
            ]);

            $product->images()->updateOrCreate(['image_path' => $imageUrl], ['alt_text' => $name, 'is_primary' => true, 'sort_order' => 0]);
            $product->images()->firstOrCreate(
                ['image_path' => "https://images.unsplash.com/{$detailPhoto}?auto=format&fit=crop&w=900&q=80"],
                ['alt_text' => "{$name} detail", 'is_primary' => false, 'sort_order' => 1],
            );

            foreach ($sizes as $order => $size) {
                $product->variants()->firstOrCreate(['name' => 'Size', 'value' => $size], [
                    'sku' => strtoupper(str_replace('-', '', $slug)).'-'.str_replace(' ', '', strtoupper($size)),
                    'stock_quantity' => 12,
                    'price_adjustment' => $size === 'XXL' ? 100 : 0,
                    'is_active' => true,
                    'sort_order' => $order,
                ]);
            }
        }
    }
}
