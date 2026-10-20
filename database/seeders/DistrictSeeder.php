<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districtAreas = [
            'Dhaka' => ['Dhanmondi', 'Mirpur', 'Uttara', 'Mohammadpur', 'Motijheel'],
            'Chattogram' => ['Agrabad', 'GEC Circle', 'Panchlaish', 'Halishahar'],
            'Sylhet' => ['Zindabazar', 'Amberkhana', 'Shibganj'],
            'Rajshahi' => ['Shaheb Bazar', 'Boalia', 'Motihar'],
            'Khulna' => ['Sonadanga', 'Khalishpur', 'Daulatpur'],
            'Barishal' => ['Sadar Road', 'Nathullabad', 'Rupatali'],
            'Rangpur' => ['Jahaj Company More', 'Modern More', 'Pairaband'],
            'Cumilla' => ['Kandirpar', 'Jhautola', 'Shashongachha'],
        ];

        foreach ($districtAreas as $districtName => $areaNames) {
            $district = District::query()->updateOrCreate(
                ['slug' => Str::slug($districtName)],
                ['name' => $districtName, 'is_active' => true, 'sort_order' => array_search($districtName, array_keys($districtAreas), true) + 1],
            );

            foreach ($areaNames as $index => $areaName) {
                $district->areas()->updateOrCreate(
                    ['slug' => Str::slug($areaName)],
                    ['name' => $areaName, 'is_active' => true, 'sort_order' => $index + 1],
                );
            }
        }
    }
}
