<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Dhaka',
                'location' => 'Gulshan, Dhaka',
                'phone' => '01712345678',
                'description' => 'Main branch in Dhaka',
                'status' => 1,
            ],
            [
                'name' => 'Jamalpur',
                'location' => 'Town Center, Jamalpur',
                'phone' => '01812345678',
                'description' => 'Branch in Jamalpur',
                'status' => 1,
            ],
            [
                'name' => 'Mymensingh',
                'location' => 'Shambhuganj, Mymensingh',
                'phone' => '01912345678',
                'description' => 'Branch in Mymensingh',
                'status' => 1,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
