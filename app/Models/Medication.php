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
}
