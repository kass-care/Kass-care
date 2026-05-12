<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'client_id',
        'diagnosis_id',
        'medication_name',
        'dose',
        'frequency',
        'instructions',
        'status',
        'prescribed_by',
       'route',
'is_prn',
'emar_times',
'start_date',
'end_date',
'approval_status',
'approved_by',
'approved_at',
'provider_note',
    ];
protected $casts = [
    'is_prn' => 'boolean',
    'emar_times' => 'array',
    'start_date' => 'date',
    'end_date' => 'date',
'approved_at' => 'datetime',
];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }
public function administrations()
{
    return $this->hasMany(EmarAdministration::class);
}
}
