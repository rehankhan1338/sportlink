<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Academy;
use App\Models\Affiliation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts
        $userCount = User::count();
        $eventCount = Event::count();
        $academyCount = Academy::count();
        $affiliationCount = Affiliation::count();

        // Get recent events
        $recentEvents = Event::with('creator')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->title,
                    'start_date' => $event->start_date,
                    'creator' => $event->creator ? $event->creator->name : 'Unknown',
                    'status' => $event->status,
                ];
            });

        // Get recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'users' => $userCount,
                'events' => $eventCount,
                'academies' => $academyCount,
                'affiliations' => $affiliationCount,
            ],
            'recentEvents' => $recentEvents,
            'recentUsers' => $recentUsers,
        ]);
    }
} 