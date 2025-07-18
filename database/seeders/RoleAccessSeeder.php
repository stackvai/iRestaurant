<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAccessSeeder extends Seeder
{
    public function run(): void
    {
        $menuIds = DB::table('menus')->pluck('id')->toArray();
        $actionIds = DB::table('actions')->pluck('id')->toArray();

        foreach ($menuIds as $menuId) {
            foreach ($actionIds as $actionId) {
                DB::table('access_to_role')->insert([
                    'role_id' => 1, // Admin role
                    'menu_id' => $menuId,
                    'action_id' => $actionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
