<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'facility_id',
        'caregiver_id',
        'shift_date',
        'shift_start',
        'shift_end',
        'status',
        'clock_in_at',
        'clock_out_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'clock_in_at' => 'datetime',
        'clock_out_at' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
