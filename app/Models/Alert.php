<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Alert extends Model
{
    protected $fillable = [
        'organization_id',
        'facility_id',
        'client_id',
        'visit_id',
        'caregiver_id',
        'provider_id',
        'provider_note_id',
        'type',
        'title',
        'severity',
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
                    $query->where(function (Builder $q) use ($selectedFacilityId) {
                        $q->where('facility_id', $selectedFacilityId)
                          ->orWhereHas('visit', function (Builder $visitQuery) use ($selectedFacilityId) {
                              $visitQuery->where('facility_id', $selectedFacilityId);
                          });
                    });
                }

                return;
            }

            $facilityId = session('facility_id') ?? $user->facility_id;

            if (!empty($facilityId)) {
                $query->where(function (Builder $q) use ($facilityId) {
                    $q->where('facility_id', $facilityId)
                      ->orWhereHas('visit', function (Builder $visitQuery) use ($facilityId) {
                          $visitQuery->where('facility_id', $facilityId);
                      });
                });
            }
        });
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function providerNote()
    {
        return $this->belongsTo(ProviderNote::class, 'provider_note_id');
    }

    public function directClient()
    {
        return $this->belongsTo(Client::class, 'client_id');
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
