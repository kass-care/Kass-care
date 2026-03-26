<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use App\Models\Client;
use App\Models\Visit;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        return view('super_admin.dashboard', [
            'facilitiesCount' => Facility::count(),
            'usersCount' => User::count(),
            'adminsCount' => User::where('role', 'admin')->count(),
            'providersCount' => User::where('role', 'provider')->count(),
            'caregiversCount' => User::where('role', 'caregiver')->count(),
            'clientsCount' => Client::count(),
            'visitsCount' => Visit::count(),
        ]);
    }
}
