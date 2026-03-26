<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderNote extends Model
{
    protected $fillable = [
        'client_id',
        'visit_id',
        'provider_id',
        'note',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'follow_up',
        'signed_at',
        'status',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
