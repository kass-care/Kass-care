<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Client;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Organization has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Organization has many clients
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
