<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $table = 'events_registered';

    protected $fillable = [
        'event_id',
        'user_id',
        'organization_id',
        'profile_id',
        'email',
        'weight',
        'age',
        'gender',
        'nationality',
        'date_of_birth',
        'phone',
        'address',
        'country_of_residence',
        'height',
        'passport_image_path',
        'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
} 