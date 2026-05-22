<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadinessItem extends Model
{
    protected $fillable = [
        'facility_id',
        'category',
        'title',
        'completed',
        'expires_at',
        'status',
        'notes',
    ];
}
