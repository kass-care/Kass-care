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

        $text = strtolower($note->note ?? '');

        $icdCodes = [];

        if (str_contains($text, 'abdominal pain')) {
            $icdCodes[] = 'R10.9';
        }

        if (str_contains($text, 'appendicitis')) {
            $icdCodes[] = 'K37';
        }

        if (empty($icdCodes)) {
            $icdCodes[] = 'Z00.00';
        }

        $claim = Claim::create([
            'client_id' => $client?->id,
            'visit_id' => $visit?->id,
            'provider_note_id' => $note->id,
            'provider_id' => $user->id,
            'facility_id' => $visit?->facility_id,
            'claim_number' => 'CLM-' . strtoupper(Str::random(8)),
            'status' => 'draft',
            'icd_codes' => $icdCodes,
            'cpt_code' => '99214',
            'pos_code' => '12',
            'billing_notes' => 'Auto-generated from provider note',
            'estimated_amount' => 150.00,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Claim generated successfully (Draft).');
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
}
