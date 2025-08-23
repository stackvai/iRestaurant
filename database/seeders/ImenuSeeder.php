<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus\Imenus;
use App\Models\Branch;

class ImenuSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one branch exists
        $branch = Branch::firstOrCreate(['name' => 'Dhaka']);

        Imenus::create([
            'name' => 'Main Menu',
            'description' => 'Primary food menu',
            'image' => null, // or base64 string if needed
            'is_active' => true,
            'branch_id' => $branch->id, // ✅ connect to branch
            'availability' => [
                'Monday' => [
                    ['from' => '09:00', 'to' => '17:00'],
                ],
                'Tuesday' => [
                    ['from' => '09:00', 'to' => '17:00'],
                ],
                'Wednesday' => [
                    ['from' => '09:00', 'to' => '17:00'],
                ],
                'Thursday' => [
                    ['from' => '09:00', 'to' => '17:00'],
                ],
                'Friday' => [
                    ['from' => '09:00', 'to' => '17:00'],
                ],
                'Saturday' => [],
                'Sunday' => [],
            ],
        ]);
    }
}
