<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'patient_id',
        'title',
        'category',
        'file_path',
        'uploaded_by',
       'expires_at',
    ];

   protected $casts = [
    'expires_at' => 'date',
];
    public function patient()
    {
        return $this->belongsTo(Client::class, 'patient_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
