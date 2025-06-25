<?php

namespace App\Models\Traits;

use App\Models\Profile;
use Illuminate\Validation\ValidationException;

trait ValidatesMatches
{
    protected function validateMatchParticipants($player1Id, $player2Id)
    {
        // Prevent same athlete matchup
        if ($player1Id === $player2Id) {
            throw ValidationException::withMessages([
                'player2_id' => ['An athlete cannot compete against themselves']
            ]);
        }

        // Validate both athletes exist
        $player1 = Profile::find($player1Id);
        $player2 = Profile::find($player2Id);

        if (!$player1 || !$player2) {
            throw ValidationException::withMessages([
                'players' => ['One or both athletes do not exist']
            ]);
        }

        // Validate weight class compliance
        $this->validateWeightClass($player1, $player2);

        // Validate division compliance
        $this->validateDivisionCompliance($player1, $player2);
    }

    protected function validateWeightClass($player1, $player2)
    {
        $division = $this->division;
        
        // Check weight class limits if defined
        if ($division->min_weight !== null || $division->max_weight !== null) {
            foreach ([$player1, $player2] as $player) {
                if ($division->min_weight !== null && $player->weight < $division->min_weight) {
                    throw ValidationException::withMessages([
                        'weight' => ["Athlete {$player->name} is below the minimum weight for this division"]
                    ]);
                }
                if ($division->max_weight !== null && $player->weight > $division->max_weight) {
                    throw ValidationException::withMessages([
                        'weight' => ["Athlete {$player->name} is above the maximum weight for this division"]
                    ]);
                }
            }
        }
    }

    protected function validateDivisionCompliance($player1, $player2)
    {
        $division = $this->division;

        // Check age limits if defined
        if ($division->min_age !== null || $division->max_age !== null) {
            foreach ([$player1, $player2] as $player) {
                if ($division->min_age !== null && $player->age < $division->min_age) {
                    throw ValidationException::withMessages([
                        'age' => ["Athlete {$player->name} is below the minimum age for this division"]
                    ]);
                }
                if ($division->max_age !== null && $player->age > $division->max_age) {
                    throw ValidationException::withMessages([
                        'age' => ["Athlete {$player->name} is above the maximum age for this division"]
                    ]);
                }
            }
        }

        // Check gender if specified
        if ($division->gender) {
            foreach ([$player1, $player2] as $player) {
                if ($player->gender !== $division->gender) {
                    throw ValidationException::withMessages([
                        'gender' => ["Athlete {$player->name} does not match the division's gender requirement"]
                    ]);
                }
            }
        }

        // Check belt level if specified
        if ($division->belt_level) {
            foreach ([$player1, $player2] as $player) {
                if ($player->belt_level !== $division->belt_level) {
                    throw ValidationException::withMessages([
                        'belt_level' => ["Athlete {$player->name} does not match the division's belt level requirement"]
                    ]);
                }
            }
        }
    }

    protected function validateMatchEditable()
    {
        // Check if match is completed or walkover
        if (in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_WALKOVER])) {
            throw ValidationException::withMessages([
                'status' => ['Completed or walkover matches cannot be edited']
            ]);
        }

        // Check if any dependent matches have results
        if ($this->childMatches()->whereNotNull('winner_id')->exists()) {
            throw ValidationException::withMessages([
                'dependent_matches' => ['Cannot edit match as dependent matches have already been completed']
            ]);
        }
    }
} 