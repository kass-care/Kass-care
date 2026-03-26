<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FacilityProviderCycle extends Model
{
    protected $fillable = [
        'facility_id',
        'provider_id',
        'review_interval_days',
        'last_completed_at',
        'next_due_at',
        'scheduled_for',
        'completed_for_cycle_at',
        'status',
        'priority',
        'notes',
    ];

    protected $casts = [
        'last_completed_at' => 'datetime',
        'next_due_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'completed_for_cycle_at' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(\App\Models\Facility::class, 'facility_id');
    }

    public function provider()
    {
        return $this->belongsTo(\App\Models\User::class, 'provider_id');
    }

    public function getComputedStatusAttribute()
    {
        $today = Carbon::today();

        if ($this->completed_for_cycle_at) {
            return 'completed';
        }

        if ($this->scheduled_for) {
            return 'scheduled';
        }

        if (!$this->next_due_at) {
            return 'current';
        }

        if ($today->gt($this->next_due_at)) {
            return 'overdue';
        }

        if ($today->eq($this->next_due_at)) {
            return 'due';
        }

        if ($today->diffInDays($this->next_due_at, false) <= 3) {
            return 'due_soon';
        }

        return 'current';
    }
}
