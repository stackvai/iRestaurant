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
        $duplicate = null;
        if ($request->has('duplicate')) {
            $item = Item::findOrFail($request->query('duplicate'));

            // only pick the fields you want to duplicate
            $duplicate = $item->only([
                'name',
                'description',
                'image',
                'price',
                'offer_price',
                'tax',
                'is_active'
            ]);
        }

        return Inertia::render('imenus/[id]/add-item', [
            'menu' => $menu,
            'category' => $category,
            'duplicate' => $duplicate,
        ]);
    }


    /**
     * Update the order of items and return update menu with inertia
     */
    public function reorderItems(Request $request, Imenus $menu, Category $category)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.serial_no' => 'required|integer',
        ]);

        foreach ($request->items as $itemData) {
            Item::where('id', $itemData['id'])->update(['serial_no' => $itemData['serial_no']]);
        }

        // Redirect with a flash message
        return redirect()->route('imenus.show', $menu->id)
            ->with('success', 'Items reordered successfully');
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
            'offer_price' => 'required|numeric',
            'tax' => 'required|numeric',
            'serial_no' => 'nullable|numeric', // can be optional, we’ll assign automatically
            'is_active' => 'boolean',
        ]);

        $validated['category_id'] = $category->id;

        // ✅ Auto assign serial_no if not provided
        if (empty($validated['serial_no'])) {
            $lastSerial = \App\Models\Menus\Item::where('category_id', $category->id)->max('serial_no');
            $validated['serial_no'] = $lastSerial ? $lastSerial + 1 : 1;
        }

        Item::create($validated);

        return redirect()
            ->route('imenus.show', $menu->id)
            ->with('success', 'Item added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Imenus $menu)
    {
        $menu->load(['categories.items' => function ($query) {
            $query->orderBy('serial_no', 'asc');
        }]);

        return Inertia::render('imenus/[id]/show', [
            'menu' => $menu,
        ]);
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
