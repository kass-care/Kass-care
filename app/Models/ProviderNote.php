<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderNote extends Model
{

    protected $fillable = [
        'client_id',
        'note'
    ];

}
