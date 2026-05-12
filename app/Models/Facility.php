<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    protected $fillable = [
    'name',
    'email',
    'phone',
    'address',
    'contact_person',
    'plan',
    'subscription_status',
    'subscription_starts_at',
    'subscription_ends_at',
    'trial_ends_at',
    'facility_limit',
    'accepted_terms',
    'accepted_terms_at',
    'organization_id',
    'provider_id',
    'visit_frequency_days',
    'last_visit',
    'next_visit',
    'stripe_id',
    'is_active',
];

      protected $casts = [
    'is_active' => 'boolean',
    'accepted_terms' => 'boolean',
    'subscription_starts_at' => 'datetime',
    'subscription_ends_at' => 'datetime',
    'trial_ends_at' => 'datetime',
    'accepted_terms_at' => 'datetime',
    'last_visit' => 'date',
    'next_visit' => 'date',
];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function caregivers()
    {
        return $this->hasMany(Caregiver::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
public function providers()
{
    return $this->belongsToMany(User::class, 'facility_provider', 'facility_id', 'provider_id')
        ->withTimestamps();
}
}
