<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'client_id',
        'visit_id',
        'provider_note_id',
        'provider_id',
        'facility_id',
        'claim_number',
        'status',
        'icd_codes',
        'cpt_code',
        'pos_code',
        'billing_notes',
        'estimated_amount',
        'submitted_at',
        'paid_at',
        'denied_at',
        'external_id',
    ];

    protected $casts = [
        'icd_codes' => 'array',
        'estimated_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'paid_at' => 'datetime',
        'denied_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function providerNote()
    {
        return $this->belongsTo(ProviderNote::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
