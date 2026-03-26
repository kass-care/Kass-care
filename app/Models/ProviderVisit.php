<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderVisit extends Model
{
    protected $fillable = [
        'facility_id',
        'visit_date',
        'next_visit_due',
        'notes'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
