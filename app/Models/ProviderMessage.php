<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderMessage extends Model
{
    protected $fillable = [
        'facility_id',
        'client_id',
        'sender_id',
        'provider_id',
        'subject',
        'message',
        'provider_reply',
        'priority',
        'read_at',
        'replied_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
