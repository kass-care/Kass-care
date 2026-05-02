<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderNoteCode extends Model
{
    protected $fillable = [
        'provider_note_id',
        'type',
        'code',
        'label',
    ];

    public function providerNote()
    {
        return $this->belongsTo(ProviderNote::class);
    }
}

