<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimLedger extends Model
{
    protected $fillable = [
        'client_id',
        'visit_id',
        'facility_id',
        'provider_id',
        'payer_name',
        'claim_number',
        'service_date',
        'billed_amount',
        'paid_amount',
        'adjustment_amount',
        'patient_responsibility',
        'balance_amount',
        'status',
        'paid_at',
        'submitted_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'service_date' => 'date',
        'paid_at' => 'datetime',
        'submitted_at' => 'datetime',
        'billed_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'patient_responsibility' => 'decimal:2',
        'balance_amount' => 'decimal:2',
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

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'green',
            'partial' => 'yellow',
            'submitted' => 'blue',
            'denied' => 'red',
            'void' => 'gray',
            default => 'slate',
        };
    }

    public function recalculateBalance(): void
    {
        $billed = (float) ($this->billed_amount ?? 0);
        $paid = (float) ($this->paid_amount ?? 0);
        $adjustment = (float) ($this->adjustment_amount ?? 0);
        $patientResponsibility = (float) ($this->patient_responsibility ?? 0);

        $this->balance_amount = max(0, $billed - $paid - $adjustment - $patientResponsibility);
    }
}
