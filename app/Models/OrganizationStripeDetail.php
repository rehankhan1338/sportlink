<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationStripeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_type',
        'business_name',
        'business_tax_id',
        'account_holder_name',
        'account_number',
        'routing_number',
        'bank_account_type',
        'address_line1',
        'address_city',
        'address_state',
        'address_postal_code',
        'address_country',
        'email',
        'phone',
        'stripe_account_id',
        'stripe_account_status',
        'stripe_account_requirements',
        'is_verified'
    ];

    protected $casts = [
        'stripe_account_requirements' => 'array',
        'is_verified' => 'boolean'
    ];

    /**
     * Get the organization that owns the stripe details.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
