<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['user', 'events'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Organizations/Index', [
            'organizations' => $organizations
        ]);
    }

    public function create()
    {
        // Get all users that can be associated with organizations
        $users = \App\Models\User::where('id', '!=', auth()->id()) // Exclude admin
            ->select('id', 'name', 'email')
            ->get();

        return Inertia::render('Admin/Organizations/Create', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'website' => 'nullable|url',
            'type' => 'required|string|in:business,non-profit,educational,sports',
            'status' => 'required|in:active,inactive,pending'
        ]);

        try {
            // Create the organization with the selected user
            $organization = Organization::create($validated);

            return redirect()->route('admin.organizations.index')
                ->with('success', 'Organization created successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to create organization: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Failed to create organization. Please try again.'
            ])->withInput();
        }
    }

    public function edit(Organization $organization)
    {
        return Inertia::render('Admin/Organizations/Edit', [
            'organization' => $organization->load(['user', 'events'])
        ]);
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'required|string',
            'website' => 'nullable|url',
            'type' => 'required|string',
            'status' => 'required|in:active,inactive,pending'
        ]);

        $organization->update($validated);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }

    public function show(Organization $organization)
    {
        return Inertia::render('Admin/Organizations/Show', [
            'organization' => $organization->load(['user', 'events'])
        ]);
    }
} 