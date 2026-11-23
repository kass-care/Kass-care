<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Support\AuditLogger;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $facilityId = session('facility_id');
        $search = trim((string) $request->get('search'));

        $query = Client::query()->with(['facility', 'provider', 'latestVisit']);

        if ($user && $user->role === 'super_admin') {
            if ($facilityId) {
                $query->where('facility_id', $facilityId);
            }
        } else {
            $effectiveFacilityId = $facilityId ?: ($user->facility_id ?? null);

            if ($effectiveFacilityId) {
                $query->where('facility_id', $effectiveFacilityId);
            }
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(12)->withQueryString();

        return view('clients.index', compact('clients', 'search'));
    }

    public function create()
    {
        $user = auth()->user();
        $facilityId = session('facility_id') ?: ($user->facility_id ?? null);

        if (!$facilityId && $user->role !== 'super_admin') {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Please select a facility first before creating a client.');
        }

        return view('clients.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],

            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'room' => ['nullable', 'string', 'max:255'],
            'height' => ['nullable', 'numeric'],
            'weight' => ['nullable', 'numeric'],
            'chief_complaint' => ['nullable', 'string'],
            'medical_history' => ['nullable', 'string'],
            'family_history' => ['nullable', 'string'],
            'social_history' => ['nullable', 'string'],
        ]);

        $facilityId = session('facility_id') ?: ($user->facility_id ?? null);

        if (!$facilityId) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Please select a facility before creating a client.');
        }

        $height = isset($validated['height']) && $validated['height'] !== null
            ? (float) $validated['height']
            : null;

        $weight = isset($validated['weight']) && $validated['weight'] !== null
            ? (float) $validated['weight']
            : null;

        $bmi = null;
        if ($height && $weight && $height > 0) {
            $heightInMeters = $height / 100;
            $bmi = round($weight / ($heightInMeters * $heightInMeters), 2);
        }

        $dateOfBirth = $validated['date_of_birth'] ?? null;
        $age = null;

        if ($dateOfBirth) {
            $age = now()->diffInYears(Carbon::parse($dateOfBirth));
        }

        $client = Client::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'facility_id' => $facilityId,

            'date_of_birth' => $dateOfBirth,
            'age' => $age,
            'gender' => $validated['gender'] ?? null,
            'room' => $validated['room'] ?? null,
            'height' => $height,
            'weight' => $weight,
            'bmi' => $bmi,
            'chief_complaint' => $validated['chief_complaint'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'family_history' => $validated['family_history'] ?? null,
            'social_history' => $validated['social_history'] ?? null,
        ]);

        AuditLogger::log(
            action: 'patient_created',
            description: 'Created new patient record',
            targetType: 'client',
            targetId: $client->id,
            clientId: $client->id,
            clientName: $client->name,
            meta: [
                'facility_id' => $client->facility_id,
                'email' => $client->email,
                'date_of_birth' => $client->date_of_birth,
                'gender' => $client->gender,
                'room' => $client->room,
                'bmi' => $client->bmi,
            ]
        );

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $this->authorizeClient($client);

        $client->load([
            'diagnoses',
            'medications',
            'facility',
            'provider',
            'visits' => function ($query) {
                $query->latest('visit_date')->latest('created_at');
            },
            'visits.provider',
            'visits.caregiver',
            'visits.facility',
            'visits.careLogs',
            'visits.providerNote',
        ]);

        AuditLogger::log(
            action: 'patient_viewed',
            description: 'Opened patient clinical profile',
            targetType: 'client',
            targetId: $client->id,
            clientId: $client->id,
            clientName: $client->name,
            meta: [
                'facility_id' => $client->facility_id,
                'provider_id' => $client->provider_id,
            ]
        );

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $this->authorizeClient($client);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $this->authorizeClient($client);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],

            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'room' => ['nullable', 'string', 'max:255'],
            'height' => ['nullable', 'numeric'],
            'weight' => ['nullable', 'numeric'],
            'chief_complaint' => ['nullable', 'string'],
            'medical_history' => ['nullable', 'string'],
            'family_history' => ['nullable', 'string'],
            'social_history' => ['nullable', 'string'],
        ]);

        $height = isset($validated['height']) && $validated['height'] !== null
            ? (float) $validated['height']
            : null;

        $weight = isset($validated['weight']) && $validated['weight'] !== null
            ? (float) $validated['weight']
            : null;

        $bmi = null;
        if ($height && $weight && $height > 0) {
            $heightInMeters = $height / 100;
            $bmi = round($weight / ($heightInMeters * $heightInMeters), 2);
        }

        $dateOfBirth = $validated['date_of_birth'] ?? null;
        $age = null;

        if ($dateOfBirth) {
            $age = now()->diffInYears(Carbon::parse($dateOfBirth));
        }

        $client->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,

            'date_of_birth' => $dateOfBirth,
            'age' => $age,
            'gender' => $validated['gender'] ?? null,
            'room' => $validated['room'] ?? null,
            'height' => $height,
            'weight' => $weight,
            'bmi' => $bmi,
            'chief_complaint' => $validated['chief_complaint'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'family_history' => $validated['family_history'] ?? null,
            'social_history' => $validated['social_history'] ?? null,
        ]);

        AuditLogger::log(
            action: 'patient_updated',
            description: 'Updated patient record',
            targetType: 'client',
            targetId: $client->id,
            clientId: $client->id,
            clientName: $client->name,
            meta: [
                'facility_id' => $client->facility_id,
                'email' => $client->email,
                'date_of_birth' => $client->date_of_birth,
                'gender' => $client->gender,
                'room' => $client->room,
                'bmi' => $client->bmi,
            ]
        );

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $this->authorizeClient($client);

        AuditLogger::log(
            action: 'patient_deleted',
            description: 'Deleted patient record',
            targetType: 'client',
            targetId: $client->id,
            clientId: $client->id,
            clientName: $client->name,
            meta: [
                'facility_id' => $client->facility_id,
                'email' => $client->email,
            ]
        );

        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    private function authorizeClient(Client $client): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        if ($user->role === 'super_admin') {
            return;
        }

        $facilityId = session('facility_id') ?: ($user->facility_id ?? null);

        if ($facilityId && (int) $client->facility_id !== (int) $facilityId) {
            abort(403, 'Unauthorized access to this client.');
        }
    }
}
