<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'status',
    ];

    // This links the caregiver to their visits
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    // This links the caregiver to their schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
