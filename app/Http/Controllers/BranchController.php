<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::latest()->paginate(10);
        return Inertia::render('branches/index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('branches/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|boolean',
        ]);

        Branch::create($data);
        return redirect()->route('branches.index')->with('success', 'Branch created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return Inertia::render('branches/edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|boolean',
        ]);

        $branch->update($data);
        return redirect()->route('branches.index')->with('success', 'Branch updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }
}
