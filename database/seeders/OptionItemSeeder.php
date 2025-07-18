<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\OptionItem;

class OptionItemSeeder extends Seeder
{
    public function run(): void
    {
        OptionItem::create([
            'option_id' => 1,
            'name' => 'Tomato Sauce',
            'description' => 'Classic tomato flavor',
            'extra_price' => 0.00,
            'is_default' => true,
            'is_active' => true,
        ]);

        OptionItem::create([
            'option_id' => 1,
            'name' => 'Garlic Sauce',
            'description' => 'Creamy garlic flavor',
            'extra_price' => 0.50,
            'is_default' => false,
            'is_active' => true,
        ]);

        OptionItem::create([
            'option_id' => 2,
            'name' => 'Cheese',
            'description' => 'Extra mozzarella',
            'extra_price' => 1.00,
            'is_default' => false,
            'is_active' => true,
        ]);
    }
}
