<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Item;
use App\Models\Menus\Option;
use Illuminate\Support\Facades\DB;

class ItemOptionSeeder extends Seeder
{
    public function run(): void
    {
        $pepperoni = Item::where('name', 'Pepperoni Pizza')->first();
        $cheeseBurger = Item::where('name', 'Cheese Burger')->first();

        $size = Option::where('name', 'Size')->first();
        $toppings = Option::where('name', 'Toppings')->first();

        DB::table('item_option')->insert([
            [
                'item_id' => $pepperoni->id,
                'option_id' => $size->id,
                'is_required' => true,
                'min_selection' => 1,
                'max_selection' => 1,
                'multiple_selection' => false,
            ],
            [
                'item_id' => $pepperoni->id,
                'option_id' => $toppings->id,
                'is_required' => false,
                'min_selection' => 0,
                'max_selection' => 3,
                'multiple_selection' => true,
            ],
            [
                'item_id' => $cheeseBurger->id,
                'option_id' => $toppings->id,
                'is_required' => false,
                'min_selection' => 0,
                'max_selection' => 2,
                'multiple_selection' => true,
            ],
        ]);
    }
}
