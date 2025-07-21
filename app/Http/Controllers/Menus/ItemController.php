<?php

namespace App\Http\Controllers\Menus;

use App\Models\Menus\Category;
use App\Http\Controllers\Controller;
use App\Models\Menus\Imenus;
use App\Models\Menus\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Imenus $menu, Category $category, Request $request)
    {
        $duplicate = $request->query('duplicate');

        return Inertia::render('imenus/[id]/add-item', [
            'menu' => $menu,
            'category' => $category,
            'duplicate' => $duplicate ? json_decode($duplicate, true) : null,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Imenus $menu, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        $validated['category_id'] = $category->id;

        Item::create($validated);

        return redirect()->route('imenus.show', $menu->id)->with('success', 'Item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Imenus $menu, Category $category, Item $item)
    {
        return Inertia::render('imenus/[id]/edit-item', [
            'menu' => $menu,
            'category' => $category,
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imenus $menu, Category $category, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        $item->update($validated);

        return redirect()->route('imenus.show', $menu->id)->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imenus $menu, Category $category, Item $item)
    {
        $item->delete();
        return redirect()->route('imenus.show', $menu->id)->with('success', 'Item deleted successfully.');
    }
}
