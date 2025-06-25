<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'weight',
        'height',
        'date_of_birth',
        'age',
        'gender',
        'nationality',
        'country_of_residence',
        'phone',
        'address',
        'passport_image_path',
        'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
} 