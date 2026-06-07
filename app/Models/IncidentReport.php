<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $fillable = [
        'facility_id',
        'client_id',
        'visit_id',
        'reported_by',
        'incident_type',
        'severity',
        'location',
        'incident_datetime',
        'description',
        'immediate_action',
        'witnesses',
        'provider_notified',
        'family_notified',
        'ems_called',
        'hospital_transfer',
        'status',
        'admin_review_notes',
    ];

    protected $casts = [
        'incident_datetime' => 'datetime',
        'provider_notified' => 'boolean',
        'family_notified' => 'boolean',
        'ems_called' => 'boolean',
        'hospital_transfer' => 'boolean',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
