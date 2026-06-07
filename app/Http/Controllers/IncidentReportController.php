<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\IncidentReport;
use App\Models\ProviderMessage;
use App\Models\User;
use Illuminate\Http\Request;

class IncidentReportController extends Controller
{
    public function caregiverIndex()
    {
        $reports = IncidentReport::with(['client', 'facility', 'reporter'])
            ->where('reported_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('caregiver.incidents.index', compact('reports'));
    }

    public function caregiverCreate()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $clients = Client::where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();

        return view('caregiver.incidents.create', compact('clients'));
    }

    public function caregiverStore(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'incident_type' => ['required', 'string', 'max:100'],
            'severity' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'incident_datetime' => ['nullable', 'date'],
            'description' => ['required', 'string'],
            'immediate_action' => ['nullable', 'string'],
            'witnesses' => ['nullable', 'string', 'max:255'],
            'provider_notified' => ['nullable'],
            'family_notified' => ['nullable'],
            'ems_called' => ['nullable'],
            'hospital_transfer' => ['nullable'],
        ]);

        $client = !empty($data['client_id']) ? Client::find($data['client_id']) : null;
        $facilityId = $client?->facility_id ?? session('facility_id') ?? auth()->user()->facility_id;

        $report = IncidentReport::create([
            'facility_id' => $facilityId,
            'client_id' => $data['client_id'] ?? null,
            'reported_by' => auth()->id(),
            'incident_type' => $data['incident_type'],
            'severity' => $data['severity'],
            'location' => $data['location'] ?? null,
            'incident_datetime' => $data['incident_datetime'] ?? now(),
            'description' => $data['description'],
            'immediate_action' => $data['immediate_action'] ?? null,
            'witnesses' => $data['witnesses'] ?? null,
            'provider_notified' => $request->boolean('provider_notified'),
            'family_notified' => $request->boolean('family_notified'),
            'ems_called' => $request->boolean('ems_called'),
            'hospital_transfer' => $request->boolean('hospital_transfer'),
            'status' => 'open',
        ]);

        if ($report->provider_notified && $facilityId) {
            $providerId = $client?->provider_id
                ?? User::where('role', 'provider')->where('facility_id', $facilityId)->value('id');

            if ($providerId) {
                ProviderMessage::create([
                    'facility_id' => $facilityId,
                    'client_id' => $report->client_id,
                    'sender_id' => auth()->id(),
                    'provider_id' => $providerId,
                    'subject' => 'KassCare Incident Report: ' . $report->incident_type,
                    'message' =>
                        "KassCare Incident Report\n\n" .
                        "Incident #: {$report->id}\n" .
                        "Type: {$report->incident_type}\n" .
                        "Severity: {$report->severity}\n" .
                        "Location: " . ($report->location ?? 'Not provided') . "\n" .
                        "Witnesses: " . ($report->witnesses ?? 'None reported') . "\n\n" .
                        "Immediate Action Taken:\n" .
                        ($report->immediate_action ?? 'Not documented') . "\n\n" .
                        "Narrative:\n" .
                        $report->description,
                    'priority' => in_array($report->severity, ['High', 'Critical']) ? 'urgent' : 'normal',
                ]);
            }
        }

        return redirect()
            ->route('caregiver.incidents.index')
            ->with('success', 'Incident report saved successfully.');
    }
}
