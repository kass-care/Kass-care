<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationAdministration extends Model
{
    protected $fillable = [
        'medication_id',
        'client_id',
        'caregiver_id',
        'administration_date',
        'scheduled_time',
        'administered_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'administration_date' => 'date',
        'administered_at' => 'datetime',
    ];

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}
