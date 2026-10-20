<?php

namespace Database\Seeders;

use App\Models\ShippingOption;
use Illuminate\Database\Seeder;

class ShippingOptionSeeder extends Seeder
{
    public function run(): void
    {
        ShippingOption::firstOrCreate(['key' => 'inside-dhaka'], ['name' => 'Inside Dhaka', 'charge' => 70, 'is_active' => true, 'sort_order' => 1]);
        ShippingOption::firstOrCreate(['key' => 'outside-dhaka'], ['name' => 'Outside Dhaka', 'charge' => 140, 'is_active' => true, 'sort_order' => 2]);
    }
}
