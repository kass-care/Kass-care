<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use App\Models\Alert;
use App\Models\Evv;
use App\Models\CareLog;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = Client::count();
        $caregivers = Caregiver::count();

        $visits = Visit::count();
        $alerts = Alert::count();
        $evv = Evv::count();

        // Today's data
        $visitsToday = Visit::whereDate('created_at', today())->count();
        $careLogsToday = CareLog::whereDate('created_at', today())->count();

        return view('dashboard.index', compact(
            'clients',
            'caregivers',
            'visits',
            'alerts',
            'evv',
            'visitsToday',
            'careLogsToday'
        ));
    }
}
