<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //Users related Seeders
            RoleSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            ActionSeeder::class,
            RoleAccessSeeder::class,
            UserAccessSeeder::class,
            //Menu related Seeders
            iMenuSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            OptionSeeder::class,
            OptionItemSeeder::class,
            ItemOptionSeeder::class,
        ]);
    }
}
