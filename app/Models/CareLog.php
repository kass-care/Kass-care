<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareLog extends Model
{
    protected $fillable = [
        'client_id',
        'caregiver_id',
        'visit_id',
        'notes',
        'adl_status',
        'meal_notes',
        'medication_notes',
        'organization_id',
        'bathroom_assistance',
        'mobility_support',
        'charting_notes',
        'check_in_time',
        'check_out_time',
        'latitude',
        'longitude',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
