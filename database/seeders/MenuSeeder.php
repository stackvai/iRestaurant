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
            ['id' => 2, 'parent_id' => null, 'name' => 'Staff', 'href' => '/staffs', 'icon' => 'fa-solid:users', 'sequance' => 2, 'status' => 1],
            ['id' => 3, 'parent_id' => null, 'name' => 'Customer', 'href' => '/customers', 'icon' => 'raphael:users', 'sequance' => 3, 'status' => 1],
            ['id' => 4, 'parent_id' => null, 'name' => 'Menus', 'href' => '#', 'icon' => 'bx:food-menu', 'sequance' => 4, 'status' => 1],
            ['id' => 5, 'parent_id' => null, 'name' => 'Stock Manager', 'href' => '/stocks', 'icon' => 'material-symbols:delivery-truck-speed', 'sequance' => 5, 'status' => 1],
            ['id' => 6, 'parent_id' => null, 'name' => 'Branch', 'href' => '/branches', 'icon' => 'ph:warehouse-fill', 'sequance' => 6, 'status' => 1],
            ['id' => 7, 'parent_id' => null, 'name' => 'Reviews', 'href' => '/reviews', 'icon' => 'game-icons:warehouse', 'sequance' => 7, 'status' => 1],
            ['id' => 8, 'parent_id' => null, 'name' => 'Sales', 'href' => '/sales', 'icon' => 'fluent:tag-multiple-16-filled', 'sequance' => 8, 'status' => 1],
            ['id' => 9, 'parent_id' => null, 'name' => 'Marketing', 'href' => '/marketing', 'icon' => 'ri:database-fill', 'sequance' => 9, 'status' => 1],
            ['id' => 10, 'parent_id' => null, 'name' => 'Pos', 'href' => '/pos', 'icon' => 'jam:computer-f', 'sequance' => 10, 'status' => 1],
            ['id' => 11, 'parent_id' => null, 'name' => 'Order Manage', 'href' => '/order', 'icon' => 'streamline-ultimate:shopping-cart-full-bold', 'sequance' => 11, 'status' => 1],
            ['id' => 12, 'parent_id' => null, 'name' => 'Page', 'href' => '/page', 'icon' => 'ic:baseline-library-books', 'sequance' => 12, 'status' => 1],
            ['id' => 13, 'parent_id' => null, 'name' => 'Roles Permission', 'href' => '/permission', 'icon' => 'mdi:shield-key', 'sequance' => 13, 'status' => 1],
            ['id' => 14, 'parent_id' => null, 'name' => 'Delivery', 'href' => '/delivery', 'icon' => 'mdi:courier-fast', 'sequance' => 14, 'status' => 1],
            ['id' => 15, 'parent_id' => null, 'name' => 'Settings', 'href' => '/settings', 'icon' => 'mdi:courier-fast', 'sequance' => 15, 'status' => 1],
            ['id' => 16, 'parent_id' => 11, 'name' => 'New Orders', 'href' => '/orders', 'icon' => 'streamline-ultimate:shopping-cart-full-bold', 'sequance' => 16, 'status' => 1],
            ['id' => 17, 'parent_id' => 11, 'name' => 'Confirm Orders', 'href' => '/orders/confirm', 'icon' => 'streamline:shopping-cart-check-solid', 'sequance' => 17, 'status' => 1],
            ['id' => 18, 'parent_id' => 11, 'name' => 'Shipped Order', 'href' => '/orders/shipped', 'icon' => 'fluent:vehicle-truck-checkmark-48-filled', 'sequance' => 18, 'status' => 1],
            ['id' => 19, 'parent_id' => 11, 'name' => 'Cancel Order', 'href' => '/orders/cancel', 'icon' => 'bi:cart-x-fill', 'sequance' => 19, 'status' => 1],
            ['id' => 20, 'parent_id' => 11, 'name' => 'Delivered Order', 'href' => '/orders/delivery', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 20, 'status' => 1],
            ['id' => 21, 'parent_id' => 4, 'name' => 'Menu Manager', 'href' => '/imenus', 'icon' => 'fluent:food-32-regular', 'sequance' => 21, 'status' => 1],
            ['id' => 22, 'parent_id' => 4, 'name' => 'Make Options', 'href' => '/options', 'icon' => 'ion:fast-food-outline', 'sequance' => 22, 'status' => 1],
            ['id' => 23, 'parent_id' => 9, 'name' => 'Coupon Offers', 'href' => '/marketing/coupon', 'icon' => 'hugeicons:coupon-02', 'sequance' => 23, 'status' => 1],
            ['id' => 24, 'parent_id' => 9, 'name' => 'Popup Offers', 'href' => '/marketing/popup', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 23, 'status' => 1],
            ['id' => 25, 'parent_id' => 15, 'name' => 'Features', 'href' => '/settings/features', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 23, 'status' => 1],
            ['id' => 26, 'parent_id' => 15, 'name' => 'Payments', 'href' => '/settings/payments', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 23, 'status' => 1],
            ['id' => 27, 'parent_id' => 15, 'name' => 'E-mail', 'href' => '/settings/email', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 23, 'status' => 1],
            ['id' => 28, 'parent_id' => 15, 'name' => 'Contacts', 'href' => '/settings/Contacts', 'icon' => 'mdi:cart-arrow-right', 'sequance' => 23, 'status' => 1],
            ['id' => 29, 'parent_id' => 9, 'name' => 'Tracking Pixels', 'href' => '/marketing/pixels', 'icon' => 'mdi:pixel', 'sequance' => 24, 'status' => 1],
        ];

        foreach ($menus as &$menu) {
            $menu['created_at'] = $now;
            $menu['updated_at'] = $now;
        }

        DB::table('menus')->insert($menus);
    }
}
