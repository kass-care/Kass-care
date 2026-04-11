<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caregiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'facility_id',
        'user_id',
        'name',
        'email',
        'phone',
        'status',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'caregiver_id');
    }

    public function careLogs(): HasMany
    {
        return $this->hasMany(CareLog::class, 'caregiver_id');
    }
}
