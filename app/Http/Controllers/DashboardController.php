<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;

class DashboardController extends Controller
{
    public function index()
    {
        // Gathering the data from the database
        $clientCount = Client::count();
        $caregiverCount = Caregiver::count();
        $visitCount = Visit::count();
        $alertCount = 3; // Placeholder for alerts

        // Sending the data to the view
        return view('dashboard.index', compact(
            'clientCount',
            'caregiverCount',
            'visitCount',
            'alertCount'
        ));
    }
}
