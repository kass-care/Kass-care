<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class ProviderCalendarController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        $visits = Visit::with(['client', 'caregiver'])
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->orderBy('visit_date', 'asc')
            ->get();

        return view('provider.calendar', compact('visits'));
    }
}
