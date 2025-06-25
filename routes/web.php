<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Models\Event;
use App\Models\Profile;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\OrganizationStripeController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AffiliationController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BracketsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Foundation\Application;

use Inertia\Inertia;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMatchController;
use Illuminate\Http\Request;

// Homepage
Route::get('/', function () {
    return Inertia::render('Home/Index');
})->name('home');

Route::get('/storage/events/{filename}', function ($filename) {
    $path = storage_path('events/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
})->where('filename', '.*');

Route::get('/storage/academies/{type}/{filename}', function ($type, $filename) {
    $path = storage_path('academies/' . $type . '/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
})->where(['type' => 'logos|covers', 'filename' => '.*']);

Route::get('/storage/affiliation/{type}/{filename}', function ($type, $filename) {
    $path = storage_path('affiliation/' . $type . '/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
})->where(['type' => 'logos|covers', 'filename' => '.*']);


// features
Route::prefix('features')->group(function () {
    Route::get('/pricing', function () {
        return Inertia::render('FeaturesPricing/Index');
    })->name('features.pricing');

    Route::get('/scoreboard', function () {
        return Inertia::render('FeaturesScoreboard/Index');
    })->name('features.scoreboard');

    Route::get('/streaming', function () {
        return Inertia::render('FeaturesStreaming/Index');
    })->name('features.streaming');

    Route::get('/federation-platform', function () {
        return Inertia::render('FeaturesFederationPlatform/Index');
    })->name('features.federation-platform');
});


Route::get('/about', function () {
    return Inertia::render('About/Index');
})->name('about');

Route::get('/support', function () {
    return Inertia::render('Support/Index');
})->name('support');

Route::get('/affiliation', function () {
    return Inertia::render('Affiliation/Index');
})->name('affiliation');

Route::get('/athletes', function () {
    return Inertia::render('Athletes/Index');
})->name('athletes');


Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');


Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->name('register');



Route::middleware(['auth'])->group(function () {
    Route::get('/create_organization', function () {
        return Inertia::render('NewOrganization/Index');
    })->name('Organization');

    // ✅ This MUST be inside the group
    Route::post('/organization', [OrganizationController::class, 'store']);
    Route::put('/organization/update', [OrganizationController::class, 'update']);
    Route::post('/academy/store', [AcademyController::class, 'store'])->name('academy.store');

    Route::get('/user', function () {
        return response()->json(auth()->user());
    });

    // Stripe Details Routes
    Route::get('/organization/stripe-details', [OrganizationStripeController::class, 'show'])->name('organization.stripe.show');
    Route::post('/organization/stripe-details', [OrganizationStripeController::class, 'store'])->name('organization.stripe.store');

    Route::get('/affiliation/register', function () {
        return Inertia::render('Affiliation/Register');
    })->name('affiliation.register');
    Route::post('/affiliation/store', [AffiliationController::class, 'store'])->name('affiliation.store');

    // Stripe Checkout Routes
    Route::post('/stripe/create-checkout-session', [StripeCheckoutController::class, 'createCheckoutSession'])->name('stripe.checkout');
    Route::get('/stripe/success', [StripeCheckoutController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [StripeCheckoutController::class, 'cancel'])->name('stripe.cancel');

    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::get('/events/{division}/division-athletes', [EventController::class, 'getDivisionAthletes'])
        ->name('events.division.athletes');
    Route::get('/events/{event}/divisions/{division}/brackets', [EventController::class, 'getDivisionDetails'])
        ->name('events.divisions.brackets');

    // Division participants count
    Route::get('/{event}/divisions/{division}/participants/count', [EventController::class, 'getDivisionParticipantsCount'])
        ->name('events.division.participants.count');

    // Division details and athletes
    Route::get('/{event}/divisions/{division}/details', [EventController::class, 'getDivisionDetails'])
        ->name('events.division.details');

    // Match List
    Route::get('/{id}/matchlist', function ($id) {
        return Inertia::render('Event/MatchList', ['id' => $id]);
    })->name('event.matchlist');

    // Schedule
    Route::get('/{id}/schedule', function ($id) {
        return Inertia::render('Event/Schedule', ['id' => $id]);
    })->name('event.schedule');

    // Registration
    Route::get('/{id}/register', function ($id) {
        $event = \App\Models\Event::findOrFail($id);
        return Inertia::render('Event/Register', [
            'event' => $event,
            'selectedProfile' => session('selected_profile'),
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    })->name('event.register');

    Route::post('/{id}/register', [EventRegistrationController::class, 'store'])->name('event.register.store');
});

// Stripe webhook route - must be outside all middleware groups
Route::post('/stripe/webhook', [StripeCheckoutController::class, 'handleWebhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware(['web', 'auth', 'csrf']);

Route::get('/events/{id}/athletes-count', [EventController::class, 'getAthletesCount'])->name('events.athletes.count');
Route::get('/events/{id}/athletes-list', [EventController::class, 'getAthletesList'])->name('events.athletes.list');

Route::middleware(['auth'])->group(function () {
    Route::get('/events/{division}/division-athletes', [EventController::class, 'getDivisionAthletes'])
        ->name('events.division.athletes');
});



// Club
Route::prefix('club')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Club/Index');
    })->name('club');

    Route::get('/finder', function () {
        return Inertia::render('Club/Finder/Index');
    })->name('club.finder');
});

// Event routes
Route::prefix('event')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $myEvents = [];
        $userId = null;
        if ($user) {
            $myEvents = Event::where('created_by', $user->id)->orderByDesc('start_date')->get();
            $userId = $user->id;
        }
        
        // Get all events
        $events = Event::orderByDesc('start_date')->get();
        
        return Inertia::render('Event/Index', [
            'myEvents' => $myEvents,
            'userId' => $userId,
            'events' => $events,
        ]);
    })->name('event');

    Route::get('/{id}', function ($id) {
        $event = \App\Models\Event::with('creator')->findOrFail($id);
        $eventCreator = $event->creator;
        $organization = $eventCreator ? $eventCreator->organization : null;
        
        return Inertia::render('Event/Show', [
            'event' => $event,
            'organization' => $organization,
            'eventCreator' => $eventCreator ? [
                'id' => $eventCreator->id,
                'phone' => $eventCreator->phone,
                'email' => $eventCreator->email
            ] : null
        ]);
    })->name('event.show');

    // Division routes
    Route::get('/{event}/divisions/{division}/details', [EventController::class, 'getDivisionDetails'])
        ->name('event.division.details');

    Route::get('/{event}/divisions/{division}/participants/count', [EventController::class, 'getDivisionParticipantsCount'])
        ->name('event.division.participants.count');

    // Athletes
    Route::get('/{id}/athletes', function ($id) {
        return Inertia::render('Event/Athletes', ['id' => $id]);
    })->name('event.athletes');

    // Results
    Route::get('/{id}/results', function ($id) {
        return Inertia::render('Event/Results', ['id' => $id]);
    })->name('event.results');

    // Brackets
    Route::get('/{id}/brackets', function ($id) {
        return Inertia::render('Event/Brackets', ['id' => $id]);
    })->name('event.brackets');

    // Match List
    Route::get('/{id}/matchlist', function ($id) {
        return Inertia::render('Event/MatchList', ['id' => $id]);
    })->name('event.matchlist');

    // Schedule
    Route::get('/{id}/schedule', function ($id) {
        return Inertia::render('Event/Schedule', ['id' => $id]);
    })->name('event.schedule');

    // Registration
    Route::get('/{id}/register', function ($id) {
        $event = \App\Models\Event::findOrFail($id);
        return Inertia::render('Event/Register', [
            'event' => $event,
            'selectedProfile' => session('selected_profile'),
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    })->name('event.register');

    Route::post('/{id}/register', [EventRegistrationController::class, 'store'])
        ->name('event.register.store');

    // Division athletes
    Route::get('/{event}/divisions/{division}/athletes', [EventController::class, 'getDivisionAthletes'])
        ->name('event.division.athletes');
});




Route::get('/dashboard', function () {
    $user = Auth::user();
    $organization = $user->organization; // 👈 using the relationship
    $events = Event::where('created_by', $user->id)->orderByDesc('created_at')->get();

    return Inertia::render('Dashboard', [
        'organization' => $organization,
        'events' => $events,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile management routes
    Route::get('/select-profile', function () {
        $profiles = Auth::user()->profiles()->get();
        return Inertia::render('SelectProfile/Index', [
            'profiles' => $profiles,
            'selectedProfile' => session('selected_profile'),
            'event_id' => request()->get('event_id')
        ]);
    })->name('select-profile');

    Route::get('/profile/create', function () {
        return Inertia::render('SelectProfile/Create');
    })->name('profile.create');

    Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/{profile}/switch', [ProfileController::class, 'switch'])->name('profile.switch');
    Route::get('/profile/{profile}/unlink', [ProfileController::class, 'unlink'])->name('profile.unlink');

    // Profile details routes
    Route::get('/profile/details', [ProfileController::class, 'getDetails'])->name('profile.details');
    Route::post('/profile/details/update', [ProfileController::class, 'updateDetails'])->name('profile.details.update');

    // Academy routes
    Route::get('/register/academy', [AcademyController::class, 'register'])->name('academy.register');
    Route::get('/affiliations/list', [AffiliationController::class, 'list'])->name('affiliations.list');

    // User details route
    Route::post('/user/details', [UserDetailsController::class, 'store'])->name('user.details.store');

    Route::get('/academies/list', [AcademyController::class, 'list'])->name('academies.list');
    Route::post('/academy/store', [AcademyController::class, 'store'])->name('academy.store');

    // Academy detail page
    Route::get('/academy/{id}', [AcademyController::class, 'show'])->name('academy.detail');
});

require __DIR__ . '/auth.php';

Route::get('/events/{event}/divisions/{division}/athletes', [EventController::class, 'getDivisionAthletes'])
    ->name('events.division.athletes');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (for admin login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    // Protected admin routes
    Route::middleware(['web', 'auth:admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // User management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        
        // Event management
        Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
        
        // Division management
        Route::resource('divisions', \App\Http\Controllers\Admin\DivisionController::class);
        
        // Athlete management
        Route::get('athletes', [\App\Http\Controllers\Admin\AthleteController::class, 'index'])->name('athletes.index');
        
        // Bracket management
        Route::resource('brackets', \App\Http\Controllers\Admin\BracketsController::class);
        
        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        
        // Affiliation management
        Route::resource('affiliations', \App\Http\Controllers\Admin\AffiliationController::class);
        
        // Academy management
        Route::resource('academies', \App\Http\Controllers\Admin\AcademyController::class);
        
        // Organization management
        Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);

        // Matches management
        Route::get('matches', [AdminMatchController::class, 'index'])->name('matches.index');
        Route::get('matches/{id}/edit', [AdminMatchController::class, 'edit'])->name('matches.edit');
        Route::put('matches/{id}', [AdminMatchController::class, 'update'])->name('matches.update');
        
        // Specific bracket routes
        Route::get('brackets/{id}/show', [BracketsController::class, 'show'])->name('brackets.show.alt');
        Route::get('brackets/{eventId}/divisions/{divisionId}', [BracketsController::class, 'showDivision'])->name('brackets.division.show');
        
        // Match management routes
        Route::post('events/{eventId}/divisions/{divisionId}/matches', [BracketsController::class, 'createMatch'])->name('brackets.matches.create');
        Route::delete('events/{eventId}/divisions/{divisionId}/matches/{matchId}', [BracketsController::class, 'deleteMatch'])->name('brackets.matches.delete');
        Route::patch('events/{eventId}/divisions/{divisionId}/matches/{matchId}/status', [BracketsController::class, 'updateMatchStatus'])->name('brackets.matches.status');
        Route::patch('events/{eventId}/divisions/{divisionId}/matches/{matchId}/result', [BracketsController::class, 'recordMatchResult'])->name('brackets.matches.result');
    });
});

// Event matches
Route::get('/{event}/matches', [EventController::class, 'getEventMatches'])->name('events.matches');

// Fix duplicate route names
Route::get('/{event}/divisions/{division}/details', [EventController::class, 'getDivisionDetails'])
    ->name('division.details.alt');

Route::get('/{event}/divisions/{division}/participants/count', [EventController::class, 'getDivisionParticipantsCount'])
    ->name('division.participants.count.alt');

// Fix duplicate matchlist routes
Route::get('/{id}/matchlist', function ($id) {
    return Inertia::render('Event/MatchList', ['id' => $id]);
})->name('event.matchlist.alt');

// Fix duplicate register routes
Route::get('/{id}/register', function ($id) {
    $event = \App\Models\Event::findOrFail($id);
    return Inertia::render('Event/Register', [
        'event' => $event,
        'selectedProfile' => session('selected_profile'),
        'auth' => [
            'user' => auth()->user()
        ]
    ]);
})->name('event.register.alt');

Route::post('/{id}/register', [EventRegistrationController::class, 'store'])->name('event.register.store.alt');

// Fix duplicate schedule routes
Route::get('/{id}/schedule', function ($id) {
    return Inertia::render('Event/Schedule', ['id' => $id]);
})->name('event.schedule.alt');

// Remove or rename duplicate routes
Route::get('/events/{event}/divisions/{division}/athletes', [EventController::class, 'getDivisionAthletes'])
    ->name('events.division.athletes');

Route::get('/events/{division}/division-athletes', [EventController::class, 'getDivisionAthletes'])
    ->name('events.division.athletes.alt');
