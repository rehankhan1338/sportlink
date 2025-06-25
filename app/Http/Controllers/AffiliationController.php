<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AffiliationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'location' => 'required|string',
            'email' => 'required|email|unique:affiliations',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = Str::random(20) . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('affiliations/logos', $logoName, 'public');
        }

        // Handle cover upload
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $cover = $request->file('cover_image');
            $coverName = Str::random(20) . '.' . $cover->getClientOriginalExtension();
            $coverPath = $cover->storeAs('affiliations/covers', $coverName, 'public');
        }

        // Create affiliation record
        $affiliation = Affiliation::create([
            'name' => $validated['name'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'location' => $validated['location'],
            'email' => $validated['email'],
            'logo' => $logoPath,
            'cover_image' => $coverPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Affiliation registered successfully',
            'affiliation' => $affiliation
        ]);
    }

    public function list()
    {
        $affiliations = Affiliation::all();
        return response()->json([
            'affiliations' => $affiliations
        ]);
    }
} 