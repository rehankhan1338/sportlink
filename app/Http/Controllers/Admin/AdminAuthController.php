<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Event;
use App\Models\TournamentDivision;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except(['destroy', 'dashboard']);
    }

    public function create()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            $intended = session()->pull('admin.intended', route('admin.dashboard'));
            return redirect()->intended($intended);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $stats = [
            'events' => Event::where('status', 'active')->count(),
            'divisions' => Schema::hasTable('tournament_divisions') ? TournamentDivision::count() : 0,
            'athletes' => Profile::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }
} 