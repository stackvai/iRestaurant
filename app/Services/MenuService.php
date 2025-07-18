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

        // Get all menu IDs the user has access to (granted = true)
        $userMenuIds = DB::table('access_to_user')
            ->where('user_id', $user->id)
            ->where('granted', true)
            ->pluck('menu_id')
            ->unique()
            ->toArray();

        if (empty($userMenuIds)) {
            // Fallback to role access if no user-specific access found
            $userMenuIds = DB::table('access_to_role')
                ->where('role_id', $user->role_id)
                ->pluck('menu_id')
                ->unique()
                ->toArray();
        }

        // Now, get top-level menus where ID in userMenuIds and parent_id is null
        // And eager load children recursively, but only those in userMenuIds

        $menus = Menu::with(['children' => function ($query) use ($userMenuIds) {
            $query->whereIn('id', $userMenuIds)
                ->orderBy('sequance')
                ->with(['children' => function ($query) use ($userMenuIds) {
                    // Recursive children filtering (optional)
                    $query->whereIn('id', $userMenuIds)
                        ->orderBy('sequance');
                }]);
        }])
            ->whereIn('id', $userMenuIds)
            ->whereNull('parent_id')
            ->orderBy('sequance')
            ->get();

        return $menus;
    }
}
