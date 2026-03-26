<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'visit_id',
        'facility_id',
        'created_by',
        'type',
        'title',
        'description',
        'destination',
        'priority',
        'status',
        'ordered_date',
        'follow_up_date',
        'result_notes',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
