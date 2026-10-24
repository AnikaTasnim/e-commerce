<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            ['black-forest-cake', 'Black Forest Cake', 'CAKES', 'black-forest.jpeg', 1250, 'Chocolate sponge layered with cream and cherry, finished with chocolate shavings.', 'A celebration classic with rich chocolate, whipped cream, and cherry.', '0.5 kg', '1 kg', '2 kg'],
            ['blueberry-cheesecake', 'Blueberry Cheesecake', 'CAKES', 'blueberry-cheesecake.png', 1450, 'Creamy baked cheesecake with a buttery biscuit base and blueberry topping.', 'Silky cheesecake, bright blueberries, and a crisp biscuit base.', '6 inch', '8 inch', '10 inch'],
            ['classic-cheesecake', 'Classic Cheesecake', 'CAKES', 'cheese-cake.jpg', 1350, 'Smooth, creamy cheesecake on a golden crumb crust. Simple, rich, and made for sharing.', 'A creamy New York style favorite on a buttery crumb crust.', '6 inch', '8 inch', '10 inch'],
            ['chocolate-brownie-box', 'Chocolate Brownie Box', 'DESSERTS', 'chocolate-brownie.jpg', 450, 'Fudgy chocolate brownies with a delicate crackly top, baked in small batches.', 'Deep chocolate brownies with soft, fudgy centers.', '4 pieces', '8 pieces', '12 pieces'],
            ['chocolate-cake', 'Chocolate Fudge Cake', 'CAKES', 'Chocolate-Cake.jpg', 1150, 'Moist cocoa sponge covered with smooth chocolate frosting for a crowd-pleasing treat.', 'Moist chocolate layers wrapped in silky chocolate frosting.', '0.5 kg', '1 kg', '2 kg'],
            ['chocolate-cheesecake', 'Chocolate Cheesecake', 'CAKES', 'chocolate-cheese-cake.jpg', 1550, 'Rich chocolate cheesecake over a crunchy base, finished with a glossy chocolate layer.', 'A rich chocolate twist on creamy cheesecake.', '6 inch', '8 inch', '10 inch'],
            ['dark-chocolate-cake', 'Dark Chocolate Cake', 'CAKES', 'dark-chocolate-cake.jpg', 1290, 'Dark cocoa sponge and smooth ganache make this an indulgent choice for chocolate lovers.', 'Bold dark chocolate sponge finished with silky ganache.', '0.5 kg', '1 kg', '2 kg'],
            ['classic-donut-box', 'Classic Donut Box', 'DESSERTS', 'dounuts.jpg', 360, 'Soft, golden donuts with a light glaze, freshly prepared for sharing.', 'A fresh box of soft, golden, glazed donuts.', '4 pieces', '6 pieces', '12 pieces'],
            ['gluten-free-donuts', 'Gluten-Free Donut Box', 'DESSERTS', 'Gluten-Free-Donut.png', 480, 'A tender gluten-free donut treat, prepared separately and packed fresh.', 'A soft, sweet gluten-free option for your treat time.', '4 pieces', '6 pieces', '12 pieces'],
            ['vanilla-cake', 'Vanilla Celebration Cake', 'CAKES', 'vanilla-cake.jpg', 990, 'Soft vanilla sponge with a light cream finish, ready for birthdays and everyday celebrations.', 'A light, tender vanilla cake made for sharing.', '0.5 kg', '1 kg', '2 kg'],
        ];

        foreach ($catalog as $index => [$slug, $name, $category, $file, $price, $description, $short, $small, $medium, $large]) {
            $imagePath = '/images/products/'.$file;
            $product = Product::firstOrCreate(['slug' => $slug], [
                'name' => $name,
                'sku' => strtoupper(str_replace('-', '', $slug)),
                'category' => $category,
                'description' => $description.' Made fresh to order and delivered across Bangladesh. Choose a size or box and place your order here.',
                'short_description' => $short,
                'regular_price' => $price + 200,
                'discount_price' => $price,
                'stock_quantity' => 36,
                'status' => 'active',
                'featured' => $index < 4,
                'main_image' => $imagePath,
                'material' => $category === 'CAKES' ? 'Freshly baked cake' : 'Freshly baked dessert',
                'quality_note' => 'Prepared fresh and packed with care.',
                'features' => ['Freshly prepared', 'Carefully packed', 'Cash on delivery available'],
            ]);

            $product->images()->updateOrCreate(['image_path' => $imagePath], [
                'alt_text' => $name,
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            $sizes = [$small, $medium, $large];
            foreach ($sizes as $order => $size) {
                $adjustment = [0, 700, 1700][$order];
                $product->variants()->firstOrCreate(['name' => 'Size', 'value' => $size], [
                    'sku' => strtoupper(str_replace('-', '', $slug)).'-'.str_replace(' ', '', strtoupper($size)),
                    'stock_quantity' => 12,
                    'price_adjustment' => $adjustment,
                    'is_active' => true,
                    'sort_order' => $order,
                ]);
            }
        }
    }
}
