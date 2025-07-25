<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Option;
use App\Models\Menus\OptionItem;

class OptionItemSeeder extends Seeder
{
    public function run(): void
    {
        $size = Option::where('name', 'Size')->first();
        $toppings = Option::where('name', 'Toppings')->first();

        OptionItem::insert([
            // Size Options
            ['option_id' => $size->id, 'name' => 'Small', 'extra_price' => 0.00, 'is_default' => true, 'is_active' => true, 'position' => 1],
            ['option_id' => $size->id, 'name' => 'Medium', 'extra_price' => 2.00, 'is_default' => false, 'is_active' => true, 'position' => 2],
            ['option_id' => $size->id, 'name' => 'Large', 'extra_price' => 4.00, 'is_default' => false, 'is_active' => true, 'position' => 3],

            // Toppings Options
            ['option_id' => $toppings->id, 'name' => 'Extra Cheese', 'extra_price' => 1.00, 'is_default' => false, 'is_active' => true, 'position' => 1],
            ['option_id' => $toppings->id, 'name' => 'Olives', 'extra_price' => 0.50, 'is_default' => false, 'is_active' => true, 'position' => 2],
            ['option_id' => $toppings->id, 'name' => 'Mushrooms', 'extra_price' => 0.75, 'is_default' => false, 'is_active' => true, 'position' => 3],
        ]);
    }
}
