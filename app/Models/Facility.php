<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'location',
        'plan',
        'subscription_status',
        'provider_limit',
        'caregiver_limit',
        'subscription_starts_at',
        'subscription_ends_at',
        'stripe_id',
        'stripe_subscription_id',
    ];

    protected $casts = [
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function providers()
    {
        return $this->hasMany(User::class)->where('role', 'provider');
    }

    public function caregivers()
    {
        return $this->hasMany(User::class)->where('role', 'caregiver');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
