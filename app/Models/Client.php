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
        'status', // e.g., 'Active' or 'Inactive'
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
