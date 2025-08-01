<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use Inertia\Inertia;

class SidebarController extends Controller
{
    public function dashboard(MenuService $menuService)
    {
        return Inertia::render('Dashboard', [
            'menus' => $menuService->getMenusForUser(),
        ]);
    }
}
