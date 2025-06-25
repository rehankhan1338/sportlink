<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TournamentDivision;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = TournamentDivision::with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Divisions/Index', [
            'divisions' => $divisions
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Divisions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'min_age' => 'required|integer|min:0',
            'max_age' => 'required|integer|gt:min_age',
            'min_weight' => 'required|numeric|min:0',
            'max_weight' => 'required|numeric|gt:min_weight',
            'belt_level' => 'required|string',
            'gender' => 'required|in:male,female,mixed'
        ]);

        TournamentDivision::create($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully.');
    }

    public function edit(TournamentDivision $division)
    {
        return Inertia::render('Admin/Divisions/Edit', [
            'division' => $division->load('event')
        ]);
    }

    public function update(Request $request, TournamentDivision $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'min_age' => 'required|integer|min:0',
            'max_age' => 'required|integer|gt:min_age',
            'min_weight' => 'required|numeric|min:0',
            'max_weight' => 'required|numeric|gt:min_weight',
            'belt_level' => 'required|string',
            'gender' => 'required|in:male,female,mixed'
        ]);

        $division->update($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    public function destroy(TournamentDivision $division)
    {
        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }
} 