<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
            DistrictSeeder::class,
            ShippingOptionSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
