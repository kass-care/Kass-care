<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'facility_id', // 🔥 IMPORTANT
    ];

    public function carelogs()
    {
        return $this->hasMany(CareLog::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
