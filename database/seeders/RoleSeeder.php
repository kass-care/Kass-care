<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'provider']);
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'caregiver']);
        Role::create(['name' => 'lab']);
    }
}
