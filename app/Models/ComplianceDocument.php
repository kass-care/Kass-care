<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceDocument extends Model
{
    protected $fillable = [
        'facility_id',
       'client_id',
        'readiness_item_id',
        'title',
        'category',
        'file_path',
        'file_name',
        'file_type',
        'expires_at',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
public function client()
{
    return $this->belongsTo(Client::class);
}

    public function readinessItem()
    {
        return $this->belongsTo(ReadinessItem::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
