<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function getMenusForUser()
    {
        $user = Auth::user();

        // Get menu IDs accessible by the user's role
        $menuIds = DB::table('access_to_role')
            ->where('role_id', $user->role_id)
            ->pluck('menu_id')
            ->unique()
            ->toArray();

        // Load parent menus with filtered children
        $menus = Menu::with(['children' => function ($query) use ($menuIds) {
            $query->whereIn('id', $menuIds)
                ->orderBy('sequance')
                ->with(['children' => function ($query) use ($menuIds) {
                    $query->whereIn('id', $menuIds)
                        ->orderBy('sequance');
                }]);
        }])
            ->whereIn('id', $menuIds)
            ->whereNull('parent_id')
            ->orderBy('sequance')
            ->get();

        return $menus;
    }
}
