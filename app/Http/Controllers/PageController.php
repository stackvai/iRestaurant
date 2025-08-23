<?php
namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function handle(Request $request, $slug)
    {
        // Look up the page (must exist and be active)
        $page = Page::where('slug', $slug)->where('status', true)->first();

        // If page not found, show 404
        if (!$page) {
            abort(404);
        }

        // If PUT request, update page
        if ($request->isMethod('put')) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
            ]);

            $page->update($validated);

            return back()->with('success', 'Page updated successfully.');
        }

        // Else render the page
        return Inertia::render('pages/index', [
            'page' => $page,
            'slug' => $slug,
        ]);
    }
}
