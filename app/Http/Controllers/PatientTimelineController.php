<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Visit;
use App\Models\CareLog;
use App\Models\ProviderNote;
use App\Models\Diagnosis;
use App\Models\Medication;

class PatientTimelineController extends Controller
{
    public function show($client)
    {
        $client = Client::findOrFail($client);

        $visits = Visit::where('client_id', $client->id)
            ->orderBy('visit_date', 'desc')
            ->get();

        $careLogs = CareLog::whereHas('visit', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $providerNotes = ProviderNote::whereHas('visit', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $diagnoses = Diagnosis::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $medications = Medication::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $timeline = collect();

        foreach ($visits as $visit) {
            $timeline->push([
                'type' => 'visit',
                'title' => 'Visit ' . ucfirst($visit->status ?? 'scheduled'),
                'description' => 'Visit ID #' . $visit->id,
                'time' => $visit->visit_date ?? $visit->created_at,
                'color' => 'blue',
            ]);
        }

        foreach ($careLogs as $log) {
            $alerts = [];

            if (!empty($log->high_bp_alert)) {
                $alerts[] = 'High BP flagged';
            }

            if (!empty($log->low_oxygen_alert)) {
                $alerts[] = 'Low oxygen flagged';
            }

            if (!empty($log->mild_temp_alert)) {
                $alerts[] = 'Mild temperature elevation';
            }

            if (!empty($log->fever_alert)) {
                $alerts[] = 'Fever flagged';
            }

            if (!empty($log->pulse_alert)) {
                $alerts[] = 'Pulse alert';
            }

            $description = 'Care Log #' . $log->id;

            if (!empty($alerts)) {
                $description .= ' • ' . implode(' • ', $alerts);
            }

            $timeline->push([
                'type' => 'carelog',
                'title' => 'Caregiver Care Log',
                'description' => $description,
                'time' => $log->created_at,
                'color' => 'indigo',
            ]);
        }

        foreach ($providerNotes as $note) {
            $timeline->push([
                'type' => 'note',
                'title' => 'Provider Note',
                'description' => $note->title ?? 'Provider added a clinical note',
                'time' => $note->created_at,
                'color' => 'green',
            ]);
        }

        foreach ($diagnoses as $diagnosis) {
            $timeline->push([
                'type' => 'diagnosis',
                'title' => 'Diagnosis Added',
                'description' => $diagnosis->name ?? $diagnosis->diagnosis ?? 'Diagnosis recorded',
                'time' => $diagnosis->created_at,
                'color' => 'red',
            ]);
        }

        foreach ($medications as $medication) {
            $medicationName = $medication->name ?? $medication->medication_name ?? 'Medication';
            $details = [];

            if (!empty($medication->dose)) {
                $details[] = 'Dose: ' . $medication->dose;
            }

            if (!empty($medication->frequency)) {
                $details[] = 'Frequency: ' . $medication->frequency;
            }

            $description = $medicationName;

            if (!empty($details)) {
                $description .= ' • ' . implode(' • ', $details);
            }

            $timeline->push([
                'type' => 'medication',
                'title' => 'Medication Added',
                'description' => $description,
                'time' => $medication->created_at,
                'color' => 'purple',
            ]);
        }

        $timeline = $timeline->sortByDesc('time')->values();

        return view('timeline.show', compact('client', 'timeline'));
    }
}
