<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class ProviderCalendarController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['client', 'caregiver'])
            ->orderBy('visit_date', 'asc')
            ->get();

        return view('provider.calendar', compact('visits'));
    }
}
