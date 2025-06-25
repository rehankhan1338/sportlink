<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'location',
        'country',
        'start_date',
        'end_date',
        'last_date_of_registration',
        'timezone',
        'status',
        'visibility',
        'description',
        'image',
        'adult_price',
        'minor_price',
        'children_price',
        'created_by',
        'organization_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['image_url'];

    protected $with = ['divisions'];

    /**
     * Relationship: Event created by user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Optional: If you have a Country model
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Optional: If you have a Game model
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Relationship: Event divisions
     */
    public function divisions()
    {
        return $this->hasMany(EventDivision::class);
    }

    /**
     * Relationship: Organization that owns this event
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
