<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Imenus;

class ImenuSeeder extends Seeder
{
    public function run(): void
    {
        Imenus::create([
            'name' => 'Main Menu',
            'description' => 'Primary food menu',
            'image' => 'menu.jpg',
            'is_active' => true,
        ]);
    }
}
