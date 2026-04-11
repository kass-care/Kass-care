<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

protected static function booted()
{
    static::addGlobalScope('facility', function ($query) {
        if (Auth::check() && Auth::user()->facility_id) {
            $query->whereHas('visit', function ($visitQuery) {
                $visitQuery->where('facility_id', Auth::user()->facility_id);
            });
        }
    });
}
    protected $casts = [
        'resolved' => 'boolean',
        'read_at' => 'datetime',
    ];

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
