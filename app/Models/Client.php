<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
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
        'photo',

        'allergies',
        'psychiatrist',
        'cardiologist',
        'primary_care_provider',
        'pharmacy',
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
                $client->facility_id = session('facility_id') ?? Auth::user()->facility_id;
            }
        });

        static::addGlobalScope('facility', function ($query) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->role === 'super_admin') {
                $selectedFacilityId = session('facility_id');

                if (!empty($selectedFacilityId)) {
                    $query->where('facility_id', $selectedFacilityId);
                }

                return;
            }

            if ($user->role === 'provider') {
                $selectedFacilityId = session('facility_id') ?? $user->facility_id;

                if (!empty($selectedFacilityId)) {
                    $query->where('facility_id', $selectedFacilityId);
                }

                return;
            }

            if (!empty($user->facility_id)) {
                $query->where('facility_id', $user->facility_id);
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
        return $this->hasMany(Visit::class, 'client_id');
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
        return $this->hasMany(CareLog::class, 'client_id');
    }

    public function documents()
    {
        return $this->hasMany(PatientDocument::class, 'patient_id', 'id');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Patient');
    }
}
