<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all roles
        $roles = Role::all();

        foreach ($roles as $role) {
            if ($role->name === 'admin') {
                User::create([
                    'name'     => 'Admin User',
                    'email'    => 'test@example.com',
                    'password' => Hash::make('password'),
                    'role_id'  => $role->id,
                    'status'   => 1,
                ]);
            } else {
                User::create([
                    'name'     => ucfirst($role->name) . ' User',
                    'email'    => $role->name . '@example.com',
                    'password' => Hash::make('password'),
                    'role_id'  => $role->id,
                    'status'   => 1,
                ]);
            }
        }
    }
}
