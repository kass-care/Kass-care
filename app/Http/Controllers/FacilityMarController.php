<?php

namespace App\Http\Controllers;

use App\Models\Medication;

class FacilityMarController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');

        $medications = Medication::with(['client', 'provider'])
            ->whereHas('client', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->where('approval_status', 'approved')
            ->orderBy('medication_name')
            ->get();

        return view('facility.mar.index', compact('medications'));
    }
}
