<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BracketMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'event_id',
        'division_id',
        'round_number',
        'match_number',
        'player1_id',
        'player2_id',
        'winner_id',
        'mat_name',
        'scheduled_time',
        'status',
        'score_details',
        'division_type',
        'bracket_type'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'score_details' => 'array'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function division()
    {
        return $this->belongsTo(EventDivision::class, 'division_id');
    }

    public function player1()
    {
        return $this->belongsTo(Profile::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Profile::class, 'player2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Profile::class, 'winner_id');
    }
} 