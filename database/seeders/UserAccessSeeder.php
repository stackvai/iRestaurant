<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAccessSeeder extends Seeder
{
    public function run(): void
    {
        $allMenuIds = DB::table('menus')->pluck('id')->toArray();

        // Define role-specific menu access
        $managerMenuIds = [1, 2, 3, 4, 5, 6, 7]; // Example: menus allowed for managers
        $chefMenuIds = [2, 3, 8]; // Example: menus allowed for chefs
        $waiterMenuIds = DB::table('menus')->whereIn('slug', ['orders', 'tables'])->pluck('id')->toArray();

        // Admin - Full menu access
        $adminUsers = DB::table('users')->where('role_id', 1)->pluck('id');
        foreach ($adminUsers as $userId) {
            foreach ($allMenuIds as $menuId) {
                DB::table('access_to_user')->insert([
                    'user_id' => $userId,
                    'role_id' => 1,
                    'menu_id' => $menuId,
                    'granted' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Manager - Partial menu access
        $managerUsers = DB::table('users')->where('role_id', 2)->pluck('id');
        foreach ($managerUsers as $userId) {
            foreach ($managerMenuIds as $menuId) {
                DB::table('access_to_user')->insert([
                    'user_id' => $userId,
                    'role_id' => 2,
                    'menu_id' => $menuId,
                    'granted' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Chef - Even more limited
        $chefUsers = DB::table('users')->where('role_id', 3)->pluck('id');
        foreach ($chefUsers as $userId) {
            foreach ($chefMenuIds as $menuId) {
                DB::table('access_to_user')->insert([
                    'user_id' => $userId,
                    'role_id' => 3,
                    'menu_id' => $menuId,
                    'granted' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Waiter - Orders and Tables only
        $waiterUsers = DB::table('users')->where('role_id', 5)->pluck('id');
        foreach ($waiterUsers as $userId) {
            foreach ($waiterMenuIds as $menuId) {
                DB::table('access_to_user')->insert([
                    'user_id' => $userId,
                    'role_id' => 5,
                    'menu_id' => $menuId,
                    'granted' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Users (role_id = 4?) - No access, skip
    }
}
