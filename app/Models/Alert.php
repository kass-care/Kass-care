<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{

    protected $fillable = [
        'organization_id',
        'type',
        'title',
        'message',
        'client_id'
    ];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
