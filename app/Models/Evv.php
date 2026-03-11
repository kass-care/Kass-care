<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evv extends Model
{
    protected $table = 'evv';

    protected $fillable = [
        'visit_id',
        'caregiver_id',
        'clock_in_time',
        'clock_out_time',
        'gps_lat',
        'gps_lng',
        'total_minutes'
    ];
}