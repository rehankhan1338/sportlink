<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class AffiliationController extends Controller
{
    public function __construct()
    {
        // Tell Laravel to use id for route model binding
        Route::bind('affiliation', function ($value) {
            return Affiliation::where('id', $value)->firstOrFail();
        });
    }

    public function index()
    {
        $affiliations = Affiliation::with('academies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Affiliations/Index', [
            'affiliations' => $affiliations
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Affiliations/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'website' => 'nullable|url',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'required|string',
            'location' => 'required|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,pending'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('affiliations/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('affiliations/covers', 'public');
        }

        Affiliation::create($validated);

        return redirect()->route('admin.affiliations.index')
            ->with('success', 'Affiliation created successfully.');
    }

    public function edit(Affiliation $affiliation)
    {
        return Inertia::render('Admin/Affiliations/Edit', [
            'affiliation' => $affiliation
        ]);
    }

    public function update(Request $request, Affiliation $affiliation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'website' => 'nullable|url',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'required|string',
            'location' => 'required|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,pending'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($affiliation->logo) {
                Storage::disk('public')->delete($affiliation->logo);
            }
            $validated['logo'] = $request->file('logo')->store('affiliations/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($affiliation->cover_image) {
                Storage::disk('public')->delete($affiliation->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('affiliations/covers', 'public');
        }

        $affiliation->update($validated);

        return redirect()->route('admin.affiliations.index')
            ->with('success', 'Affiliation updated successfully.');
    }

    public function destroy(Affiliation $affiliation)
    {
        // Delete associated images
        if ($affiliation->logo) {
            Storage::disk('public')->delete($affiliation->logo);
        }
        if ($affiliation->cover_image) {
            Storage::disk('public')->delete($affiliation->cover_image);
        }

        $affiliation->delete();

        return redirect()->route('admin.affiliations.index')
            ->with('success', 'Affiliation deleted successfully.');
    }

    public function show(Affiliation $affiliation)
    {
        return Inertia::render('Admin/Affiliations/Show', [
            'affiliation' => $affiliation->load(['academies'])
        ]);
    }
} 