<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use App\Models\CareLog;
use App\Models\Alert;
use App\Models\Evv;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = Client::count();

        $caregivers = Caregiver::count();

        $visitsToday = Visit::whereDate('created_at', today())->count();

        $careLogsToday = CareLog::whereDate('created_at', today())->count();

        $alerts = Alert::count();

        $evvRecords = Evv::count();

        return view('dashboard.index', compact(
            'clients',
            'caregivers',
            'visitsToday',
            'careLogsToday',
            'alerts',
            'evvRecords'
        ));
    }
}
