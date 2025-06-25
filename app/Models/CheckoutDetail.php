<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckoutDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'profile_id',
        'amount',
        'user_type',
        'payment_status',
        'stripe_session_id',
        'payment_details',
        'academy_id',
        'academy_name',
        'division_id'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'academy_id' => 'integer',
        'amount' => 'decimal:2'
    ];

    protected $attributes = [
        'academy_id' => 0
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function academy()
    {
        return $this->belongsTo(Academy::class)
            ->where('id', '!=', 0)
            ->withDefault();
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
