<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AthleteController extends Controller
{
    public function index()
    {
        $athletes = Profile::with(['user', 'registeredEvents'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Athletes/Index', [
            'athletes' => $athletes
        ]);
    }

    public function show(Profile $athlete)
    {
        return Inertia::render('Admin/Athletes/Show', [
            'athlete' => $athlete->load(['user', 'registeredEvents'])
        ]);
    }

    public function update(Request $request, Profile $athlete)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended,inactive',
            'notes' => 'nullable|string'
        ]);

        $athlete->update($validated);

        return back()->with('success', 'Athlete status updated successfully.');
    }
} 