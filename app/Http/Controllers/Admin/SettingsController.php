<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => [
                'site_name' => config('app.name'),
                'timezone' => config('app.timezone'),
                'registration_enabled' => config('app.registration_enabled', true),
                'maintenance_mode' => app()->isDownForMaintenance(),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'timezone' => 'required|string|timezone',
            'registration_enabled' => 'required|boolean',
            'maintenance_mode' => 'required|boolean'
        ]);

        // Update settings in the .env file or database as needed
        // This is just a placeholder - implement actual settings storage as needed

        return back()->with('success', 'Settings updated successfully.');
    }
} 