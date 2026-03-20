<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderNote extends Model
{
    protected $fillable = [
        'visit_id',
        'provider_id',
        'note',
        'status',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}

