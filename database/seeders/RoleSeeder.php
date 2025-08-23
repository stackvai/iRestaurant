<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'admin', 'status' => 1],
            ['name' => 'manager', 'status' => 1],
            ['name' => 'waiter', 'status' => 1],
            ['name' => 'Chef', 'status' => 1],
            ['name' => 'user', 'status' => 1],
        ]);
    }
}
