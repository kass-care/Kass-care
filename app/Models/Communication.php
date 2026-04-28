<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable = [
        'client_id',
        'provider_id',
        'type',
        'subject',
        'message',
        'recipient',
        'communicated_at',
    ];

    protected $casts = [
        'communicated_at' => 'datetime',
    ];
}
