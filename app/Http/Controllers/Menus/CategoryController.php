<?php

namespace App\Http\Controllers\Menus;

use App\Models\Menus\Category;
use App\Http\Controllers\Controller;
use App\Models\Menus\Imenus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
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
    public function create(Imenus $menu)
    {
        return Inertia::render('imenus/[id]/add-category', [
            'menu' => $menu,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Imenus $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['imenus_id'] = $menu->id;

        Category::create($validated);

        return redirect()->route('imenus.show', $menu->id)->with('success', 'Category added');
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
    public function edit(Imenus $menu, Category $category)
    {
        return Inertia::render('imenus/[id]/edit-category', [
            'menu' => $menu,
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imenus $menu, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('imenus.show', $menu->id)->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imenus $menu, Category $category)
    {
         $category->delete();

    return redirect()->route('imenus.show', $menu->id)->with('success', 'Category deleted successfully.');
    }
}
