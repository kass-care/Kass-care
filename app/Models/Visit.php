<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'client_id',
        'caregiver_id',
        'facility_id',
        'provider_id',
        'visit_date',
        'start_time',
        'end_time',
        'check_in_time',
        'check_out_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'status',
        'started_at',
        'ended_at',
        'visit_started',
        'visit_completed',
        'activity',
        'notes',
        'visit_started_at',
        'visit_completed_at',
        'visit_time',
        'duration_minutes',
        'adls',
        'vitals',
        'check_in',
        'check_out',
        'is_rounded',
        'rounded_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visit_started' => 'boolean',
        'visit_completed' => 'boolean',
        'adls' => 'array',
        'vitals' => 'array',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'visit_started_at' => 'datetime',
        'visit_completed_at' => 'datetime',
        'rounded_at' => 'datetime',
        'is_rounded' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($visit) {
            if (Auth::check() && empty($visit->facility_id)) {
                $visit->facility_id = Auth::user()->facility_id;
            }
        });

        static::addGlobalScope('facility', function ($query) {
            if (Auth::check() && Auth::user()->role !== 'super_admin') {
                if (Auth::user()->facility_id) {
                    $query->where('facility_id', Auth::user()->facility_id);
                }
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'client_id');
    }

    public function caregiver(): BelongsTo
    {
        return $this->belongsTo(Caregiver::class, 'caregiver_id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function careLogs(): HasMany
    {
        return $this->hasMany(CareLog::class, 'visit_id');
    }

    public function providerNote(): HasOne
    {
        return $this->hasOne(ProviderNote::class, 'visit_id');
    }
}
