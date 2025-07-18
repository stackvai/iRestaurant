<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemOptionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('item_option')->insert([
            [
                'item_id' => 1, // Garlic Bread
                'option_id' => 1, // Choose Sauce
                'is_required' => true,
                'max_selection' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_id' => 2, // Grilled Chicken
                'option_id' => 2, // Add-ons
                'is_required' => false,
                'max_selection' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
