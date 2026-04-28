<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Communication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommunicationController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $validated = $request->validate([
            'client_id'  => ['required', 'exists:clients,id'],
            'type'      => ['required', 'in:call,email,fax'],
            'recipient' => ['nullable', 'string', 'max:255'],
            'subject'   => ['nullable', 'string', 'max:255'],
            'message'   => ['nullable', 'string'],
        ]);

        $client = Client::findOrFail($validated['client_id']);

        $communication = Communication::create([
            'client_id'       => $client->id,
            'provider_id'     => $user->id,
            'type'            => $validated['type'],
            'recipient'       => $validated['recipient'] ?? null,
            'subject'         => $validated['subject'] ?? null,
            'message'         => $validated['message'] ?? null,
            'communicated_at' => now(),
        ]);

        if ($communication->type === 'email' && !empty($communication->recipient)) {
            try {
                Mail::raw($communication->message ?? 'No message provided', function ($mail) use ($communication) {
                    $mail->to($communication->recipient)
                         ->subject($communication->subject ?? 'KassCare Communication');
                });
            } catch (\Throwable $e) {
                Log::error('Email send failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Communication logged successfully.');
    }
}
