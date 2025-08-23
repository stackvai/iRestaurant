<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Helpers\AccessHelper;
use Inertia\Inertia;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $staff = User::with('role')
            ->whereNotIn('role_id', [1, 5]) // Exclude Admin (1) and Customer (5)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();


        return inertia('staffs/index', [
            'staff' => $staff,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function export()
    {
        if (!AccessHelper::hasAccess('staffs', 'export')) {
            abort(403, 'Unauthorized');
        }

        $staff = User::with('role')
            ->whereNotIn('role_id', [1, 5])
            ->get();

        $csvHeader = ['ID', 'Name', 'Email', 'Mobile', 'Location', 'Role', 'Status', 'Created At'];

        $callback = function () use ($staff, $csvHeader) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($handle, $csvHeader);

            foreach ($staff as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->mobile,
                    $user->location ?? '-',   // make sure this field exists in your users table
                    $user->role?->name,
                    $user->status ? 'Active' : 'Blocked',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        $fileName = 'staffs_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function print()
    {
        if (!AccessHelper::hasAccess('staffs', 'print')) {
            abort(403, 'Unauthorized');
        }

        $staffs = User::with('role')
            ->whereNotIn('role_id', [1, 5]) // same filter as index/export
            ->get();

        return Inertia::render('staffs/print', [
            'staffs' => $staffs
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
