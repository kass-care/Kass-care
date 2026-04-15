<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Alert extends Model
{
    protected $fillable = [
        'organization_id',
        'visit_id',
        'caregiver_id',
        'type',
        'message',
        'resolved',
        'read_at',
    ];

    protected $casts = [
        'resolved' => 'boolean',
        'read_at' => 'datetime',
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
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
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
}
