<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use App\Models\Alert;
use App\Models\Billing;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = Client::count();
        $caregivers = Caregiver::count();

        $visits_today = Visit::whereDate('visit_date', now())->count();

        $alerts = Alert::count();

        $revenue = Billing::sum('amount');

        return response()->json([
            'clients' => $clients,
            'caregivers' => $caregivers,
            'visits_today' => $visits_today,
            'alerts' => $alerts,
            'revenue' => $revenue
        ]);
    }
}
