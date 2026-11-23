<?php

namespace App\Http\Controllers\KassPro;

use App\Http\Controllers\Controller;
use App\Models\StaffCredential;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Track staff licences, CPR, TB tests and checks, with expiry warnings.
 */
class CredentialController extends Controller
{
    private function facilityId(): int
    {
        $user = auth()->user();
        $id = session('facility_id') ?? $user->facility_id;
        abort_if(!$id, 403, 'Choose a facility first.');

        return (int) $id;
    }

    private function staff(int $facilityId)
    {
        return User::where('facility_id', $facilityId)
            ->whereIn('role', ['caregiver', 'provider', 'admin'])
            ->orderBy('name')
            ->get(['id', 'name', 'role']);
    }

    public function index(Request $request)
    {
        $facilityId = $this->facilityId();
        $filter = $request->get('show', 'attention');

        $credentials = StaffCredential::with('user')
            ->where('facility_id', $facilityId)
            ->when($filter === 'attention', fn ($q) => $q->needsAttention())
            ->orderByRaw('expires_on is null')
            ->orderBy('expires_on')
            ->get();

        $counts = [
            'expired' => StaffCredential::where('facility_id', $facilityId)->whereNotNull('expires_on')->whereDate('expires_on', '<', today())->count(),
            'expiring' => StaffCredential::where('facility_id', $facilityId)->whereNotNull('expires_on')
                ->whereDate('expires_on', '>=', today())
                ->whereDate('expires_on', '<=', today()->addDays(StaffCredential::EXPIRING_DAYS))->count(),
            'all' => StaffCredential::where('facility_id', $facilityId)->count(),
        ];

        return view('kasspro.credentials', [
            'credentials' => $credentials,
            'staff' => $this->staff($facilityId),
            'types' => StaffCredential::TYPES,
            'filter' => $filter,
            'counts' => $counts,
        ]);
    }

    public function store(Request $request)
    {
        $facilityId = $this->facilityId();

        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::in($this->staff($facilityId)->pluck('id')->all())],
            'type' => ['required', 'string', 'max:80'],
            'reference' => ['nullable', 'string', 'max:120'],
            'issued_on' => ['nullable', 'date'],
            'expires_on' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        StaffCredential::create($data + [
            'facility_id' => $facilityId,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('kc.credentials.index', ['show' => 'all'])->with('success', 'Credential saved.');
    }

    public function destroy(StaffCredential $credential)
    {
        abort_unless((int) $credential->facility_id === $this->facilityId(), 404);
        $credential->delete();

        return back()->with('success', 'Credential removed.');
    }
}
