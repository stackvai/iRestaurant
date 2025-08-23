<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Option;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        Option::insert([
            ['name' => 'Size', 'description' => 'Choose your size', 'is_active' => true],
            ['name' => 'Toppings', 'description' => 'Extra toppings', 'is_active' => true],
        ]);
    }
}

