<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Option;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        Option::create([
            'name' => 'Choose Sauce',
            'description' => 'Select your preferred sauce',
            'is_active' => true,
        ]);

        Option::create([
            'name' => 'Add-ons',
            'description' => 'Extra toppings and sides',
            'is_active' => true,
        ]);
    }
}
