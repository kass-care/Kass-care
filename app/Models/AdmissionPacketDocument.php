<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionPacketDocument extends Model
{
    protected $fillable = [
        'admission_packet_id',
        'document_template_id',
        'title',
        'category',
        'content',
        'sort_order',
    ];
public function admissionPacket()
{
    return $this->belongsTo(AdmissionPacket::class);
}

public function template()
{
    return $this->belongsTo(DocumentTemplate::class, 'document_template_id');
}
}
