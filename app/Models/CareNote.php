<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'caregiver_id',
        'note',
        'vitals',
        'medication'
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }
}