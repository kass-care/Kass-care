<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\CareLog;

class CaregiverDashboardController extends Controller
{
    public function index()
    {
        $visitsCount = class_exists(\App\Models\Visit::class) ? Visit::count() : 0;
        $careLogsCount = class_exists(\App\Models\CareLog::class) ? CareLog::count() : 0;

        return view('caregiver.dashboard', compact('visitsCount', 'careLogsCount'));
    }
}
