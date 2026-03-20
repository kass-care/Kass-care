<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Caregiver;
use Illuminate\Http\Request;

class AdminVisitAssignmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_if(!$user->facility_id, 403, 'No facility assigned.');

        $visits = Visit::with(['client', 'caregiver'])
            ->where('facility_id', $user->facility_id)
            ->latest()
            ->get();

        return view('admin.visits.index', compact('visits'));
    }

    public function edit(Visit $visit)
    {
        $user = auth()->user();

        abort_if(!$user->facility_id, 403, 'No facility assigned.');
        abort_if($visit->facility_id != $user->facility_id, 403, 'Unauthorized facility access.');

        $caregiversQuery = Caregiver::query();

        if (\Schema::hasColumn('caregivers', 'facility_id')) {
            $caregiversQuery->where('facility_id', $user->facility_id);
        }

        $caregivers = $caregiversQuery->get();

        return view('admin.visits.assign', compact('visit', 'caregivers'));
    }

    public function update(Request $request, Visit $visit)
    {
        $user = auth()->user();

        abort_if(!$user->facility_id, 403, 'No facility assigned.');
        abort_if($visit->facility_id != $user->facility_id, 403, 'Unauthorized facility access.');

        $request->validate([
            'caregiver_id' => 'nullable|exists:caregivers,id',
        ]);

        if ($request->filled('caregiver_id')) {
            $caregiverQuery = Caregiver::where('id', $request->caregiver_id);

            if (\Schema::hasColumn('caregivers', 'facility_id')) {
                $caregiverQuery->where('facility_id', $user->facility_id);
            }

            $caregiver = $caregiverQuery->firstOrFail();

            $visit->update([
                'caregiver_id' => $caregiver->id,
            ]);
        } else {
            $visit->update([
                'caregiver_id' => null,
            ]);
        }

        return redirect()->route('admin.visits.index')
            ->with('success', 'Visit assignment updated successfully.');
    }
}
