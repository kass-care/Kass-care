<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffCredential extends Model
{
    public const TYPES = [
        'Nursing licence',
        'CNA / HHA certificate',
        'CPR / First aid',
        'TB test',
        'Background check',
        'Driver licence',
        'Car insurance',
        'Annual training',
        'Other',
    ];

    public const EXPIRING_DAYS = 30;

    protected $fillable = [
        'facility_id', 'user_id', 'type', 'reference',
        'issued_on', 'expires_on', 'notes', 'created_by',
    ];

    protected $casts = [
        'issued_on' => 'date',
        'expires_on' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** "expired", "expiring", "valid" or "no_expiry" */
    public function getStateAttribute(): string
    {
        if (!$this->expires_on) {
            return 'no_expiry';
        }

        $today = Carbon::today();

        if ($this->expires_on->lt($today)) {
            return 'expired';
        }

        if ($this->expires_on->lte($today->copy()->addDays(self::EXPIRING_DAYS))) {
            return 'expiring';
        }

        return 'valid';
    }

    public function scopeNeedsAttention($query)
    {
        return $query->whereNotNull('expires_on')
            ->whereDate('expires_on', '<=', Carbon::today()->addDays(self::EXPIRING_DAYS));
    }
}
