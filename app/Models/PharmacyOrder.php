<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyOrder extends Model
{
    protected $fillable = [
        'client_id',
        'provider_id',
        'medication_name',
        'dosage',
        'frequency',
        'route',
        'quantity',
        'refills',
        'pharmacy_name',
        'pharmacy_phone',
        'pharmacy_fax',
        'instructions',
        'status',
        'prescribed_at'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
