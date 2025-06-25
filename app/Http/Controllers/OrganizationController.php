<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Routing\Controller;

class OrganizationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'organization.name' => 'required|string|max:255',
                'organization.description' => 'nullable|string',
                'organization.email' => 'required|email|max:255',
                'organization.phone' => 'nullable|string|max:255',
                'organization.address' => 'nullable|string|max:255',
                'organization.city' => 'nullable|string|max:255',
                'organization.country' => 'nullable|string|max:255',
                'organization.website' => 'nullable|url|max:255',
                'organization.type' => 'required|string|in:business,non-profit,educational,sports',
                'organization.status' => 'required|string|in:active,inactive,pending'
            ]);

            // For user-side creation, always use the authenticated user
            $organizationData = $validated['organization'];
            $organizationData['user_id'] = auth()->id();
            
            $organization = Organization::create($organizationData);

            return response()->json([
                'success' => true,
                'message' => 'Organization created successfully.',
                'organization' => $organization
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Failed to create organization: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create organization: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'organization.name' => 'required|string|max:255',
                'organization.description' => 'nullable|string',
                'organization.email' => 'required|email|max:255',
                'organization.phone' => 'nullable|string|max:255',
                'organization.address' => 'nullable|string|max:255',
                'organization.city' => 'nullable|string|max:255',
                'organization.country' => 'nullable|string|max:255',
                'organization.website' => 'nullable|url|max:255',
                'organization.type' => 'required|string|in:business,non-profit,educational,sports',
                'organization.status' => 'required|string|in:active,inactive,pending'
            ]);

            $userId = auth()->id();
            $organization = Organization::where('user_id', $userId)->first();

            if (!$organization) {
                return response()->json([
                    'message' => 'Organization not found',
                ], 404);
            }

            $organization->update($data['organization']);

            return response()->json(['organization' => $organization], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
