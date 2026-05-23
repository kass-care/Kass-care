<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DutyTemplate extends Model
{
    protected $fillable = [
        'facility_id',
        'title',
        'duties',
    ];
}
