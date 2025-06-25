<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDivision;
use App\Models\GameMatch;
use App\Models\Profile;
use App\Models\CheckoutDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BracketsController extends Controller
{
    public function index()
    {
        // Get all events that have divisions
        $events = DB::table('events')
            ->join('event_to_divisions', 'events.id', '=', 'event_to_divisions.event_id')
            ->select('events.*')
            ->distinct()
            ->get();

        // For each event, get the division count and bracket status
        $eventsData = $events->map(function($event) {
            $divisions = DB::table('event_to_divisions')
                ->where('event_id', $event->id)
                ->get();
            
            $divisionsWithBrackets = DB::table('event_to_divisions')
                ->where('event_id', $event->id)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('matches')
                        ->whereRaw('matches.division_id = event_to_divisions.id');
                })
                ->count();

            return [
                'id' => $event->id,
                'name' => $event->title,
                'start_date' => $event->start_date,
                'divisions_count' => $divisions->count(),
                'brackets_generated' => $divisionsWithBrackets,
                'status' => $event->status
            ];
        });

        return Inertia::render('Admin/Events/Brackets', [
            'events' => [
                'data' => $eventsData,
                'links' => [] // Add pagination if needed
            ]
        ]);
    }

    public function show($id)
    {
        \DB::enableQueryLog();
        
        $event = Event::with(['divisions'])->findOrFail($id);
        
        // Get divisions with their details and athletes count
        $divisions = $event->divisions->map(function($division) use ($id) {
            // Log the exact values being used in the query
            \Log::info('Querying division:', [
                'division_id' => $division->id,
                'event_id' => $id,
                'division_name' => $division->name
            ]);

            // Get participants count from checkout_details table
            $query = \App\Models\CheckoutDetail::where('event_id', $id)
                ->where('division_id', $division->id)
                ->where('payment_status', 'completed')
                ->whereNotNull('profile_id')
                ->distinct('profile_id');

            // Log the SQL query before execution
            \Log::info('SQL Query:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $athletesCount = $query->count('profile_id');

            // Log the result
            \Log::info('Division participants count result:', [
                'division_id' => $division->id,
                'event_id' => $id,
                'count' => $athletesCount,
                'queries' => \DB::getQueryLog()
            ]);

            return [
                'id' => $division->id,
                'name' => $division->name,
                'min_age' => $division->min_age,
                'max_age' => $division->max_age,
                'min_weight' => $division->min_weight,
                'max_weight' => $division->max_weight,
                'belt_level' => $division->belt_level,
                'gender' => $division->gender,
                'bracket_type' => $division->bracket_type,
                'start_time' => $division->start_time,
                'mat_number' => $division->mat_number,
                'participants_count' => $athletesCount
            ];
        });

        // Log all divisions data
        \Log::info('All divisions data:', [
            'event_id' => $id,
            'divisions' => $divisions->toArray()
        ]);
        
        $matches = GameMatch::with(['division', 'player1', 'player2'])
            ->where('event_id', $id)
            ->get()
            ->map(function($match) {
                return [
                    'id' => $match->id,
                    'division' => [
                        'id' => $match->division->id,
                        'name' => $match->division->name,
                    ],
                    'round_number' => $match->round_number,
                    'match_number' => $match->match_number,
                    'mat_name' => $match->mat_name,
                    'status' => $match->status,
                    'player1' => $match->player1 ? [
                        'id' => $match->player1->id,
                        'name' => $match->player1->first_name . ' ' . $match->player1->last_name,
                    ] : null,
                    'player2' => $match->player2 ? [
                        'id' => $match->player2->id,
                        'name' => $match->player2->first_name . ' ' . $match->player2->last_name,
                    ] : null,
                    'winner_id' => $match->winner_id,
                    'scheduled_time' => $match->scheduled_time,
                ];
            });

        return Inertia::render('Admin/Brackets/Show', [
            'event' => [
                'id' => $event->id,
                'name' => $event->title,
                'divisions' => $divisions,
            ],
            'matches' => $matches,
        ]);
    }

    public function showEventBrackets($eventId)
    {
        $event = Event::with(['divisions' => function($query) {
            $query->withCount(['matches', 'checkoutDetails as athletes_count' => function($query) {
                $query->where('payment_status', 'completed');
            }]);
        }])->findOrFail($eventId);

        $divisions = $event->divisions->map(function($division) {
            return [
                'id' => $division->id,
                'name' => $division->name,
                'bracket_type' => $division->bracket_type,
                'start_time' => $division->start_time,
                'mat_area' => $division->mat_number,
                'athletes_count' => $division->athletes_count,
                'matches_created' => $division->matches_count > 0
            ];
        });

        return Inertia::render('Admin/Brackets/Index', [
            'event' => [
                'id' => $event->id,
                'name' => $event->title
            ],
            'divisions' => $divisions
        ]);
    }

    public function edit($eventId, $divisionId)
    {
        $event = Event::findOrFail($eventId);
        $division = EventDivision::with(['matches' => function($query) {
            $query->orderBy('round_number')
                ->orderBy('mat_name');
        }, 'checkoutDetails.profile'])
        ->where('event_id', $eventId)
        ->findOrFail($divisionId);

        $athletes = $division->checkoutDetails->map(function($checkout) {
            $profile = $checkout->profile;
            return [
                'id' => $profile->id,
                'name' => $profile->first_name . ' ' . $profile->last_name,
                'weight' => $profile->weight,
                'age' => $profile->age,
                'nationality' => $profile->nationality,
                'academy' => $checkout->academy_name
            ];
        });

        return Inertia::render('Admin/Brackets/Edit', [
            'event' => [
                'id' => $event->id,
                'name' => $event->title
            ],
            'division' => [
                'id' => $division->id,
                'name' => $division->name,
                'bracket_type' => $division->bracket_type,
                'start_time' => $division->start_time,
                'mat_area' => $division->mat_number
            ],
            'matches' => $division->matches,
            'athletes' => $athletes
        ]);
    }

    public function generateMatches($eventId, $divisionId)
    {
        $division = DB::table('event_to_divisions')->where('id', $divisionId)->first();
        if (!$division) {
            abort(404);
        }

        $athletes = [];
        $checkouts = DB::table('checkout_details')
            ->where('division_id', $divisionId)
            ->get();
            
        foreach ($checkouts as $checkout) {
            $profile = DB::table('profiles')->where('id', $checkout->profile_id)->first();
            if ($profile) {
                $athletes[] = $profile;
            }
        }

        // Delete existing matches
        DB::table('matches')->where('division_id', $divisionId)->delete();

        // Shuffle athletes
        shuffle($athletes);

        switch ($division->bracket_type) {
            case 'single_elimination':
                $this->generateSingleEliminationMatches($eventId, $division, $athletes);
                break;
            case 'double_elimination':
                $this->generateDoubleEliminationMatches($eventId, $division, $athletes);
                break;
            case 'round_robin':
                $this->generateRoundRobinMatches($eventId, $division, $athletes);
                break;
        }

        return redirect()->back()->with('success', 'Matches generated successfully');
    }

    private function generateSingleEliminationMatches($eventId, $division, $athletes)
    {
        // Calculate the number of slots needed (next power of 2)
        $numAthletes = count($athletes);
        $numSlots = 1;
        while ($numSlots < $numAthletes) {
            $numSlots *= 2;
        }
        
        // Calculate number of rounds
        $numRounds = log($numSlots, 2);
        
        // Track matches by round for parent linking
        $matchesByRound = [];
        $matchesByRound[1] = [];
        
        // Generate first round matches
        $matchNumber = 1;
        for ($i = 0; $i < $numSlots/2; $i++) {
            $athlete1 = isset($athletes[$i * 2]) ? $athletes[$i * 2] : null;
            $athlete2 = isset($athletes[$i * 2 + 1]) ? $athletes[$i * 2 + 1] : null;
            
            // Skip creating match if both athletes are null (BYE vs BYE)
            if (!$athlete1 && !$athlete2) {
                continue;
            }
            
            $matchData = [
                'division_id' => $division->id,
                'event_id' => $eventId,
                'division_type' => 'single_elimination',
                'bracket_type' => 'upper',
                'round_number' => 1,
                'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                'player1_id' => $athlete1 ? $athlete1->id : null,
                'player2_id' => $athlete2 ? $athlete2->id : null,
                'scheduled_time' => $division->start_time ?? now(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // If one athlete is null (BYE), auto-advance the other athlete
            if ($athlete1 && !$athlete2) {
                $matchData['winner_id'] = $athlete1->id;
                $matchData['loser_id'] = null;
                $matchData['status'] = 'completed';
            } elseif (!$athlete1 && $athlete2) {
                $matchData['winner_id'] = $athlete2->id;
                $matchData['loser_id'] = null;
                $matchData['status'] = 'completed';
            }
            
            $matchId = DB::table('matches')->insertGetId($matchData);
            $matchesByRound[1][] = $matchId;
            $matchNumber++;
        }
        
        // Generate subsequent rounds
        for ($round = 2; $round <= $numRounds; $round++) {
            $matchesByRound[$round] = [];
            $previousRoundMatches = $matchesByRound[$round - 1];
            $numMatchesThisRound = ceil(count($previousRoundMatches) / 2);
            
            for ($i = 0; $i < $numMatchesThisRound; $i++) {
                $parentMatch1Id = isset($previousRoundMatches[$i * 2]) ? $previousRoundMatches[$i * 2] : null;
                $parentMatch2Id = isset($previousRoundMatches[$i * 2 + 1]) ? $previousRoundMatches[$i * 2 + 1] : null;
                
                // Skip if both parent matches are null
                if (!$parentMatch1Id && !$parentMatch2Id) {
                    continue;
                }
                
                $matchData = [
                    'division_id' => $division->id,
                    'event_id' => $eventId,
                    'division_type' => 'single_elimination',
                    'bracket_type' => $round == $numRounds ? 'final' : 'upper',
                    'round_number' => $round,
                    'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                    'player1_id' => null,
                    'player2_id' => null,
                    'parent_match1_id' => $parentMatch1Id,
                    'parent_match2_id' => $parentMatch2Id,
                    'scheduled_time' => $division->start_time ? Carbon::parse($division->start_time)->addMinutes(30 * ($round - 1)) : now()->addMinutes(30 * ($round - 1)),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                // If one parent match is completed with a BYE, get its winner
                if ($parentMatch1Id) {
                    $parentMatch1 = DB::table('matches')->find($parentMatch1Id);
                    if ($parentMatch1 && $parentMatch1->status === 'completed') {
                        $matchData['player1_id'] = $parentMatch1->winner_id;
                    }
                }
                
                if ($parentMatch2Id) {
                    $parentMatch2 = DB::table('matches')->find($parentMatch2Id);
                    if ($parentMatch2 && $parentMatch2->status === 'completed') {
                        $matchData['player2_id'] = $parentMatch2->winner_id;
                    }
                }
                
                // If both athletes are set from BYE matches, mark this match as pending
                if ($matchData['player1_id'] && $matchData['player2_id']) {
                    $matchData['status'] = 'pending';
                }
                // If only one athlete is set from a BYE match, auto-advance them
                elseif ($matchData['player1_id'] && !$matchData['player2_id']) {
                    $matchData['status'] = 'completed';
                    $matchData['winner_id'] = $matchData['player1_id'];
                    $matchData['loser_id'] = null;
                }
                elseif (!$matchData['player1_id'] && $matchData['player2_id']) {
                    $matchData['status'] = 'completed';
                    $matchData['winner_id'] = $matchData['player2_id'];
                    $matchData['loser_id'] = null;
                }
                
                $matchId = DB::table('matches')->insertGetId($matchData);
                $matchesByRound[$round][] = $matchId;
                $matchNumber++;
            }
        }
        
        // Create bronze match if we have semifinals (at least 4 athletes)
        if (count($athletes) >= 4) {
            $semifinalMatches = $matchesByRound[$numRounds - 1];
            if (count($semifinalMatches) >= 2) {
                DB::table('matches')->insert([
                    'division_id' => $division->id,
                    'event_id' => $eventId,
                    'division_type' => 'single_elimination',
                    'bracket_type' => 'bronze',
                    'round_number' => $numRounds,
                    'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                    'parent_match1_id' => $semifinalMatches[0],
                    'parent_match2_id' => $semifinalMatches[1],
                    'scheduled_time' => $division->start_time ? Carbon::parse($division->start_time)->addMinutes(30 * $numRounds) : now()->addMinutes(30 * $numRounds),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        return true;
    }

    private function generateDoubleEliminationMatches($eventId, $division, $athletes)
    {
        // Calculate the number of slots needed (next power of 2)
        $numAthletes = count($athletes);
        $numSlots = 1;
        while ($numSlots < $numAthletes) {
            $numSlots *= 2;
        }
        
        // Calculate number of rounds
        $numRounds = log($numSlots, 2);
        
        // Track matches by round and bracket for parent linking
        $upperBracketMatches = [];
        $lowerBracketMatches = [];
        $matchNumber = 1;
        
        // Generate upper bracket first round matches
        $upperBracketMatches[1] = [];
        for ($i = 0; $i < $numSlots/2; $i++) {
            $athlete1 = isset($athletes[$i * 2]) ? $athletes[$i * 2] : null;
            $athlete2 = isset($athletes[$i * 2 + 1]) ? $athletes[$i * 2 + 1] : null;
            
            // Skip creating match if both athletes are null (BYE vs BYE)
            if (!$athlete1 && !$athlete2) {
                continue;
            }
            
            $matchData = [
                'division_id' => $division->id,
                'event_id' => $eventId,
                'division_type' => 'double_elimination',
                'bracket_type' => 'upper',
                'round_number' => 1,
                'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                'player1_id' => $athlete1 ? $athlete1->id : null,
                'player2_id' => $athlete2 ? $athlete2->id : null,
                'scheduled_time' => $division->start_time ?? now(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // If one athlete is null (BYE), auto-advance the other athlete
            if ($athlete1 && !$athlete2) {
                $matchData['winner_id'] = $athlete1->id;
                $matchData['loser_id'] = null;
                $matchData['status'] = 'completed';
            } elseif (!$athlete1 && $athlete2) {
                $matchData['winner_id'] = $athlete2->id;
                $matchData['loser_id'] = null;
                $matchData['status'] = 'completed';
            }
            
            $matchId = DB::table('matches')->insertGetId($matchData);
            $upperBracketMatches[1][] = $matchId;
            $matchNumber++;
        }
        
        // Generate subsequent upper bracket rounds
        for ($round = 2; $round <= $numRounds; $round++) {
            $upperBracketMatches[$round] = [];
            $previousRoundMatches = $upperBracketMatches[$round - 1];
            $numMatchesThisRound = ceil(count($previousRoundMatches) / 2);
            
            for ($i = 0; $i < $numMatchesThisRound; $i++) {
                $parentMatch1Id = isset($previousRoundMatches[$i * 2]) ? $previousRoundMatches[$i * 2] : null;
                $parentMatch2Id = isset($previousRoundMatches[$i * 2 + 1]) ? $previousRoundMatches[$i * 2 + 1] : null;
                
                // Skip if both parent matches are null
                if (!$parentMatch1Id && !$parentMatch2Id) {
                    continue;
                }
                
                $matchData = [
                    'division_id' => $division->id,
                    'event_id' => $eventId,
                    'division_type' => 'double_elimination',
                    'bracket_type' => 'upper',
                    'round_number' => $round,
                    'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                    'parent_match1_id' => $parentMatch1Id,
                    'parent_match2_id' => $parentMatch2Id,
                    'scheduled_time' => $division->start_time ? Carbon::parse($division->start_time)->addMinutes(30 * ($round - 1)) : now()->addMinutes(30 * ($round - 1)),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                // Check for auto-advanced athletes from parent matches
                if ($parentMatch1Id) {
                    $parentMatch1 = DB::table('matches')->find($parentMatch1Id);
                    if ($parentMatch1 && $parentMatch1->status === 'completed') {
                        $matchData['player1_id'] = $parentMatch1->winner_id;
                    }
                }
                
                if ($parentMatch2Id) {
                    $parentMatch2 = DB::table('matches')->find($parentMatch2Id);
                    if ($parentMatch2 && $parentMatch2->status === 'completed') {
                        $matchData['player2_id'] = $parentMatch2->winner_id;
                    }
                }
                
                // Auto-advance logic for BYEs
                if ($matchData['player1_id'] && !$matchData['player2_id']) {
                    $matchData['status'] = 'completed';
                    $matchData['winner_id'] = $matchData['player1_id'];
                    $matchData['loser_id'] = null;
                } elseif (!$matchData['player1_id'] && $matchData['player2_id']) {
                    $matchData['status'] = 'completed';
                    $matchData['winner_id'] = $matchData['player2_id'];
                    $matchData['loser_id'] = null;
                }
                
                $matchId = DB::table('matches')->insertGetId($matchData);
                $upperBracketMatches[$round][] = $matchId;
                $matchNumber++;
            }
        }
        
        // Generate lower bracket matches
        $lowerBracketMatches[1] = [];
        $lowerRound = 1;
        
        // First lower bracket round - losers from first upper bracket round
        for ($i = 0; $i < count($upperBracketMatches[1])/2; $i++) {
            $parentMatch1Id = isset($upperBracketMatches[1][$i * 2]) ? $upperBracketMatches[1][$i * 2] : null;
            $parentMatch2Id = isset($upperBracketMatches[1][$i * 2 + 1]) ? $upperBracketMatches[1][$i * 2 + 1] : null;
            
            if ($parentMatch1Id && $parentMatch2Id) {
                $matchData = [
                    'division_id' => $division->id,
                    'event_id' => $eventId,
                    'division_type' => 'double_elimination',
                    'bracket_type' => 'lower',
                    'round_number' => $lowerRound,
                    'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                    'parent_match1_id' => $parentMatch1Id,
                    'parent_match2_id' => $parentMatch2Id,
                    'scheduled_time' => $division->start_time ? Carbon::parse($division->start_time)->addMinutes(30 * ($numRounds + $lowerRound - 1)) : now()->addMinutes(30 * ($numRounds + $lowerRound - 1)),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $matchId = DB::table('matches')->insertGetId($matchData);
                $lowerBracketMatches[$lowerRound][] = $matchId;
                $matchNumber++;
            }
        }
        
        // Generate subsequent lower bracket rounds
        for ($round = 2; $round <= $numRounds - 1; $round++) {
            $lowerBracketMatches[$round] = [];
            $numMatchesThisRound = ceil(count($lowerBracketMatches[$round - 1]) / 2);
            
            for ($i = 0; $i < $numMatchesThisRound; $i++) {
                $parentMatch1Id = isset($lowerBracketMatches[$round - 1][$i * 2]) ? $lowerBracketMatches[$round - 1][$i * 2] : null;
                $parentMatch2Id = isset($upperBracketMatches[$round][$i]) ? $upperBracketMatches[$round][$i] : null;
                
                if ($parentMatch1Id && $parentMatch2Id) {
                    $matchData = [
                        'division_id' => $division->id,
                        'event_id' => $eventId,
                        'division_type' => 'double_elimination',
                        'bracket_type' => 'lower',
                        'round_number' => $round,
                        'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                        'parent_match1_id' => $parentMatch1Id,
                        'parent_match2_id' => $parentMatch2Id,
                        'scheduled_time' => $division->start_time ? Carbon::parse($division->start_time)->addMinutes(30 * ($numRounds + $round - 1)) : now()->addMinutes(30 * ($numRounds + $round - 1)),
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    $matchId = DB::table('matches')->insertGetId($matchData);
                    $lowerBracketMatches[$round][] = $matchId;
                    $matchNumber++;
                }
            }
        }
        
        // Create grand final match
        if (isset($upperBracketMatches[$numRounds][0]) && isset($lowerBracketMatches[$numRounds - 1][0])) {
            DB::table('matches')->insert([
                'division_id' => $division->id,
                'event_id' => $eventId,
                'division_type' => 'double_elimination',
                'bracket_type' => 'final',
                'round_number' => $numRounds + 1,
                'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                'parent_match1_id' => $upperBracketMatches[$numRounds][0],
                'parent_match2_id' => $lowerBracketMatches[$numRounds - 1][0],
                'scheduled_time' => $division->start_time ? Carbon::parse($division->start_time)->addMinutes(30 * ($numRounds * 2)) : now()->addMinutes(30 * ($numRounds * 2)),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return true;
    }

    private function generateRoundRobinMatches($eventId, $division, $athletes)
    {
        $numAthletes = count($athletes);
        $matchNumber = 1;
        
        // Each athlete faces every other athlete once
        for ($i = 0; $i < $numAthletes; $i++) {
            for ($j = $i + 1; $j < $numAthletes; $j++) {
                $matchData = [
                    'division_id' => $division->id,
                    'event_id' => $eventId,
                    'division_type' => 'round_robin',
                    'bracket_type' => null,
                    'round_number' => ceil($matchNumber / floor($numAthletes / 2)),
                    'mat_name' => "Mat {$division->mat_number}-{$matchNumber}",
                    'player1_id' => $athletes[$i]->id,
                    'player2_id' => $athletes[$j]->id,
                    'scheduled_time' => $division->start_time ? Carbon::parse($division->start_time)->addMinutes(30 * ($matchNumber - 1)) : now()->addMinutes(30 * ($matchNumber - 1)),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                DB::table('matches')->insert($matchData);
                $matchNumber++;
            }
        }
        
        return true;
    }

    public function updateMatchResult($eventId, $divisionId, $matchId)
    {
        $match = DB::table('matches')->where('id', $matchId)->first();
        if (!$match) {
            abort(404);
        }

        $winner_id = request('winner_id');
        $loser_id = null;

        // Determine loser based on winner
        if ($winner_id == $match->player1_id) {
            $loser_id = $match->player2_id;
        } elseif ($winner_id == $match->player2_id) {
            $loser_id = $match->player1_id;
        }

        // Update match result
        DB::table('matches')
            ->where('id', $matchId)
            ->update([
                'winner_id' => $winner_id,
                'loser_id' => $loser_id,
                'status' => 'completed',
                'updated_at' => now()
            ]);

        // Update child matches
        $this->updateChildMatches($match);

        return redirect()->back()->with('success', 'Match result updated successfully');
    }

    private function updateChildMatches($parentMatch)
    {
        // Find matches where this match is parent1 or parent2
        $childMatches = DB::table('matches')
            ->where('parent_match1_id', $parentMatch->id)
            ->orWhere('parent_match2_id', $parentMatch->id)
            ->get();

        foreach ($childMatches as $childMatch) {
            $updateData = [];

            // Update player1 if this is parent1
            if ($childMatch->parent_match1_id == $parentMatch->id) {
                $updateData['player1_id'] = $parentMatch->winner_id;
            }

            // Update player2 if this is parent2
            if ($childMatch->parent_match2_id == $parentMatch->id) {
                $updateData['player2_id'] = $parentMatch->winner_id;
            }

            // If both players are set and one is null (BYE), auto-advance the other player
            if (isset($updateData['player1_id']) && $childMatch->player2_id === null) {
                $updateData['winner_id'] = $updateData['player1_id'];
                $updateData['status'] = 'completed';
            } elseif (isset($updateData['player2_id']) && $childMatch->player1_id === null) {
                $updateData['winner_id'] = $updateData['player2_id'];
                $updateData['status'] = 'completed';
            }

            if (!empty($updateData)) {
                DB::table('matches')
                    ->where('id', $childMatch->id)
                    ->update($updateData);

                // If the child match was auto-completed, update its children too
                if (isset($updateData['status']) && $updateData['status'] === 'completed') {
                    $this->updateChildMatches($childMatch);
                }
            }
        }
    }

    public function showDivision($eventId, $divisionId)
    {
        $event = Event::findOrFail($eventId);
        $division = $event->divisions()->findOrFail($divisionId);
        
        // Get division details with participant count
        $athletesCount = \App\Models\CheckoutDetail::where('event_id', $eventId)
            ->where('division_id', $divisionId)
            ->where('payment_status', 'completed')
            ->whereNotNull('profile_id')
            ->distinct('profile_id')
            ->count('profile_id');

        $divisionData = [
            'id' => $division->id,
            'event_id' => $eventId,
            'name' => $division->name,
            'min_age' => $division->min_age,
            'max_age' => $division->max_age,
            'min_weight' => $division->min_weight,
            'max_weight' => $division->max_weight,
            'belt_level' => $division->belt_level,
            'gender' => $division->gender,
            'bracket_type' => $division->bracket_type,
            'start_time' => $division->start_time,
            'mat_number' => $division->mat_number,
            'participants_count' => $athletesCount
        ];

        // Get athletes
        $athletes = \App\Models\CheckoutDetail::where('event_id', $eventId)
            ->where('division_id', $divisionId)
            ->where('payment_status', 'completed')
            ->with(['profile' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'weight', 'age', 'nationality', 'user_id', 'passport_image_path')
                    ->with(['user:id,email']);
            }])
            ->get()
            ->map(function ($checkout) {
                $profile = $checkout->profile;
                return [
                    'id' => $profile->id,
                    'name' => $profile->first_name . ' ' . $profile->last_name,
                    'email' => $profile->user->email,
                    'weight' => $profile->weight,
                    'age' => $profile->age,
                    'nationality' => $profile->nationality,
                    'academy' => $checkout->academy_name,
                    'avatar' => $profile->passport_image_path
                ];
            });

        // Get matches
        $matches = GameMatch::with(['division', 'player1', 'player2'])
            ->where('event_id', $eventId)
            ->where('division_id', $divisionId)
            ->get()
            ->map(function($match) {
                return [
                    'id' => $match->id,
                    'round_type' => $match->round_type,
                    'round_number' => $match->round_number,
                    'match_number' => $match->match_number,
                    'mat_name' => $match->mat_name,
                    'status' => $match->status,
                    'player1' => $match->player1 ? [
                        'id' => $match->player1->id,
                        'name' => $match->player1->first_name . ' ' . $match->player1->last_name,
                    ] : null,
                    'player2' => $match->player2 ? [
                        'id' => $match->player2->id,
                        'name' => $match->player2->first_name . ' ' . $match->player2->last_name,
                    ] : null,
                    'winner_id' => $match->winner_id,
                    'scheduled_time' => $match->scheduled_time,
                ];
            });

        return Inertia::render('Admin/Brackets/DivisionDetails', [
            'division' => $divisionData,
            'athletes' => $athletes,
            'matches' => $matches
        ]);
    }

    public function createMatch(Request $request, $eventId, $divisionId)
    {
        $request->validate([
            'round_type' => 'required|string|in:semifinal,bronze,final',
            'mat_name' => 'required|string',
            'athlete1_id' => 'required|exists:profiles,id',
            'athlete2_id' => 'required|exists:profiles,id|different:athlete1_id',
            'scheduled_time' => 'required|date'
        ]);

        // Get division to determine bracket type
        $division = EventDivision::findOrFail($divisionId);

        // Map round types to numbers based on bracket type
        $roundNumber = match ($request->round_type) {
            'semifinal' => 1,
            'bronze' => 2,
            'final' => $division->bracket_type === 'single_elimination' ? 3 : 2,
            default => 1
        };

        // Determine bracket type based on round type
        $bracketType = match ($request->round_type) {
            'bronze' => 'bronze',
            'final' => 'final',
            default => 'upper'
        };

        // Get the next match number for this division
        $lastMatch = GameMatch::where('division_id', $divisionId)
            ->orderBy('match_number', 'desc')
            ->first();
        $matchNumber = $lastMatch ? $lastMatch->match_number + 1 : 1;

        $match = GameMatch::create([
            'event_id' => $eventId,
            'division_id' => $divisionId,
            'division_type' => $division->bracket_type,
            'bracket_type' => $bracketType,
            'round_type' => $request->round_type,
            'round_number' => $roundNumber,
            'match_number' => $matchNumber,
            'mat_name' => $request->mat_name,
            'player1_id' => $request->athlete1_id,
            'player2_id' => $request->athlete2_id,
            'scheduled_time' => $request->scheduled_time,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Match created successfully');
    }

    public function deleteMatch($eventId, $divisionId, $matchId)
    {
        $match = GameMatch::findOrFail($matchId);
        $match->delete();

        return back()->with('success', 'Match deleted successfully');
    }

    public function updateMatchStatus(Request $request, $eventId, $divisionId, $matchId)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $match = GameMatch::findOrFail($matchId);
        $match->update(['status' => $request->status]);

        return back()->with('success', 'Match status updated successfully');
    }

    public function recordMatchResult(Request $request, $eventId, $divisionId, $matchId)
    {
        $request->validate([
            'winner_id' => 'required|exists:profiles,id',
            'score_details' => 'nullable|string'
        ]);

        $match = GameMatch::findOrFail($matchId);
        
        // Set winner and loser
        $match->winner_id = $request->winner_id;
        $match->loser_id = $match->player1_id == $request->winner_id ? $match->player2_id : $match->player1_id;
        $match->status = 'completed';
        $match->score_details = $request->score_details;
        $match->save();

        return back()->with('success', 'Match result recorded successfully');
    }
} 