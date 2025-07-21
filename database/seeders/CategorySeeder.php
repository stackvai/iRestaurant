<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'imenus_id' => 1,
            'name' => 'Starters',
            'description' => 'Begin your meal right',
            'image' => 'starters.jpg',
            'is_active' => true,
        ]);

        Category::create([
            'imenus_id' => 1,
            'name' => 'Main Course',
            'description' => 'Main dishes',
            'image' => 'main.jpg',
            'is_active' => true,
        ]);
    }
}
