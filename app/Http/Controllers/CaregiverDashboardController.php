<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class CaregiverDashboardController extends Controller
{
    public function index()
    {
        // TEMP: show all schedules for now
        $schedules = Schedule::with(['client','caregiver'])->latest()->get();

        return view('caregiver.dashboard', compact('schedules'));
    }
}
