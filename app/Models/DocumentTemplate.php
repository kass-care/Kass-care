<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'title',
        'state',
        'category',
        'content',
        'active',
    ];
}
