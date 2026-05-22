<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
       protected $fillable = [
    'name',
    'email',
    'phone',
    'fax',
    'type',
    'address',
    'city',
    'state',
    'zip',
    'ncpdp_id',
    'notes',
];
}
