<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
       Item::insert([
            [
                'name' => 'Pepperoni Pizza',
                'description' => 'Spicy pepperoni pizza',
                'price' => 12.99,
                'category_id' =>1,
                'is_active' => true,
            ],
            [
                'name' => 'Cheese Burger',
                'description' => 'Cheesy and juicy burger',
                'price' => 8.99,
                'category_id' => 2,
                'is_active' => true,
            ],
        ]);
    }
}
