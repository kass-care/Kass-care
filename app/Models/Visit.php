<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'client_id',
        'caregiver_id',
        'activity',
        'visit_date',
        'visit_time',
        'scheduled_at',
        'status',
        'notes',
        'check_in_time',
        'check_out_time',
        'facility_id',
        'duration_minutes',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'scheduled_at' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
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
        return $this->hasMany(CareLog::class, 'visit_id');
    }
}
