<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Helpers\AccessHelper;
use Illuminate\Support\Facades\Response;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        if (!AccessHelper::hasAccess('branches', 'view')) {
            abort(403, 'Unauthorized');
        }

        $search = $request->input('search');
        $perPage  = $request->input('per_page', 10);

        $branches = Branch::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('branches/index', [
            'branches' => $branches,
            'filters' => ['search' => $search],
        ]);
    }

    public function export()
    {
        if (!AccessHelper::hasAccess('branches', 'export')) {
            abort(403, 'Unauthorized');
        }

        $branches = Branch::all();

        $csvHeader = ['ID', 'Name', 'Location', 'Phone', 'Description', 'Status', 'Created At'];
        $callback = function () use ($branches, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);

            foreach ($branches as $branch) {
                fputcsv($file, [
                    $branch->id,
                    $branch->name,
                    $branch->location,
                    $branch->phone,
                    $branch->description,
                    $branch->status ? 'Active' : 'Inactive',
                    $branch->created_at,
                ]);
            }

            fclose($file);
        };

        $fileName = 'branches_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function print()
    {
        if (!AccessHelper::hasAccess('branches', 'print')) {
            abort(403, 'Unauthorized');
        }

        $branches = Branch::all(); // get all branches

        return Inertia::render('branches/print', [
            'branches' => $branches
        ]);
    }


    public function create()
    {
        if (!AccessHelper::hasAccess('branches', 'create')) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('branches/create');
    }

    public function show(Branch $branch)
    {
        // You can leave empty if you don't need this route
    }

    public function store(Request $request)
    {
        if (!AccessHelper::hasAccess('branches', 'create')) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:20',
            'image' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Branch::create($data);
        return redirect()->route('branches.index')->with('success', 'Branch created.');
    }

    public function edit(Branch $branch)
    {
        if (!AccessHelper::hasAccess('branches', 'edit')) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('branches/edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        if (!AccessHelper::hasAccess('branches', 'edit')) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:20',
            'image' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $branch->update($data);
        return redirect()->route('branches.index')->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        if (!AccessHelper::hasAccess('branches', 'delete')) {
            abort(403, 'Unauthorized');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }
}
