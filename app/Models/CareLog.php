<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CareLog extends Model
{
    protected $fillable = [
        'visit_id',
        'notes',
        'adls',
        'vitals',
        'reviewed',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'adls' => 'array',
        'vitals' => 'array',
        'reviewed' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('facility', function (Builder $query) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->role === 'super_admin') {
                $selectedFacilityId = session('facility_id');

                if (!empty($selectedFacilityId)) {
                    $query->whereHas('visit', function (Builder $visitQuery) use ($selectedFacilityId) {
                        $visitQuery->where('facility_id', $selectedFacilityId);
                    });
                }

                return;
            }

            if ($user->role === 'provider') {
                $selectedFacilityId = session('facility_id') ?? $user->facility_id;

                if (!empty($selectedFacilityId)) {
                    $query->whereHas('visit', function (Builder $visitQuery) use ($selectedFacilityId) {
                        $visitQuery->where('facility_id', $selectedFacilityId);
                    });
                }

                return;
            }

            if (!empty($user->facility_id)) {
                $query->whereHas('visit', function (Builder $visitQuery) use ($user) {
                    $visitQuery->where('facility_id', $user->facility_id);
                });
            }
        });

        static::created(function (CareLog $careLog) {
            $careLog->generateVitalAlerts();
        });

        static::updated(function (CareLog $careLog) {
            $careLog->generateVitalAlerts();
        });
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Visit::class,
            'id',
            'id',
            'visit_id',
            'client_id'
        );
    }

    public function caregiver()
    {
        return $this->hasOneThrough(
            Caregiver::class,
            Visit::class,
            'id',
            'id',
            'visit_id',
            'caregiver_id'
        );
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getBloodPressureAttribute(): ?string
    {
        $vitals = is_array($this->vitals) ? $this->vitals : [];
        return $vitals['blood_pressure'] ?? $vitals['bp'] ?? null;
    }

    public function getPulseAttribute(): ?string
    {
        $vitals = is_array($this->vitals) ? $this->vitals : [];
        return $vitals['pulse'] ?? null;
    }

    public function getTemperatureAttribute(): ?string
    {
        $vitals = is_array($this->vitals) ? $this->vitals : [];
        return $vitals['temperature'] ?? $vitals['temp'] ?? null;
    }

    public function getOxygenSaturationAttribute(): ?string
    {
        $vitals = is_array($this->vitals) ? $this->vitals : [];
        return $vitals['oxygen_saturation'] ?? $vitals['oxygen'] ?? null;
    }

    public function getRespiratoryRateAttribute(): ?string
    {
        $vitals = is_array($this->vitals) ? $this->vitals : [];
        return $vitals['respiratory_rate'] ?? null;
    }

    public function generateVitalAlerts(): void
    {
        $visit = $this->visit;

        if (!$visit || !is_array($this->vitals)) {
            return;
        }

        $vitals = $this->vitals;
        $organizationId = $visit->organization_id ?? null;
        $caregiverId = $visit->caregiver_id ?? null;

        $this->syncAlert(
            'high_blood_pressure',
            $this->isHighBloodPressure($vitals),
            'High blood pressure detected for visit #' . $visit->id,
            $visit->id,
            $organizationId,
            $caregiverId
        );

        $this->syncAlert(
            'fever',
            $this->isFever($vitals),
            'Fever detected for visit #' . $visit->id,
            $visit->id,
            $organizationId,
            $caregiverId
        );

        $this->syncAlert(
            'low_oxygen',
            $this->isLowOxygen($vitals),
            'Low oxygen saturation detected for visit #' . $visit->id,
            $visit->id,
            $organizationId,
            $caregiverId
        );

        $this->syncAlert(
            'high_respiratory_rate',
            $this->isHighRespiratoryRate($vitals),
            'High respiratory rate detected for visit #' . $visit->id,
            $visit->id,
            $organizationId,
            $caregiverId
        );
    }

    protected function syncAlert(
        string $type,
        bool $shouldExist,
        string $message,
        int $visitId,
        $organizationId = null,
        $caregiverId = null
    ): void {
        if (!class_exists(\App\Models\Alert::class)) {
            return;
        }

        if ($shouldExist) {
            \App\Models\Alert::updateOrCreate(
                [
                    'visit_id' => $visitId,
                    'type' => $type,
                ],
                [
                    'message' => $message,
                    'organization_id' => $organizationId,
                    'caregiver_id' => $caregiverId,
                    'resolved' => false,
                    'read_at' => null,
                ]
            );
        } else {
            \App\Models\Alert::where('visit_id', $visitId)
                ->where('type', $type)
                ->delete();
        }
    }

    protected function isHighBloodPressure(array $vitals): bool
    {
        $bloodPressure = $vitals['blood_pressure'] ?? $vitals['bp'] ?? null;

        if (!$bloodPressure || !str_contains((string) $bloodPressure, '/')) {
            return false;
        }

        [$systolic, $diastolic] = array_pad(explode('/', (string) $bloodPressure), 2, null);

        $systolic = is_numeric(trim((string) $systolic)) ? (int) trim((string) $systolic) : null;
        $diastolic = is_numeric(trim((string) $diastolic)) ? (int) trim((string) $diastolic) : null;

        return ($systolic !== null && $systolic >= 160)
            || ($diastolic !== null && $diastolic >= 100);
    }

    protected function isFever(array $vitals): bool
    {
        $temperature = $vitals['temperature'] ?? $vitals['temp'] ?? null;

        if ($temperature === null || $temperature === '') {
            return false;
        }

        return is_numeric($temperature) && (float) $temperature >= 100.4;
    }

    protected function isLowOxygen(array $vitals): bool
    {
        $oxygen = $vitals['oxygen_saturation'] ?? $vitals['oxygen'] ?? null;

        if ($oxygen === null || $oxygen === '') {
            return false;
        }

        return is_numeric($oxygen) && (float) $oxygen > 0 && (float) $oxygen < 90;
    }

    protected function isHighRespiratoryRate(array $vitals): bool
    {
        $respiratoryRate = $vitals['respiratory_rate'] ?? null;

        if ($respiratoryRate === null || $respiratoryRate === '') {
            return false;
        }

        return is_numeric($respiratoryRate) && (float) $respiratoryRate >= 22;
    }
}
