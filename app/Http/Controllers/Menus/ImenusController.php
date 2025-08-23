<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Menus\Imenus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImenusController extends Controller
{
    private $menuId = 4;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $imenus = Imenus::all();
        return Inertia::render('imenus/index', [
            'imenus' => $imenus,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::select('id', 'name')->get();

        return Inertia::render('imenus/create', [
            'branches' => $branches,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'required|boolean',
            'branch_id' => 'required|exists:branches,id',
            'availability' => 'nullable|array',
        ]);

        Imenus::create($validated);

        return redirect()->route('imenus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu = Imenus::with('categories.items')->findOrFail($id);

        return Inertia::render('imenus/show', [
            'menu' => $menu
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Imenus::findOrFail($id);
        $branches = Branch::select('id', 'name')->get();

        return Inertia::render('imenus/edit', [
            'menu' => $menu,
            'branches' => $branches,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menu = Imenus::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $menu->update($validated);

        return redirect()->route('imenus.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Imenus::findOrFail($id)->delete();

        return redirect()->route('imenus.index');
    }
}
