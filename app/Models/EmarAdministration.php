<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmarAdministration extends Model
{
    protected $fillable = [
        'facility_id',
        'client_id',
        'medication_id',
        'caregiver_id',
        'scheduled_date',
        'scheduled_time',
        'status',
        'administered_at',
        'notes',
	'is_corrected',
	'previous_status',
	'previous_notes',
	'correction_reason',
	'corrected_by',
	'corrected_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'administered_at' => 'datetime',
	'is_corrected' => 'boolean',
	'corrected_at' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
   public function correctedBy()
{
    return $this->belongsTo(User::class, 'corrected_by');
}
}
