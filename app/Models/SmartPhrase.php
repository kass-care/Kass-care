<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartPhrase extends Model
{
    protected $fillable = [
        'user_id',
        'shortcut',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
