<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareLog extends Model
{
    protected $fillable = [
        'visit_id',
        'notes',
        'adl_notes',
        'vitals',
        'mood',
    ];

    public function visit()
    {
        return $this->belongsTo(\App\Models\Visit::class, 'visit_id');
    }
}
