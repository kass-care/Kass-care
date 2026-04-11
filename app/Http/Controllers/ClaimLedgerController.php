<?php

namespace App\Http\Controllers;

use App\Models\ClaimLedger;
use App\Models\Client;
use App\Models\Facility;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class ClaimLedgerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);
        $search = trim((string) $request->get('search'));
        $status = trim((string) $request->get('status'));

        $query = ClaimLedger::with([
            'client',
            'visit',
            'facility',
            'provider',
            'creator',
        ]);

        if ($user && $user->role !== 'super_admin' && $facilityId) {
            $query->where('facility_id', $facilityId);
        }

        if ($user && $user->role === 'super_admin' && $facilityId) {
            $query->where('facility_id', $facilityId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('payer_name', 'like', "%{$search}%")
                    ->orWhere('claim_number', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $claims = $query->latest('service_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $statsQuery = ClaimLedger::query();

        if ($user && $user->role !== 'super_admin' && $facilityId) {
            $statsQuery->where('facility_id', $facilityId);
        }

        if ($user && $user->role === 'super_admin' && $facilityId) {
            $statsQuery->where('facility_id', $facilityId);
        }

        $stats = [
            'total_claims' => (clone $statsQuery)->count(),
            'submitted' => (clone $statsQuery)->where('status', 'submitted')->count(),
            'partial' => (clone $statsQuery)->where('status', 'partial')->count(),
            'paid' => (clone $statsQuery)->where('status', 'paid')->count(),
            'denied' => (clone $statsQuery)->where('status', 'denied')->count(),
            'total_billed' => (float) ((clone $statsQuery)->sum('billed_amount')),
            'total_paid' => (float) ((clone $statsQuery)->sum('paid_amount')),
            'total_balance' => (float) ((clone $statsQuery)->sum('balance_amount')),
        ];

        return view('claims.index', compact('claims', 'stats', 'search', 'status'));
    }
     public function create(Request $request)
{
    $user = auth()->user();

    abort_if(!$user, 403, 'Unauthorized.');

    $selectedFacilityId = session('facility_id');
    $userFacilityId = $user->facility_id;
    $facilityId = $selectedFacilityId ?? $userFacilityId;

    if ($user->role === 'super_admin') {
        $clients = $facilityId
            ? Client::where('facility_id', $facilityId)->orderBy('name')->get()
            : Client::orderBy('name')->get();

        $visits = $facilityId
            ? Visit::with('client')->where('facility_id', $facilityId)->latest('visit_date')->get()
            : Visit::with('client')->latest('visit_date')->get();

        $facilities = Facility::orderBy('name')->get();

        $providers = User::query()
            ->where('role', 'provider')
            ->when($facilityId, fn ($q) => $q->where('facility_id', $facilityId))
            ->orderBy('name')
            ->get();
    } else {
        abort_if(!$facilityId, 403, 'No facility assigned.');

        $clients = Client::where('facility_id', $facilityId)->orderBy('name')->get();

        $visits = Visit::with('client')
            ->where('facility_id', $facilityId)
            ->latest('visit_date')
            ->get();

        $facilities = Facility::where('id', $facilityId)->orderBy('name')->get();

        $providers = User::query()
            ->where('role', 'provider')
            ->where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();
    }

    $selectedVisit = null;

    $prefill = [
        'client_id' => old('client_id'),
        'visit_id' => old('visit_id'),
        'facility_id' => old('facility_id'),
        'provider_id' => old('provider_id'),
        'service_date' => old('service_date'),
        'status' => old('status', 'submitted'),
    ];

    if ($request->filled('visit_id')) {
        $visitQuery = Visit::with(['client', 'caregiver']);

        if ($user->role !== 'super_admin' || $facilityId) {
            $visitQuery->where('facility_id', $facilityId);
        }

        $selectedVisit = $visitQuery->find($request->visit_id);

        if ($selectedVisit) {
            $prefill['visit_id'] = $selectedVisit->id;
            $prefill['client_id'] = $selectedVisit->client_id;
            $prefill['facility_id'] = $selectedVisit->facility_id;
            $prefill['service_date'] = $selectedVisit->visit_date
                ? \Carbon\Carbon::parse($selectedVisit->visit_date)->format('Y-m-d')
                : null;
            $prefill['status'] = 'submitted';

            $providerFromVisit = null;

            if (!empty($selectedVisit->provider_id)) {
                $providerFromVisit = $selectedVisit->provider_id;
            } elseif (!empty($selectedVisit->client?->provider_id)) {
                $providerFromVisit = $selectedVisit->client->provider_id;
            }

            if ($providerFromVisit) {
                $prefill['provider_id'] = $providerFromVisit;
            }
        }
    }

    return view('claims.create', compact(
        'clients',
        'visits',
        'facilities',
        'providers',
        'selectedVisit',
        'prefill'
    ));
}


    public function store(Request $request)
    {
        $user = auth()->user();
        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
            'provider_id' => ['nullable', 'exists:users,id'],
            'payer_name' => ['required', 'string', 'max:255'],
            'claim_number' => ['nullable', 'string', 'max:255'],
            'service_date' => ['required', 'date'],
            'billed_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'adjustment_amount' => ['nullable', 'numeric', 'min:0'],
            'patient_responsibility' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:submitted,partial,paid,denied,void'],
            'paid_at' => ['nullable', 'date'],
            'submitted_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Only admin or super admin can manage claim ledgers.');
        }

        $claim = new ClaimLedger();
        $claim->fill($validated);

        $claim->facility_id = $validated['facility_id'] ?? $facilityId;
        $claim->paid_amount = $validated['paid_amount'] ?? 0;
        $claim->adjustment_amount = $validated['adjustment_amount'] ?? 0;
        $claim->patient_responsibility = $validated['patient_responsibility'] ?? 0;
        $claim->created_by = $user->id;
        $claim->updated_by = $user->id;
        $claim->recalculateBalance();
        $claim->save();

        return redirect()
            ->route('claims.index')
            ->with('success', 'Claim ledger created successfully.');
    }

    public function edit(ClaimLedger $claim)
    {
        $this->authorizeClaim($claim);

        $user = auth()->user();
        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $clients = Client::query()
            ->when($facilityId, fn ($q) => $q->where('facility_id', $facilityId))
            ->orderBy('name')
            ->get();

        $visits = Visit::query()
            ->with('client')
            ->when($facilityId, fn ($q) => $q->where('facility_id', $facilityId))
            ->orderByDesc('visit_date')
            ->get();

        $facilities = Facility::query()
            ->when($user && $user->role !== 'super_admin' && $facilityId, fn ($q) => $q->where('id', $facilityId))
            ->orderBy('name')
            ->get();

        $providers = User::query()
            ->where('role', 'provider')
            ->when($facilityId, fn ($q) => $q->where('facility_id', $facilityId))
            ->orderBy('name')
            ->get();

        return view('claims.edit', compact('claim', 'clients', 'visits', 'facilities', 'providers'));
    }

    public function update(Request $request, ClaimLedger $claim)
    {
        $this->authorizeClaim($claim);

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'facility_id' => ['nullable', 'exists:facilities,id'],
            'provider_id' => ['nullable', 'exists:users,id'],
            'payer_name' => ['required', 'string', 'max:255'],
            'claim_number' => ['nullable', 'string', 'max:255'],
            'service_date' => ['required', 'date'],
            'billed_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'adjustment_amount' => ['nullable', 'numeric', 'min:0'],
            'patient_responsibility' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:submitted,partial,paid,denied,void'],
            'paid_at' => ['nullable', 'date'],
            'submitted_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $claim->fill($validated);
        $claim->paid_amount = $validated['paid_amount'] ?? 0;
        $claim->adjustment_amount = $validated['adjustment_amount'] ?? 0;
        $claim->patient_responsibility = $validated['patient_responsibility'] ?? 0;
        $claim->updated_by = auth()->id();
        $claim->recalculateBalance();
        $claim->save();

        return redirect()
            ->route('claims.index')
            ->with('success', 'Claim ledger updated successfully.');
    }

    public function destroy(ClaimLedger $claim)
    {
        $this->authorizeClaim($claim);

        $claim->delete();

        return redirect()
            ->route('claims.index')
            ->with('success', 'Claim ledger deleted successfully.');
    }

    private function authorizeClaim(ClaimLedger $claim): void
    {
        $user = auth()->user();
        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Only admin or super admin can manage claim ledgers.');
        }

        if ($user->role === 'super_admin') {
            if ($facilityId && (int) $claim->facility_id !== (int) $facilityId) {
                abort(403, 'Unauthorized access to this claim ledger.');
            }

            return;
        }

        if ($facilityId && (int) $claim->facility_id !== (int) $facilityId) {
            abort(403, 'Unauthorized access to this claim ledger.');
        }
    }
}
