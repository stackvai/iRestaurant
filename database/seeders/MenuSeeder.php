<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();

        $menus = [
            ['id' => 1, 'parent_id' => null, 'name' => 'Dashboard', 'href' => '/dashboard', 'icon' => 'mdi:home', 'sequance' => 1, 'status' => 1],
            ['id' => 2, 'parent_id' => null, 'name' => 'Staff', 'href' => '/staff', 'icon' => 'fa-solid:users', 'sequance' => 2, 'status' => 1],
            ['id' => 3, 'parent_id' => null, 'name' => 'Customer', 'href' => '/customer', 'icon' => 'raphael:users', 'sequance' => 3, 'status' => 1],
            ['id' => 4, 'parent_id' => null, 'name' => 'Products', 'href' => '/products', 'icon' => 'lucide:boxes', 'sequance' => 4, 'status' => 1],
            ['id' => 5, 'parent_id' => null, 'name' => 'Stock Transfer', 'href' => '/stock_transfer', 'icon' => 'material-symbols:delivery-truck-speed', 'sequance' => 5, 'status' => 1],
            ['id' => 6, 'parent_id' => null, 'name' => 'Branch', 'href' => '/branch', 'icon' => 'ph:warehouse-fill', 'sequance' => 6, 'status' => 1],
            ['id' => 7, 'parent_id' => null, 'name' => 'Inventory', 'href' => '/inventory', 'icon' => 'game-icons:warehouse', 'sequance' => 7, 'status' => 1],
            ['id' => 8, 'parent_id' => null, 'name' => 'Brand', 'href' => '/brand', 'icon' => 'fluent:tag-multiple-16-filled', 'sequance' => 8, 'status' => 1],
            ['id' => 9, 'parent_id' => null, 'name' => 'Category', 'href' => '/category', 'icon' => 'ri:database-fill', 'sequance' => 9, 'status' => 1],
            ['id' => 10, 'parent_id' => null, 'name' => 'Pos', 'href' => '/pos', 'icon' => 'jam:computer-f', 'sequance' => 1.1, 'status' => 1],
            ['id' => 11, 'parent_id' => null, 'name' => 'Order Manage', 'href' => '/order', 'icon' => 'streamline-ultimate:shopping-cart-full-bold', 'sequance' => 11, 'status' => 1],
            ['id' => 12, 'parent_id' => null, 'name' => 'Page', 'href' => '/page', 'icon' => 'ic:baseline-library-books', 'sequance' => 12, 'status' => 1],
            ['id' => 13, 'parent_id' => null, 'name' => 'Permission', 'href' => '/permission', 'icon' => 'mdi:shield-key', 'sequance' => 13, 'status' => 1],
            ['id' => 14, 'parent_id' => null, 'name' => 'Courier Charge', 'href' => '/courier', 'icon' => 'mdi:courier-fast', 'sequance' => 14, 'status' => 1],
            ['id' => 15, 'parent_id' => 13, 'name' => 'Role', 'href' => '/role', 'icon' => 'fa6-solid:users-gear', 'sequance' => 14, 'status' => 1],
            ['id' => 16, 'parent_id' => 13, 'name' => 'User Access', 'href' => '/user_access', 'icon' => 'tdesign:user-unlocked-filled', 'sequance' => 16, 'status' => 1],
            ['id' => 17, 'parent_id' => 13, 'name' => 'Role Access', 'href' => '/role_access', 'icon' => 'fa6-solid:users-gear', 'sequance' => 17, 'status' => 1],
            ['id' => 18, 'parent_id' => 11, 'name' => 'New Orders', 'href' => '/orders', 'icon' => 'streamline-ultimate:shopping-cart-full-bold', 'sequance' => 18, 'status' => 1],
            ['id' => 19, 'parent_id' => 11, 'name' => 'Confirm Orders', 'href' => '/orders/confirm', 'icon' => 'streamline:shopping-cart-check-solid', 'sequance' => 19, 'status' => 1],
            ['id' => 20, 'parent_id' => 11, 'name' => 'Shipped Order', 'href' => '/orders/shipped', 'icon' => 'fluent:vehicle-truck-checkmark-48-filled', 'sequance' => 20, 'status' => 1],
            ['id' => 21, 'parent_id' => 11, 'name' => 'Cancel Order', 'href' => '/orders/cancel', 'icon' => 'bi:cart-x-fill', 'sequance' => 21, 'status' => 1],
            ['id' => 22, 'parent_id' => 11, 'name' => 'Delivered Order', 'href' => '/orders/delivery', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 22, 'status' => 1],
        ];

        foreach ($menus as &$menu) {
            $menu['created_at'] = $now;
            $menu['updated_at'] = $now;
        }

        DB::table('menus')->insert($menus);
    }
}
