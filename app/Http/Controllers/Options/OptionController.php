<?php

namespace App\Http\Controllers\Options;

use App\Http\Controllers\Controller;
use App\Models\Menus\Category;
use App\Models\Menus\Item;
use App\Models\Menus\Option;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('options/index', [
            'options' => Option::with(['optionItems', 'items'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('options/create', [
            'categories' => Category::select('id', 'name')
                ->with(['items:id,name,category_id'])
                ->get(),
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
            'is_active' => 'boolean',

            'option_items' => 'required|array|min:1',
            'option_items.*.name' => 'required|string|max:255',
            'option_items.*.description' => 'nullable|string',
            'option_items.*.extra_price' => 'nullable|numeric',
            'option_items.*.tax' => 'nullable|numeric',
            'option_items.*.image' => 'nullable|string', // base64 directly stored
            'option_items.*.is_default' => 'boolean',
            'option_items.*.position' => 'required|integer|min:1',

            'items' => 'array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.is_required' => 'boolean',
            'items.*.min_selection' => 'integer|min:0',
            'items.*.max_selection' => 'integer|min:1',
            'items.*.multiple_selection' => 'boolean',
        ]);

        // Create Option
        $option = Option::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Create Option Items (store base64 directly)
        foreach ($validated['option_items'] as $item) {
            $option->optionItems()->create([
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'extra_price' => $item['extra_price'] ?? 0.00,
                'tax' => $item['tax'] ?? 0.00,
                'image' => $item['image'] ?? null, // base64 stored directly
                'is_default' => $item['is_default'] ?? false,
                'position' => $item['position'],
            ]);
        }

        // Attach Option to Items with pivot data
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $itemOption) {
                $option->items()->attach($itemOption['item_id'], [
                    'is_required' => $itemOption['is_required'] ?? false,
                    'min_selection' => $itemOption['min_selection'] ?? 0,
                    'max_selection' => $itemOption['max_selection'] ?? 1,
                    'multiple_selection' => $itemOption['multiple_selection'] ?? false,
                ]);
            }
        }

        return redirect()
            ->route('options.index')
            ->with('success', 'Option and items created successfully.');
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
    public function edit(Option $option)
    {
        $option->load([
            'optionItems' => fn($q) => $q->orderBy('position'),
            'items' => fn($q) => $q->withPivot([
                'is_required',
                'min_selection',
                'max_selection',
                'multiple_selection'
            ])
        ]);

        return Inertia::render('options/[id]/edit', [
            'option' => $option,
            'items' => Item::select('id', 'name')->get(),
            'categories' => Category::with('items:id,category_id,name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Option $option)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',

            'option_items' => 'array',
            'option_items.*.id' => 'nullable|exists:option_items,id',
            'option_items.*.name' => 'required|string|max:255',
            'option_items.*.description' => 'nullable|string',
            'option_items.*.extra_price' => 'nullable|numeric',
            'option_items.*.tax' => 'nullable|numeric',
            'option_items.*.image' => 'nullable|string', // base64 directly stored
            'option_items.*.is_default' => 'boolean',
            'option_items.*.position' => 'required|integer|min:1',

            'items' => 'array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.is_required' => 'boolean',
            'items.*.min_selection' => 'integer',
            'items.*.max_selection' => 'integer',
            'items.*.multiple_selection' => 'boolean',
        ]);

        // Update option
        $option->update($validated);

        // Sync items
        $syncData = collect($request->input('items', []))->mapWithKeys(function ($item) {
            return [
                $item['item_id'] => [
                    'is_required' => $item['is_required'] ?? false,
                    'min_selection' => $item['min_selection'] ?? 0,
                    'max_selection' => $item['max_selection'] ?? 1,
                    'multiple_selection' => $item['multiple_selection'] ?? false,
                ],
            ];
        });

        $option->items()->sync($syncData);

        // Update option_items
        $option->optionItems()->delete(); // Or diff logic
        $option->optionItems()->createMany($validated['option_items'] ?? []);

        return redirect()->route('options.index')->with('success', 'Option updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Option $option)
    {
        // Delete related option_items
        $option->optionItems()->delete();

        // Detach related items from pivot table
        $option->items()->detach();

        // Delete the option itself
        $option->delete();

        return redirect()->route('options.index')->with('success', 'Option deleted successfully.');
    }
}
