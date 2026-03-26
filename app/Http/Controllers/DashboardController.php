<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use App\Models\Facility;

class DashboardController extends Controller
{
    public function index()
    {
        $clientCount = Client::count();
        $caregiverCount = Caregiver::count();
        $visitCount = Visit::count();
        $alertCount = 3;

        $facilities = Facility::all();

        return view('dashboard.index', compact(
            'clientCount',
            'caregiverCount',
            'visitCount',
            'alertCount',
            'facilities'
        ));
    }
}
