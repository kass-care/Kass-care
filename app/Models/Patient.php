<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PatientDocument;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'room_number',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'client_id');
    }

    public function careLogs()
    {
        return $this->hasManyThrough(
            CareLog::class,
            Visit::class,
            'client_id',
            'visit_id',
            'id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
public function documents()
{
    return $this->hasMany(PatientDocument::class, 'patient_id')->latest();
}
}
