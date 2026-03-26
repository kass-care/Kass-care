<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'notes',
        'adls',
        'vitals',
    ];

    // This ensures your ADL checkboxes and Vital inputs 
    // are saved as arrays in the database.
    protected $casts = [
        'adls' => 'array',
        'vitals' => 'array',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class, 'visit_id');
    }
}
