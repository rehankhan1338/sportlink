<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliation extends Model
{
    use HasFactory;

    protected $table = 'affiliations';

    protected $fillable = [
        'name',
        'email',
        'country',
        'city',
        'address',
        'location',
        'logo',
        'cover_image',
        'description',
        'website',
        'phone',
        'status'
    ];

    // Add accessor for image URLs
    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? "/storage/affiliations/logos/" . basename($value) : null,
        );
    }

    protected function coverImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? "/storage/affiliations/covers/" . basename($value) : null,
        );
    }

    public function academies(): HasMany
    {
        return $this->hasMany(Academy::class);
    }
} 