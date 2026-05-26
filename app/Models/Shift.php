<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

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
    'duties',
    'special_instructions',
    'created_by',
]; 

    protected $casts = [
        'shift_date' => 'date',
        'clock_in_at' => 'datetime',
        'clock_out_at' => 'datetime',
       'duties' => 'array',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
      public function clients()
{
    return $this->belongsToMany(Client::class, 'shift_client');
}
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
