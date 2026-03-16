<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Facility;
use App\Models\Visit;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $clientsCount = class_exists(\App\Models\Client::class) ? Client::count() : 0;
        $caregiversCount = class_exists(\App\Models\Caregiver::class) ? Caregiver::count() : 0;
        $facilitiesCount = class_exists(\App\Models\Facility::class) ? Facility::count() : 0;
        $visitsCount = class_exists(\App\Models\Visit::class) ? Visit::count() : 0;

        return view('admin.dashboard', compact(
            'clientsCount',
            'caregiversCount',
            'facilitiesCount',
            'visitsCount'
        ));
    }
}
