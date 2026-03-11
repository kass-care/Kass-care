<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'phone',
        'visit_frequency_days',
        'last_visit',
        'next_visit',
    ];

    protected $casts = [
        'visit_frequency_days' => 'integer',
        'last_visit' => 'date',
        'next_visit' => 'date',
    ];
}