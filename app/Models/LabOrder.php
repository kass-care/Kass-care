<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOrder extends Model
{

    protected $fillable = [
        'client_id',
        'lab_type',
        'instructions',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
