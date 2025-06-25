<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Academy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'country',
        'city',
        'address',
        'latitude',
        'longitude',
        'person_in_charge',
        'email',
        'phone',
        'website',
        'about',
        'logo',
        'cover_image',
        'affiliation'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function affiliation(): BelongsTo
    {
        return $this->belongsTo(Affiliation::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 