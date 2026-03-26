<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'caregiver_id',
        'facility_id',
        'activity',
        'visit_date',
        'status',
    ];

    // Relationships

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

    public function providerNote()
    {
        return $this->hasOne(ProviderNote::class);
    }
}
