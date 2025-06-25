<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class TournamentDivision extends Model
{
    use HasFactory;

    protected $table = 'tournament_divisions';

    protected $fillable = [
        'event_id',
        'name',
        'bracket_type',
        'min_weight',
        'max_weight',
        'min_age',
        'max_age',
        'gender',
        'belt_level',
        'status',
        'created_by'
    ];

    protected $casts = [
        'min_weight' => 'float',
        'max_weight' => 'float',
        'min_age' => 'integer',
        'max_age' => 'integer'
    ];

    // Bracket type constants
    const BRACKET_SINGLE_ELIMINATION = 'single_elimination';
    const BRACKET_DOUBLE_ELIMINATION = 'double_elimination';
    const BRACKET_ROUND_ROBIN = 'round_robin';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class, 'division_id');
    }

    public function athletes()
    {
        return $this->belongsToMany(Profile::class, 'tournament_division_athletes')
            ->withPivot(['status', 'registration_date'])
            ->withTimestamps();
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function getWeightRangeAttribute()
    {
        if ($this->min_weight === null && $this->max_weight === null) {
            return 'Open Weight';
        }

        if ($this->min_weight === null) {
            return "Up to {$this->max_weight}kg";
        }

        if ($this->max_weight === null) {
            return "{$this->min_weight}kg+";
        }

        return "{$this->min_weight}kg - {$this->max_weight}kg";
    }

    public function getAgeRangeAttribute()
    {
        if ($this->min_age === null && $this->max_age === null) {
            return 'All Ages';
        }

        if ($this->min_age === null) {
            return "Up to {$this->max_age} years";
        }

        if ($this->max_age === null) {
            return "{$this->min_age} years+";
        }

        return "{$this->min_age} - {$this->max_age} years";
    }

    public function getFullNameAttribute()
    {
        $parts = [];

        // Add weight class
        $parts[] = $this->weight_range;

        // Add age range if specified
        if ($this->min_age !== null || $this->max_age !== null) {
            $parts[] = $this->age_range;
        }

        // Add gender if specified
        if ($this->gender) {
            $parts[] = ucfirst($this->gender);
        }

        // Add belt level if specified
        if ($this->belt_level) {
            $parts[] = ucwords(str_replace('_', ' ', $this->belt_level)) . ' Belt';
        }

        return implode(' • ', $parts);
    }

    public function validateAthlete(Profile $athlete)
    {
        $errors = [];

        // Validate weight
        if ($this->min_weight !== null && $athlete->weight < $this->min_weight) {
            $errors[] = "Athlete is below the minimum weight requirement";
        }
        if ($this->max_weight !== null && $athlete->weight > $this->max_weight) {
            $errors[] = "Athlete is above the maximum weight requirement";
        }

        // Validate age
        if ($this->min_age !== null && $athlete->age < $this->min_age) {
            $errors[] = "Athlete is below the minimum age requirement";
        }
        if ($this->max_age !== null && $athlete->age > $this->max_age) {
            $errors[] = "Athlete is above the maximum age requirement";
        }

        // Validate gender
        if ($this->gender && $athlete->gender !== $this->gender) {
            $errors[] = "Athlete's gender does not match division requirements";
        }

        // Validate belt level
        if ($this->belt_level && $athlete->belt_level !== $this->belt_level) {
            $errors[] = "Athlete's belt level does not match division requirements";
        }

        return $errors;
    }

    public function canGenerateBrackets()
    {
        // Check if we have enough athletes
        $athleteCount = $this->athletes()->where('status', 'checked_in')->count();
        
        if ($athleteCount < 2) {
            return false;
        }

        // For single/double elimination, we prefer power of 2 numbers
        if (in_array($this->bracket_type, [self::BRACKET_SINGLE_ELIMINATION, self::BRACKET_DOUBLE_ELIMINATION])) {
            // Allow if we have at least 2 athletes
            return true;
        }

        // For round robin, we need at least 3 athletes
        if ($this->bracket_type === self::BRACKET_ROUND_ROBIN) {
            return $athleteCount >= 3;
        }

        return true;
    }

    public function generateBrackets()
    {
        if (!$this->canGenerateBrackets()) {
            throw new \Exception('Cannot generate brackets: insufficient number of athletes');
        }

        // Get checked-in athletes
        $athletes = $this->athletes()
            ->where('status', 'checked_in')
            ->inRandomOrder() // Randomize seeding
            ->get();

        // Clear existing matches
        $this->matches()->delete();

        switch ($this->bracket_type) {
            case self::BRACKET_SINGLE_ELIMINATION:
                return $this->generateSingleEliminationBracket($athletes);
            case self::BRACKET_DOUBLE_ELIMINATION:
                return $this->generateDoubleEliminationBracket($athletes);
            case self::BRACKET_ROUND_ROBIN:
                return $this->generateRoundRobinBracket($athletes);
            default:
                throw new \Exception('Invalid bracket type');
        }
    }

    protected function generateSingleEliminationBracket($athletes)
    {
        $athleteCount = $athletes->count();
        $rounds = ceil(log($athleteCount, 2));
        $totalMatches = pow(2, $rounds) - 1;
        $firstRoundMatches = pow(2, $rounds - 1);
        $byes = pow(2, $rounds) - $athleteCount;

        $matches = [];
        $matchNumber = 1;

        // Create first round matches
        for ($i = 0; $i < $firstRoundMatches; $i++) {
            $competitor1 = $athletes[$i] ?? null;
            $competitor2 = $athletes[$athleteCount - 1 - $i] ?? null;

            // Skip creating matches for byes
            if ($i >= ($firstRoundMatches - $byes) && !$competitor2) {
                continue;
            }

            $match = new TournamentMatch([
                'event_id' => $this->event_id,
                'division_id' => $this->id,
                'round_type' => $i < $firstRoundMatches / 2 ? TournamentMatch::ROUND_QUARTERFINAL : TournamentMatch::ROUND_SEMIFINAL,
                'round_number' => 1,
                'match_number' => $matchNumber++,
                'competitor_1_id' => $competitor1?->id,
                'competitor_2_id' => $competitor2?->id,
                'status' => $competitor2 ? TournamentMatch::STATUS_PENDING : TournamentMatch::STATUS_WALKOVER,
                'created_by' => Auth::id(),
            ]);

            if (!$competitor2) {
                $match->winner_id = $competitor1->id;
                $match->loser_id = null;
            }

            $match->save();
            $matches[] = $match;
        }

        // Create subsequent rounds
        $currentRoundMatches = $matches;
        for ($round = 2; $round <= $rounds; $round++) {
            $nextRoundMatches = [];
            $matchesInRound = pow(2, $rounds - $round);

            for ($i = 0; $i < $matchesInRound; $i++) {
                $match = new TournamentMatch([
                    'event_id' => $this->event_id,
                    'division_id' => $this->id,
                    'round_type' => $round === $rounds ? TournamentMatch::ROUND_FINAL : TournamentMatch::ROUND_SEMIFINAL,
                    'round_number' => $round,
                    'match_number' => $matchNumber++,
                    'parent_match1_id' => $currentRoundMatches[$i * 2]->id,
                    'parent_match2_id' => $currentRoundMatches[$i * 2 + 1]->id,
                    'status' => TournamentMatch::STATUS_PENDING,
                    'created_by' => Auth::id(),
                ]);

                $match->save();
                $nextRoundMatches[] = $match;
            }

            $currentRoundMatches = $nextRoundMatches;
        }

        // Create bronze medal match if we have enough competitors
        if ($athleteCount > 3) {
            $semifinalMatches = $this->matches()
                ->where('round_type', TournamentMatch::ROUND_SEMIFINAL)
                ->orderBy('match_number')
                ->take(2)
                ->get();

            if ($semifinalMatches->count() === 2) {
                $bronzeMatch = new TournamentMatch([
                    'event_id' => $this->event_id,
                    'division_id' => $this->id,
                    'round_type' => TournamentMatch::ROUND_BRONZE,
                    'round_number' => $rounds,
                    'match_number' => $matchNumber,
                    'status' => TournamentMatch::STATUS_PENDING,
                    'created_by' => Auth::id(),
                ]);

                $bronzeMatch->save();
            }
        }

        return $matches;
    }

    protected function generateDoubleEliminationBracket($athletes)
    {
        $athleteCount = $athletes->count();
        $rounds = ceil(log($athleteCount, 2));
        $matchNumber = 1;

        // Generate winners bracket first (similar to single elimination)
        $winnersBracketMatches = [];
        $firstRoundMatches = pow(2, $rounds - 1);
        $byes = pow(2, $rounds) - $athleteCount;

        // Create first round matches in winners bracket
        for ($i = 0; $i < $firstRoundMatches; $i++) {
            $competitor1 = $athletes[$i] ?? null;
            $competitor2 = $athletes[$athleteCount - 1 - $i] ?? null;

            if ($i >= ($firstRoundMatches - $byes) && !$competitor2) {
                continue;
            }

            $match = new TournamentMatch([
                'event_id' => $this->event_id,
                'division_id' => $this->id,
                'round_type' => TournamentMatch::ROUND_WINNERS . '1',
                'round_number' => 1,
                'match_number' => $matchNumber++,
                'competitor_1_id' => $competitor1?->id,
                'competitor_2_id' => $competitor2?->id,
                'status' => $competitor2 ? TournamentMatch::STATUS_PENDING : TournamentMatch::STATUS_WALKOVER,
                'created_by' => Auth::id(),
            ]);

            if (!$competitor2) {
                $match->winner_id = $competitor1->id;
                $match->loser_id = null;
            }

            $match->save();
            $winnersBracketMatches[] = $match;
        }

        // Create subsequent rounds in winners bracket
        $currentRoundMatches = $winnersBracketMatches;
        for ($round = 2; $round <= $rounds; $round++) {
            $nextRoundMatches = [];
            $matchesInRound = pow(2, $rounds - $round);

            for ($i = 0; $i < $matchesInRound; $i++) {
                $match = new TournamentMatch([
                    'event_id' => $this->event_id,
                    'division_id' => $this->id,
                    'round_type' => TournamentMatch::ROUND_WINNERS . $round,
                    'round_number' => $round,
                    'match_number' => $matchNumber++,
                    'parent_match1_id' => $currentRoundMatches[$i * 2]->id,
                    'parent_match2_id' => $currentRoundMatches[$i * 2 + 1]->id,
                    'status' => TournamentMatch::STATUS_PENDING,
                    'created_by' => Auth::id(),
                ]);

                $match->save();
                $nextRoundMatches[] = $match;
            }

            $currentRoundMatches = $nextRoundMatches;
        }

        // Generate losers bracket
        $losersBracketMatches = [];
        $loserRound = 1;

        // Create first round of losers bracket from first round losers
        $firstRoundLosers = array_slice($winnersBracketMatches, 0, $firstRoundMatches);
        for ($i = 0; $i < count($firstRoundLosers); $i += 2) {
            if (!isset($firstRoundLosers[$i + 1])) {
                continue;
            }

            $match = new TournamentMatch([
                'event_id' => $this->event_id,
                'division_id' => $this->id,
                'round_type' => TournamentMatch::ROUND_LOSERS . $loserRound,
                'round_number' => $loserRound,
                'match_number' => $matchNumber++,
                'status' => TournamentMatch::STATUS_PENDING,
                'created_by' => Auth::id(),
            ]);

            $match->save();
            $losersBracketMatches[] = $match;
        }

        // Create subsequent rounds in losers bracket
        $currentLosersMatches = $losersBracketMatches;
        $loserRound++;

        while (count($currentLosersMatches) > 1) {
            $nextRoundMatches = [];

            for ($i = 0; $i < count($currentLosersMatches); $i += 2) {
                if (!isset($currentLosersMatches[$i + 1])) {
                    continue;
                }

                $match = new TournamentMatch([
                    'event_id' => $this->event_id,
                    'division_id' => $this->id,
                    'round_type' => TournamentMatch::ROUND_LOSERS . $loserRound,
                    'round_number' => $loserRound,
                    'match_number' => $matchNumber++,
                    'parent_match1_id' => $currentLosersMatches[$i]->id,
                    'parent_match2_id' => $currentLosersMatches[$i + 1]->id,
                    'status' => TournamentMatch::STATUS_PENDING,
                    'created_by' => Auth::id(),
                ]);

                $match->save();
                $nextRoundMatches[] = $match;
            }

            $currentLosersMatches = $nextRoundMatches;
            $loserRound++;
        }

        // Create grand final match
        $grandFinal = new TournamentMatch([
            'event_id' => $this->event_id,
            'division_id' => $this->id,
            'round_type' => TournamentMatch::ROUND_GRAND_FINAL,
            'round_number' => $rounds + 1,
            'match_number' => $matchNumber,
            'parent_match1_id' => end($currentRoundMatches)->id,
            'parent_match2_id' => end($currentLosersMatches)->id,
            'status' => TournamentMatch::STATUS_PENDING,
            'created_by' => Auth::id(),
        ]);

        $grandFinal->save();

        return array_merge($winnersBracketMatches, $losersBracketMatches, [$grandFinal]);
    }

    protected function generateRoundRobinBracket($athletes)
    {
        $athleteCount = $athletes->count();
        $matchNumber = 1;
        $matches = [];

        // Each athlete plays against every other athlete once
        for ($i = 0; $i < $athleteCount; $i++) {
            for ($j = $i + 1; $j < $athleteCount; $j++) {
                $match = new TournamentMatch([
                    'event_id' => $this->event_id,
                    'division_id' => $this->id,
                    'round_type' => 'round_robin',
                    'round_number' => ceil($matchNumber / ($athleteCount / 2)),
                    'match_number' => $matchNumber++,
                    'competitor_1_id' => $athletes[$i]->id,
                    'competitor_2_id' => $athletes[$j]->id,
                    'status' => TournamentMatch::STATUS_PENDING,
                    'created_by' => Auth::id(),
                ]);

                $match->save();
                $matches[] = $match;
            }
        }

        return $matches;
    }
} 