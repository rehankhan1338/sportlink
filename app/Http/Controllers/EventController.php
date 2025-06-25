<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\CheckoutDetail;
use App\Models\EventDivision;
use App\Models\BracketMatch;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function store(Request $request)
    {
        try {
            \Log::info('Event creation request data:', $request->all());

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'last_date_of_registration' => 'required|date|after_or_equal:start_date',
                'timezone' => 'nullable|string|max:255',
                'status' => 'required|string|in:draft,published',
                'visibility' => 'required|string|in:public,private',
                'description' => 'nullable|string',
                'game_id' => 'nullable|exists:games,id',
                'type' => 'nullable|string|max:255',
                'rules' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'adult_price' => 'required|numeric|min:0',
                'minor_price' => 'required|numeric|min:0',
                'children_price' => 'required|numeric|min:0',
                'divisions' => 'nullable|string'
            ]);

            \Log::info('Validated data:', $validated);

            if (!auth()->check()) {
                \Log::error('No authenticated user found');
                return back()->withInput()->with('error', 'You must be logged in to create an event.');
            }

            // Start database transaction
            DB::beginTransaction();

            try {
                // Handle image upload
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    Storage::disk('events')->putFileAs('', $image, $imageName);
                    $validated['image'] = 'events/' . $imageName;
                }

                // Set default values for required fields
                $validated['created_by'] = auth()->id();
                $validated['status'] = $validated['status'] ?? 'draft';
                $validated['visibility'] = $validated['visibility'] ?? 'public';
                $validated['timezone'] = $validated['timezone'] ?? 'UTC';

                // Format price values
                $validated['adult_price'] = number_format((float)$validated['adult_price'], 2, '.', '');
                $validated['minor_price'] = number_format((float)$validated['minor_price'], 2, '.', '');
                $validated['children_price'] = number_format((float)$validated['children_price'], 2, '.', '');

                // Remove game_id if it's empty or null
                if (empty($validated['game_id'])) {
                    unset($validated['game_id']);
                }

                // Remove divisions from validated data as it's not part of the events table
                $divisionsData = null;
                if (isset($validated['divisions'])) {
                    $divisionsData = json_decode($validated['divisions'], true);
                    unset($validated['divisions']);
                }

                \Log::info('Final data to be inserted:', $validated);

                $event = Event::create($validated);
                
                // Create divisions if provided
                if ($divisionsData && is_array($divisionsData)) {
                    foreach ($divisionsData as $division) {
                        $event->divisions()->create([
                            'name' => $division['name'],
                            'gender' => $division['gender'],
                            'min_age' => $division['min_age'],
                            'max_age' => $division['max_age'],
                            'min_weight' => $division['min_weight'],
                            'max_weight' => $division['max_weight'],
                            'belt_level' => $division['belt_level'],
                            'bracket_type' => $division['bracket_type'],
                            'match_duration_min' => $division['match_duration_min'],
                            'start_time' => $division['start_time'] ?? null,
                            'mat_number' => $division['mat_number'] ?? null
                        ]);
                    }
                }

                DB::commit();
                \Log::info('Event and divisions created successfully:', ['event_id' => $event->id]);
                
                return redirect()->route('dashboard')->with('success', 'Event created successfully.');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Failed to create event: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            \Log::info('Event update request data:', $request->all());

            $event = Event::with('divisions')->find($id);
            
            if (!$event) {
                \Log::error('Event not found:', ['id' => $id]);
                return back()->with('error', 'Event not found');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'last_date_of_registration' => 'required|date|after_or_equal:start_date',
                'timezone' => 'nullable|string|max:255',
                'status' => 'required|string|in:draft,published',
                'visibility' => 'required|string|in:public,private',
                'description' => 'nullable|string',
                'game_id' => 'nullable|exists:games,id',
                'type' => 'nullable|string|max:255',
                'rules' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'adult_price' => 'required|numeric|min:0',
                'minor_price' => 'required|numeric|min:0',
                'children_price' => 'required|numeric|min:0',
                'divisions' => 'nullable|string'
            ]);

            \Log::info('Validated data:', $validated);

            // Start database transaction
            DB::beginTransaction();

            try {
                // Handle image upload
                if ($request->hasFile('image')) {
                    // Delete old image if exists
                    if ($event->image) {
                        Storage::disk('public')->delete($event->image);
                    }

                    $image = $request->file('image');
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    Storage::disk('events')->putFileAs('', $image, $imageName);
                    $validated['image'] = 'events/' . $imageName;
                }

                // Format price values
                $validated['adult_price'] = number_format((float)$validated['adult_price'], 2, '.', '');
                $validated['minor_price'] = number_format((float)$validated['minor_price'], 2, '.', '');
                $validated['children_price'] = number_format((float)$validated['children_price'], 2, '.', '');

                // Handle divisions
                $divisionsData = null;
                if (isset($validated['divisions'])) {
                    $divisionsData = json_decode($validated['divisions'], true);
                    unset($validated['divisions']);
                    \Log::info('Decoded divisions data:', ['divisions' => $divisionsData]);
                }

                $event->update($validated);

                // Update divisions
                if ($divisionsData !== null) {
                    \Log::info('Current divisions before update:', ['divisions' => $event->divisions->toArray()]);
                    
                    // Get existing division IDs
                    $existingDivisionIds = $event->divisions->pluck('id')->toArray();
                    $updatedDivisionIds = [];
                    
                    if (is_array($divisionsData)) {
                        foreach ($divisionsData as $division) {
                            $divisionId = isset($division['id']) ? $division['id'] : null;
                            
                            if ($divisionId && in_array($divisionId, $existingDivisionIds)) {
                                // Update existing division
                                $event->divisions()->where('id', $divisionId)->update([
                                    'name' => $division['name'],
                                    'gender' => $division['gender'],
                                    'min_age' => $division['min_age'],
                                    'max_age' => $division['max_age'],
                                    'min_weight' => $division['min_weight'],
                                    'max_weight' => $division['max_weight'],
                                    'belt_level' => $division['belt_level'],
                                    'bracket_type' => $division['bracket_type'],
                                    'match_duration_min' => $division['match_duration_min'],
                                    'start_time' => isset($division['start_time']) ? date('Y-m-d H:i:s', strtotime($division['start_time'])) : null,
                                    'mat_number' => $division['mat_number'] ?? null
                                ]);
                                $updatedDivisionIds[] = $divisionId;
                            } else {
                                // Create new division
                                $newDivision = $event->divisions()->create([
                                    'name' => $division['name'],
                                    'gender' => $division['gender'],
                                    'min_age' => $division['min_age'],
                                    'max_age' => $division['max_age'],
                                    'min_weight' => $division['min_weight'],
                                    'max_weight' => $division['max_weight'],
                                    'belt_level' => $division['belt_level'],
                                    'bracket_type' => $division['bracket_type'],
                                    'match_duration_min' => $division['match_duration_min'],
                                    'start_time' => isset($division['start_time']) ? date('Y-m-d H:i:s', strtotime($division['start_time'])) : null,
                                    'mat_number' => $division['mat_number'] ?? null
                                ]);
                                $updatedDivisionIds[] = $newDivision->id;
                            }
                        }
                        
                        // Delete divisions that are no longer in the updated list
                        $divisionsToDelete = array_diff($existingDivisionIds, $updatedDivisionIds);
                        if (!empty($divisionsToDelete)) {
                            $event->divisions()->whereIn('id', $divisionsToDelete)->delete();
                        }
                        
                        \Log::info('Divisions updated successfully');
                    }
                }

                DB::commit();
                \Log::info('Event and divisions updated successfully:', ['event_id' => $id]);

                return back()->with('success', 'Event updated successfully.');
            } catch (\Exception $e) {
                DB::rollback();
                \Log::error('Error in transaction:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Failed to update event:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        \Log::info('Destroy method called with ID:', ['id' => $id]);
        
        try {
            $event = Event::find($id);
            
            if (!$event) {
                \Log::error('Event not found:', ['id' => $id]);
                return back()->with('error', 'Event not found');
            }

            DB::beginTransaction();
            
            try {
                $event->forceDelete();
                DB::commit();
                
                \Log::info('Delete operation completed:', ['event_id' => $id]);
                return back()->with('success', 'Event deleted successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete event:', [
                'event_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }

    public function getAthletesCount($id)
    {
        try {
            \Log::info('Getting athletes count for event:', ['event_id' => $id]);
            
            // Simple count query for all records with this event_id
            $count = \App\Models\CheckoutDetail::where('event_id', $id)->count();
            
            \Log::info('Total checkout details count:', [
                'event_id' => $id,
                'count' => $count
            ]);
            
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Failed to get athletes count:', [
                'event_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to get athletes count'], 500);
        }
    }

    public function getAthletesList($id)
    {
        try {
            \Log::info('Getting athletes list for event:', ['event_id' => $id]);
            
            // Get all checkout details for this event with related profile and academy information
            $athletes = \App\Models\CheckoutDetail::where('event_id', $id)
                ->where('payment_status', 'completed')
                ->with(['user', 'profile', 'academy'])
                ->get()
                ->map(function ($checkout) {
                    $profile = $checkout->profile;
                    return [
                        'id' => $profile->id,
                        'name' => $profile->first_name . ' ' . $profile->last_name,
                        'image' => $profile->passport_image_path ? '/storage/' . $profile->passport_image_path : null,
                        'birth_year' => $profile->date_of_birth ? $profile->date_of_birth->format('Y') : null,
                        'age' => $profile->age,
                        'academy' => $checkout->academy_name,
                        'academy_url' => $checkout->academy_id ? '/academy/' . $checkout->academy_id : null,
                        'weight' => $profile->weight,
                        'gender' => $profile->gender,
                        'nationality' => $profile->nationality,
                        'country_of_residence' => $profile->country_of_residence
                    ];
                });
            
            \Log::info('Athletes list retrieved successfully', [
                'event_id' => $id,
                'count' => $athletes->count()
            ]);
            
            return response()->json(['athletes' => $athletes]);
        } catch (\Exception $e) {
            \Log::error('Failed to get athletes list:', [
                'event_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to get athletes list'], 500);
        }
    }

    public function index()
    {
        $user = auth()->user();
        $events = Event::with('divisions')
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Dashboard', [
            'events' => $events,
            'organization' => $user->organization
        ]);
    }

    public function getBrackets($id)
    {
        try {
            $event = Event::with(['divisions'])->findOrFail($id);
            
            // Get all brackets from divisions
            $brackets = $event->divisions->map(function ($division) {
                // Get participants for this division
                $participants = \App\Models\Participant::where('category_id', $division->id)
                    ->with(['user', 'team'])
                    ->get();

                // Get matches for this division
                $matches = \App\Models\Matches::where('bracket_id', $division->id)
                    ->with(['team1', 'team2'])
                    ->orderBy('round')
                    ->get();

                // Separate matches into semifinals and finals
                $semifinals = $matches->where('round', 1);
                $finals = $matches->where('round', 2);

                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'bracket_type' => $division->bracket_type,
                    'mat_number' => rand(1, 3), // This should come from your actual data
                    'start_time' => '10:00', // This should come from your actual data
                    'participants' => $participants->map(function ($participant) {
                        return [
                            'id' => $participant->id,
                            'name' => $participant->user->name,
                            'team' => $participant->team->name ?? 'No Team',
                            'avatar' => $participant->user->avatar ?? null,
                            'country' => $participant->user->country ?? 'Unknown'
                        ];
                    }),
                    'semifinals' => $semifinals->map(function ($match) {
                        return [
                            'id' => $match->id,
                            'number' => $match->id,
                            'mat' => rand(1, 3), // This should come from your actual data
                            'time' => '10:00', // This should come from your actual data
                            'player1' => [
                                'id' => $match->team1->id,
                                'name' => $match->team1->name,
                                'team' => $match->team1->academy ?? 'No Team',
                                'avatar' => $match->team1->avatar ?? null,
                                'country' => $match->team1->country ?? 'Unknown',
                                'isWinner' => $match->winner_id === $match->team1->id
                            ],
                            'player2' => [
                                'id' => $match->team2->id,
                                'name' => $match->team2->name,
                                'team' => $match->team2->academy ?? 'No Team',
                                'avatar' => $match->team2->avatar ?? null,
                                'country' => $match->team2->country ?? 'Unknown',
                                'isWinner' => $match->winner_id === $match->team2->id
                            ]
                        ];
                    }),
                    'finals' => $finals->map(function ($match) {
                        return [
                            'id' => $match->id,
                            'number' => $match->id,
                            'mat' => rand(1, 3), // This should come from your actual data
                            'time' => '10:30', // This should come from your actual data
                            'player1' => [
                                'id' => $match->team1->id,
                                'name' => $match->team1->name,
                                'team' => $match->team1->academy ?? 'No Team',
                                'avatar' => $match->team1->avatar ?? null,
                                'country' => $match->team1->country ?? 'Unknown',
                                'isWinner' => $match->winner_id === $match->team1->id
                            ],
                            'player2' => [
                                'id' => $match->team2->id,
                                'name' => $match->team2->name,
                                'team' => $match->team2->academy ?? 'No Team',
                                'avatar' => $match->team2->avatar ?? null,
                                'country' => $match->team2->country ?? 'Unknown',
                                'isWinner' => $match->winner_id === $match->team2->id
                            ]
                        ];
                    })
                ];
            });

            return response()->json([
                'brackets' => $brackets
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get brackets:', [
                'event_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to get brackets'], 500);
        }
    }

    public function getDivisionParticipantsCount($event, $division)
    {
        try {
            \Log::info('Fetching division participants count:', [
                'event_id' => $event,
                'division_id' => $division
            ]);

            // Count unique profile_ids from checkout_details table
            $count = \App\Models\CheckoutDetail::where('event_id', $event)
                ->where('division_id', $division)
                ->where('payment_status', 'completed')
                ->whereNotNull('profile_id')
                ->distinct('profile_id')
                ->count('profile_id');

            \Log::info('Division participants count:', [
                'count' => $count
            ]);

            return response()->json([
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get division participants count:', [
                'event_id' => $event,
                'division_id' => $division,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to get division participants count'], 500);
        }
    }

    public function getDivisionAthletes($eventId, $divisionId)
    {
        $athletes = CheckoutDetail::where('event_id', $eventId)
            ->where('division_id', $divisionId)
            ->with(['profile' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'weight', 'age', 'nationality', 'user_id')
                    ->with(['user:id,email']);
            }])
            ->get()
            ->map(function ($checkout) {
                $profile = $checkout->profile;
                return [
                    'id' => $profile->id,
                    'name' => $profile->first_name . ' ' . $profile->last_name,
                    'weight' => $profile->weight,
                    'age' => $profile->age,
                    'nationality' => $profile->nationality,
                    'email' => $profile->user->email,
                ];
            });

        return response()->json(['athletes' => $athletes]);
    }

    public function getDivisionBrackets($event, $division)
    {
        try {
            \Log::info('Getting division brackets:', [
                'event_id' => $event,
                'division_id' => $division
            ]);

            // 1. Get division details
            $divisionDetails = \App\Models\EventDivision::findOrFail($division);
            
            // 2. Verify division belongs to event
            if ($divisionDetails->event_id != $event) {
                return response()->json(['error' => 'Division does not belong to this event'], 400);
            }

            // 3. Get athletes from checkout_details with their profile information
            $athletes = \App\Models\CheckoutDetail::where('division_id', $division)
                ->where('payment_status', 'completed')
                ->with(['profile' => function($query) {
                    $query->select(
                        'id',
                        'first_name',
                        'last_name',
                        'weight',
                        'country_of_residence',
                        'passport_image_path'
                    );
                }])
                ->get()
                ->map(function($registration) {
                    return [
                        'id' => $registration->profile->id, // Get the actual profile ID
                        'name' => $registration->profile->first_name . ' ' . $registration->profile->last_name,
                        'weight' => $registration->profile->weight,
                        'country' => $registration->profile->country_of_residence,
                        'academy' => $registration->academy_name,
                        'avatar' => $registration->profile->passport_image_path ?? '/images/default-avatar.png'
                    ];
                });

            \Log::info('Found athletes:', [
                'count' => $athletes->count(),
                'first_few' => $athletes->take(3)->toArray()
            ]);

            // 4. Get or create matches for this division
            $matches = \App\Models\BracketMatch::where('event_id', $event)
                ->where('division_id', $division)
                ->with(['athlete1', 'athlete2'])
                ->orderBy('round')
                ->orderBy('match_number')
                ->get();

            // 5. If no matches exist and we have athletes, create the bracket structure
            if ($matches->isEmpty() && $athletes->count() >= 2) {
                $matches = $this->createBracketMatches($event, $division, $athletes->toArray(), $divisionDetails);
                
                // Reload matches with relationships after creation
                $matches = \App\Models\BracketMatch::where('event_id', $event)
                    ->where('division_id', $division)
                    ->with(['athlete1', 'athlete2'])
                    ->orderBy('round')
                    ->orderBy('match_number')
                    ->get();
            }

            // 6. Map matches to include athlete details
            $mappedMatches = $matches->map(function($match) {
                $athlete1Details = null;
                if ($match->athlete1) {
                    $athlete1Details = [
                        'id' => $match->athlete1->id,
                        'name' => $match->athlete1->first_name . ' ' . $match->athlete1->last_name,
                        'avatar' => $match->athlete1->passport_image_path ?? '/images/default-avatar.png'
                    ];
                }

                $athlete2Details = null;
                if ($match->athlete2) {
                    $athlete2Details = [
                        'id' => $match->athlete2->id,
                        'name' => $match->athlete2->first_name . ' ' . $match->athlete2->last_name,
                        'avatar' => $match->athlete2->passport_image_path ?? '/images/default-avatar.png'
                    ];
                }

                return [
                    'id' => $match->id,
                    'round' => $match->round,
                    'match_number' => $match->match_number,
                    'mat_number' => $match->mat_number,
                    'scheduled_time' => $match->scheduled_time,
                    'status' => $match->status,
                    'athlete1' => $athlete1Details,
                    'athlete2' => $athlete2Details,
                    'winner_id' => $match->winner_id,
                    'score_details' => $match->score_details
                ];
            });

            // 7. Prepare and send response
            $response = [
                'matches' => $mappedMatches,
                'athletes' => $athletes,
                'division' => [
                    'id' => $divisionDetails->id,
                    'name' => $divisionDetails->name,
                    'min_age' => $divisionDetails->min_age,
                    'max_age' => $divisionDetails->max_age,
                    'min_weight' => $divisionDetails->min_weight,
                    'max_weight' => $divisionDetails->max_weight,
                    'belt_level' => $divisionDetails->belt_level,
                    'gender' => $divisionDetails->gender,
                    'bracket_type' => $divisionDetails->bracket_type,
                    'start_time' => $divisionDetails->start_time,
                    'mat_number' => $divisionDetails->mat_number
                ]
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Failed to get division brackets:', [
                'event_id' => $event,
                'division_id' => $division,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to get division brackets: ' . $e->getMessage()], 500);
        }
    }

    private function createBracketMatches($eventId, $divisionId, $athletes, $division)
    {
        // Handle different bracket types
        switch ($division->bracket_type) {
            case 'single_elimination':
                return $this->createSingleEliminationBracket($eventId, $divisionId, $athletes, $division);
            case 'double_elimination':
                return $this->createDoubleEliminationBracket($eventId, $divisionId, $athletes, $division);
            case 'round_robin':
                return $this->createRoundRobinBracket($eventId, $divisionId, $athletes, $division);
            default:
                return $this->createSingleEliminationBracket($eventId, $divisionId, $athletes, $division);
        }
    }

    private function createSingleEliminationBracket($eventId, $divisionId, $athletes, $division)
    {
        $matches = collect();
        
        // Only create semifinals if we have 4 or more athletes
        if (count($athletes) >= 4) {
            // Create semifinals (2 matches)
            for ($i = 0; $i < 2; $i++) {
                $match = \App\Models\BracketMatch::create([
                    'event_id' => $eventId,
                    'division_id' => $divisionId,
                    'round' => 1, // Semifinals
                    'match_number' => $i + 1,
                    'athlete1_id' => isset($athletes[$i * 2]) ? $athletes[$i * 2]['id'] : null,
                    'athlete2_id' => isset($athletes[$i * 2 + 1]) ? $athletes[$i * 2 + 1]['id'] : null,
                    'mat_number' => $division->mat_number ?? 'TBD',
                    'scheduled_time' => $division->start_time ?? now(),
                    'status' => 'pending'
                ]);
                $matches->push($match);
            }

            // Create bronze match (optional)
            $bronzeMatch = \App\Models\BracketMatch::create([
                'event_id' => $eventId,
                'division_id' => $divisionId,
                'round' => 2, // Bronze match
                'match_number' => 1,
                'mat_number' => $division->mat_number ?? 'TBD',
                'scheduled_time' => $division->start_time ? $division->start_time->addMinutes(30) : now()->addMinutes(30),
                'status' => 'pending'
            ]);
            $matches->push($bronzeMatch);
        }

        // Create final match
        $finalMatch = \App\Models\BracketMatch::create([
            'event_id' => $eventId,
            'division_id' => $divisionId,
            'round' => 3, // Final match
            'match_number' => 1,
            'mat_number' => $division->mat_number ?? 'TBD',
            'scheduled_time' => $division->start_time ? $division->start_time->addMinutes(60) : now()->addMinutes(60),
            'status' => 'pending'
        ]);
        $matches->push($finalMatch);

        return $matches;
    }

    private function createDoubleEliminationBracket($eventId, $divisionId, $athletes, $division)
    {
        $matches = collect();
        
        // Only create semifinals if we have 4 or more athletes
        if (count($athletes) >= 4) {
            // Create semifinals (2 matches)
            for ($i = 0; $i < 2; $i++) {
                $match = \App\Models\BracketMatch::create([
                    'event_id' => $eventId,
                    'division_id' => $divisionId,
                    'round' => 1, // Semifinals
                    'match_number' => $i + 1,
                    'athlete1_id' => isset($athletes[$i * 2]) ? $athletes[$i * 2]['id'] : null,
                    'athlete2_id' => isset($athletes[$i * 2 + 1]) ? $athletes[$i * 2 + 1]['id'] : null,
                    'mat_number' => $division->mat_number ?? 'TBD',
                    'scheduled_time' => $division->start_time ?? now(),
                    'status' => 'pending'
                ]);
                $matches->push($match);
            }
        }

        // Create final match
        $finalMatch = \App\Models\BracketMatch::create([
            'event_id' => $eventId,
            'division_id' => $divisionId,
            'round' => 3, // Final match
            'match_number' => 1,
            'mat_number' => $division->mat_number ?? 'TBD',
            'scheduled_time' => $division->start_time ? $division->start_time->addMinutes(60) : now()->addMinutes(60),
            'status' => 'pending'
        ]);
        $matches->push($finalMatch);

        return $matches;
    }

    private function createRoundRobinBracket($eventId, $divisionId, $athletes, $division)
    {
        $matches = collect();
        $athleteCount = count($athletes);
        
        // Create matches for each athlete to face every other athlete once
        for ($i = 0; $i < $athleteCount; $i++) {
            for ($j = $i + 1; $j < $athleteCount; $j++) {
                $match = \App\Models\BracketMatch::create([
                    'event_id' => $eventId,
                    'division_id' => $divisionId,
                    'round' => 1, // All matches are round 1 in round robin
                    'match_number' => $matches->count() + 1,
                    'athlete1_id' => $athletes[$i]['id'],
                    'athlete2_id' => $athletes[$j]['id'],
                    'mat_number' => $division->mat_number ?? 'TBD',
                    'scheduled_time' => $division->start_time ? $division->start_time->addMinutes(30 * $matches->count()) : now()->addMinutes(30 * $matches->count()),
                    'status' => 'pending'
                ]);
                $matches->push($match);
            }
        }

        return $matches;
    }

    public function getDivisionDetails($eventId, $divisionId)
    {
        try {
            // Get division details
            $division = EventDivision::with(['event'])->findOrFail($divisionId);
            
            // Get matches with relationships
            $matches = BracketMatch::where('event_id', $eventId)
                ->where('division_id', $divisionId)
                ->with(['player1', 'player2'])  // Load player relationships
                ->orderBy('round_number')
                ->orderBy('match_number')
                ->get()
                ->map(function ($match) {
                    return [
                        'id' => $match->id,
                        'division_type' => $match->division_type,
                        'bracket_type' => $match->bracket_type,
                        'round_type' => $this->determineRoundType($match),
                        'round_number' => $match->round_number,
                        'match_number' => $match->match_number,
                        'mat_name' => $match->mat_name,
                        'scheduled_time' => $match->scheduled_time,
                        'status' => $match->status,
                        'player1_id' => $match->player1_id,
                        'player2_id' => $match->player2_id,
                        'player1' => $match->player1 ? [
                            'id' => $match->player1->id,
                            'name' => $match->player1->first_name . ' ' . $match->player1->last_name,
                            'academy' => $match->player1->academy_name,
                            'image' => $match->player1->passport_image_path ? '/storage/' . $match->player1->passport_image_path : '/images/default-avatar.png'
                        ] : null,
                        'player2' => $match->player2 ? [
                            'id' => $match->player2->id,
                            'name' => $match->player2->first_name . ' ' . $match->player2->last_name,
                            'academy' => $match->player2->academy_name,
                            'image' => $match->player2->passport_image_path ? '/storage/' . $match->player2->passport_image_path : '/images/default-avatar.png'
                        ] : null,
                        'winner_id' => $match->winner_id,
                        'score_details' => $match->score_details
                    ];
                });

            return response()->json([
                'division' => [
                    'id' => $division->id,
                    'name' => $division->name,
                    'bracket_type' => $division->bracket_type,
                    'start_time' => $division->start_time,
                    'mat_number' => $division->mat_number
                ],
                'matches' => $matches
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get division details:', [
                'event_id' => $eventId,
                'division_id' => $divisionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to get division details'], 500);
        }
    }

    private function determineRoundType($match)
    {
        if ($match->division_type === 'single_elimination') {
            switch ($match->round_number) {
                case 1:
                    return 'semifinal';
                case 2:
                    return $match->bracket_type === 'bronze' ? 'bronze' : 'semifinal';
                case 3:
                    return 'final';
                default:
                    return 'round_' . $match->round_number;
            }
        } elseif ($match->division_type === 'double_elimination') {
            if ($match->bracket_type === 'final') {
                return 'final';
            } elseif ($match->bracket_type === 'upper' && $match->round_number === 1) {
                return 'semifinal';
            } else {
                return $match->bracket_type . '_round_' . $match->round_number;
            }
        } else { // round_robin
            return 'round_' . $match->round_number;
        }
    }

    public function getEventMatches($eventId)
    {
        try {
            $matches = BracketMatch::where('event_id', $eventId)
                ->with(['division:id,name', 'player1', 'player2'])
                ->leftJoin('profiles as winner_profile', function($join) {
                    $join->on('matches.who_won', '=', 'winner_profile.id');
                })
                ->select(
                    'matches.*',
                    'winner_profile.first_name as winner_first_name',
                    'winner_profile.last_name as winner_last_name',
                    'winner_profile.id as winner_profile_id'
                )
                ->orderBy('matches.scheduled_time')
                ->get()
                ->map(function ($match) {
                    return [
                        'id' => $match->id,
                        'division' => $match->division->name,
                        'round_type' => $match->round_type,
                        'mat_name' => $match->mat_name,
                        'scheduled_time' => $match->scheduled_time,
                        'status' => $match->status,
                        'player1' => $match->player1 ? [
                            'id' => $match->player1->id,
                            'name' => $match->player1->first_name . ' ' . $match->player1->last_name,
                            'image' => $match->player1->passport_image_path ? '/storage/' . $match->player1->passport_image_path : '/images/default-avatar.png'
                        ] : null,
                        'player2' => $match->player2 ? [
                            'id' => $match->player2->id,
                            'name' => $match->player2->first_name . ' ' . $match->player2->last_name,
                            'image' => $match->player2->passport_image_path ? '/storage/' . $match->player2->passport_image_path : '/images/default-avatar.png'
                        ] : null,
                        'winner_id' => $match->winner_profile_id,
                        'who_won' => $match->winner_first_name && $match->winner_last_name ? 
                            $match->winner_first_name . ' ' . $match->winner_last_name : null,
                        'score_details' => $match->score_details
                    ];
                });

            return response()->json(['matches' => $matches]);
        } catch (\Exception $e) {
            Log::error('Failed to get event matches:', [
                'event_id' => $eventId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to get event matches'], 500);
        }
    }
}
