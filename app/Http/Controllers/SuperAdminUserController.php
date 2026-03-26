<?php

namespace App\Http\Controllers;

use App\Models\User;

class SuperAdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view('super_admin.users.index', compact('users'));
    }
}
