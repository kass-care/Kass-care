<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facility;
use App\Models\FacilityReadinessItem;
use App\Models\Medication;
use App\Models\User;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;

class FacilitySurveyPacketController extends Controller
{
    public function download()
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');

        $facility = Facility::findOrFail($facilityId);

        $readinessItems = FacilityReadinessItem::where('facility_id', $facilityId)
            ->latest()
            ->get();

        $clients = Client::where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();

        $clientIds = $clients->pluck('id');

        $medications = Medication::with('client')
            ->whereIn('client_id', $clientIds)
            ->orderBy('medication_name')
            ->get();

        $caregivers = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();

        $visits = Visit::with(['client', 'caregiver'])
            ->where('facility_id', $facilityId)
            ->latest()
            ->take(50)
            ->get();

        $total = $readinessItems->count();
        $complete = $readinessItems->where('status', 'complete')->count();
        $pending = $readinessItems->where('status', 'pending')->count();
        $issues = $readinessItems->where('status', 'issue')->count();
        $score = $total > 0 ? round(($complete / $total) * 100) : 0;

        $pdf = Pdf::loadView('facility.readiness.packet-pdf', compact(
            'facility',
            'readinessItems',
            'clients',
            'medications',
            'caregivers',
            'visits',
            'total',
            'complete',
            'pending',
            'issues',
            'score'
        ));

        return $pdf->download('kasscare-state-survey-packet-' . now()->format('Y-m-d') . '.pdf');
    }
}
