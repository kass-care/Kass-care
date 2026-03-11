<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Client;
use App\Models\Lab;
use App\Models\ProviderVisit;
use App\Models\FacilityVisit;

class ProviderDashboardController extends Controller
{
    /**
     * Display the provider dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ✅ Count total visits
        $totalVisits = class_exists(Visit::class) ? Visit::count() : 0;

        // ✅ Count total clients
        $totalClients = class_exists(Client::class) ? Client::count() : 0;

        // ✅ Count total labs
        $totalLabs = class_exists(Lab::class) ? Lab::count() : 0;

        // ✅ Count provider visits
        $totalProviderVisits = class_exists(ProviderVisit::class) ? ProviderVisit::count() : 0;

        // ✅ Count facility visits
        $totalFacilityVisits = class_exists(FacilityVisit::class) ? FacilityVisit::count() : 0;

        // Pass all data to the dashboard view
        return view('provider.dashboard', compact(
            'totalVisits',
            'totalClients',
            'totalLabs',
            'totalProviderVisits',
            'totalFacilityVisits'
        ));
    }
}
