<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'facility_id',
        'plan',
        'subscription_status',
        'facility_limit',
        'subscription_starts_at',
        'subscription_ends_at',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isProvider()
    {
        return $this->role === 'provider';
    }

    public function isCaregiver()
    {
        return $this->role === 'caregiver';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function facility()
    {
        return $this->belongsTo(\App\Models\Facility::class);
    }
}
