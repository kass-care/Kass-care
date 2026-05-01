<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ProviderNote;
use Illuminate\Support\Str;

class ClaimController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $claims = Claim::with(['client', 'visit', 'providerNote', 'facility'])
            ->latest()
            ->get();

        return view('provider.claims.index', compact('claims'));
    }

    public function show($id)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $claim = Claim::with(['client', 'visit', 'providerNote', 'facility'])
            ->findOrFail($id);

        return view('provider.claims.show', compact('claim'));
    }

    public function store($noteId)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $note = ProviderNote::with('visit.client')->findOrFail($noteId);

        $visit = $note->visit;
        $client = $visit?->client;
        $coding = $this->suggestCodingFromNote($note);
    $selectedIcdCodes = request('icd_codes')
    ? array_values(array_filter(array_map('trim', explode(',', request('icd_codes')))))
    : $coding['icd_codes'];

$selectedCptCode = request('cpt_code') ?: $coding['cpt_code'];
$selectedPosCode = request('pos_code') ?: $coding['pos_code'];
        Claim::create([
            'client_id' => $client?->id,
            'visit_id' => $visit?->id,
            'provider_note_id' => $note->id,
            'provider_id' => $user->id,
            'facility_id' => $visit?->facility_id,
            'claim_number' => 'CLM-' . strtoupper(Str::random(8)),
            'status' => 'draft',
             'icd_codes' => $selectedIcdCodes,
'cpt_code' => $selectedCptCode,
'pos_code' => $selectedPosCode,
            'billing_notes' => $coding['billing_notes'],
            'estimated_amount' => $coding['estimated_amount'],
        ]);

        return back()->with('success', 'Claim generated successfully (Draft).');
    }

    public function submit($id)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $claim = Claim::findOrFail($id);

        if ($claim->status !== 'draft') {
            return back()->with('error', 'Only draft claims can be submitted.');
        }

        $claim->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Claim submitted successfully.');
    }

    public function markPaid($id)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $claim = Claim::findOrFail($id);

        $claim->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Claim marked as PAID.');
    }

    public function markDenied($id)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $claim = Claim::findOrFail($id);

        $claim->update([
            'status' => 'denied',
            'denied_at' => now(),
        ]);

        return back()->with('error', 'Claim marked as DENIED.');
    }

    private function suggestCodingFromNote(ProviderNote $note): array
    {
        $text = strtolower(trim(
            ($note->chief_complaint ?? '') . ' ' .
            ($note->subjective ?? '') . ' ' .
            ($note->objective ?? '') . ' ' .
            ($note->assessment ?? '') . ' ' .
            ($note->plan ?? '') . ' ' .
            ($note->note ?? '')
        ));

        $keywordMap = [
            'copd' => 'J44.9',
            'shortness of breath' => 'R06.02',
            'sob' => 'R06.02',
            'asthma' => 'J45.909',
            'hypertension' => 'I10',
            'high blood pressure' => 'I10',
            'diabetes' => 'E11.9',
            'abdominal pain' => 'R10.9',
            'appendicitis' => 'K37',
            'chest pain' => 'R07.9',
            'cough' => 'R05.9',
            'fever' => 'R50.9',
            'fall' => 'W19.XXXA',
            'weakness' => 'R53.1',
            'dizziness' => 'R42',
            'fatigue' => 'R53.83',
            'confusion' => 'R41.0',
            'pain' => 'R52',
        ];

        $icdCodes = [];

        foreach ($keywordMap as $keyword => $code) {
            if (str_contains($text, $keyword) && !in_array($code, $icdCodes, true)) {
                $icdCodes[] = $code;
            }
        }

        if (empty($icdCodes)) {
            $icdCodes[] = 'Z00.00';
        }

        $noteLength = strlen($text);
        $hasAssessment = !empty($note->assessment);
        $hasPlan = !empty($note->plan);
        $hasObjective = !empty($note->objective);

        if ($noteLength > 1200 && $hasAssessment && $hasPlan && $hasObjective) {
            $cptCode = '99215';
            $amount = 225.00;
            $complexity = 'High complexity provider visit suggested from detailed documentation.';
        } elseif ($noteLength > 500 && $hasAssessment && $hasPlan) {
            $cptCode = '99214';
            $amount = 150.00;
            $complexity = 'Moderate complexity provider visit suggested from documentation.';
        } else {
            $cptCode = '99213';
            $amount = 95.00;
            $complexity = 'Low complexity provider visit suggested from documentation.';
        }

        return [
            'icd_codes' => $icdCodes,
            'cpt_code' => $cptCode,
            'pos_code' => '12',
            'billing_notes' => 'Auto-generated coding suggestion from provider note. ' . $complexity,
            'estimated_amount' => $amount,
        ];
    }
}
