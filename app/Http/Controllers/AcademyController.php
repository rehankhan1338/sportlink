<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AcademyController extends Controller
{
    public function list()
    {
        try {
            $academies = Academy::select('id', 'name', 'logo')->get();
            \Log::info('Fetched academies:', [
                'count' => $academies->count(),
                'academies' => $academies->toArray()
            ]);
            return response()->json(['academies' => $academies]);
        } catch (\Exception $e) {
            \Log::error('Error fetching academies:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch academies'], 500);
        }
    }

    public function store(Request $request)
    {
        // Validate basic required fields
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'person_in_charge' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            // Optional fields
            'website' => 'nullable|url|max:255',
            'about' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'affiliation' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        try {
            // Handle logo upload if present
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = Str::random(20) . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('academies/logos', $logoName, 'public');
            }

            // Handle cover upload if present
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $cover = $request->file('cover_image');
                $coverName = Str::random(20) . '.' . $cover->getClientOriginalExtension();
                $coverPath = $cover->storeAs('academies/covers', $coverName, 'public');
            }

            // Create academy record with user_id
            $academy = Academy::create([
                'user_id' => Auth::id(), // Always get the authenticated user's ID
                'name' => $request->name,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'person_in_charge' => $request->person_in_charge,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'about' => $request->about,
                'logo' => $logoPath,
                'cover_image' => $coverPath,
                'affiliation' => $request->affiliation,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Academy registered successfully',
                'academy' => $academy
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create academy:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create academy. ' . $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to register an academy');
        }
        
        return Inertia::render('Academy/Register', [
            'event_id' => $request->get('event_id'),
            'auth' => [
                'user' => Auth::user()
            ]
        ]);
    }

    public function show($id)
    {
        try {
            $academy = Academy::findOrFail($id);
            \Log::info('Academy Data:', ['academy' => $academy->toArray()]);
            
            // Ensure we're passing all necessary fields
            $academyData = [
                'id' => $academy->id,
                'name' => $academy->name,
                'about' => $academy->about,
                'logo' => $academy->logo,
                'cover_image' => $academy->cover_image,
                'address' => $academy->address,
                'city' => $academy->city,
                'country' => $academy->country,
                'person_in_charge' => $academy->person_in_charge,
                'email' => $academy->email,
                'phone' => $academy->phone,
                'website' => $academy->website,
                'affiliation' => $academy->affiliation,
            ];

            return Inertia::render('Academy/Show', [
                'academy' => $academyData
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in AcademyController@show: ' . $e->getMessage());
            return response()->json(['error' => 'Academy not found'], 404);
        }
    }
} 