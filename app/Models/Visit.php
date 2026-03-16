<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'organization_id',
        'client_id',
        'caregiver_id',
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
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'visit_started_at' => 'datetime',
        'visit_completed_at' => 'datetime',
        'visit_started' => 'boolean',
        'visit_completed' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }

    public function careLogs()
    {
        return $this->hasMany(CareLog::class);
    }
}
