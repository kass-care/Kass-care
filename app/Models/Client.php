<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'facility_id',
        'provider_id',
        'room',
        'uuid',
        'age',
        'date_of_birth',
        'gender',
        'height',
        'weight',
        'bmi',
        'chief_complaint',
        'medical_history',
        'family_history',
        'social_history',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'bmi' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($client) {
            if (Auth::check() && empty($client->facility_id)) {
                $client->facility_id = Auth::user()->facility_id;
            }
        });

        static::addGlobalScope('facility', function ($query) {
            if (
                Auth::check() &&
                Auth::user()->role !== 'super_admin' &&
                Auth::user()->facility_id
            ) {
                $query->where('facility_id', Auth::user()->facility_id);
            }
        });
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function latestVisit()
    {
        return $this->hasOne(Visit::class, 'client_id')->latestOfMany();
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function careLogs()
    {
        return $this->hasMany(CareLog::class);
    }
public function documents()
{
    return $this->hasMany(\App\Models\PatientDocument::class, 'patient_id', 'id');
}
}
