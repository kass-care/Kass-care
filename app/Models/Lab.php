<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{

protected $fillable = [
    'client_id',
    'test_type',
    'priority',
    'notes',
    'status'
];

public function client()
{
    return $this->belongsTo(Client::class);
}

}
