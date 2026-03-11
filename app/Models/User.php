<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'pin'
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'pin'
    ];


    public function isAdmin()
    {
        return $this->role === 'admin';
    }


    public function isProvider()
    {
        return $this->role === 'provider';
    }


    public function isCaregiver()
    {
        return $this->role === 'caregiver';
    }

}
