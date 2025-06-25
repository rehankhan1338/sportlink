<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDivision extends Model
{
    use HasFactory;

    protected $table = 'event_to_divisions';

    protected $fillable = [
        'event_id',
        'name',
        'gender',
        'min_age',
        'max_age',
        'min_weight',
        'max_weight',
        'belt_level',
        'bracket_type',
        'match_duration_min',
        'start_time',
        'mat_number'
    ];

    protected $casts = [
        'min_age' => 'integer',
        'max_age' => 'integer',
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'match_duration_min' => 'integer',
        'start_time' => 'datetime'
    ];

    /**
     * Get the event that owns the division.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the matches for this division.
     */
    public function matches()
    {
        return $this->hasMany(BracketMatch::class, 'division_id');
    }

    /**
     * Get the checkout details (registered athletes) for this division.
     */
    public function checkoutDetails()
    {
        return $this->hasMany(CheckoutDetail::class, 'division_id');
    }
} 