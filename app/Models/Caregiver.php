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
        'status'
    ];

}
