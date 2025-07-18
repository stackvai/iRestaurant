<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        Item::create([
            'category_id' => 1,
            'name' => 'Garlic Bread',
            'description' => 'Toasted bread with garlic and herbs',
            'price' => 4.99,
            'image' => 'garlic_bread.jpg',
            'is_active' => true,
        ]);

        Item::create([
            'category_id' => 2,
            'name' => 'Grilled Chicken',
            'description' => 'Chicken with spices and herbs',
            'price' => 12.99,
            'image' => 'chicken.jpg',
            'is_active' => true,
        ]);
    }
}
