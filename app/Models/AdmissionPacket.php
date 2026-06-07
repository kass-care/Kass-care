<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionPacket extends Model
{
    protected $fillable = [
        'facility_id',
        'client_id',
        'created_by',
        'packet_name',
        'status',
        'resident_name',
        'representative_name',
        'representative_relationship',
        'monthly_rate',
        'admission_date',
        'document_text',
        'signature',
        'signed_by',
        'signed_at',
        'public_token',
        'sent_at',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'signed_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
