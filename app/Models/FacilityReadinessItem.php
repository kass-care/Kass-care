<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityReadinessItem extends Model
{
    protected $fillable = [
        'facility_id',
        'created_by',
        'category',
        'title',
        'description',
        'status',
        'due_date',
        'notes',
        'completed_at',
        'completed_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
