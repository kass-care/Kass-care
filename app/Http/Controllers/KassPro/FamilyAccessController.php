<?php

namespace App\Http\Controllers\KassPro;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Agency admins give family members read-only access to their loved one's care.
 */
class FamilyAccessController extends Controller
{
    private function facilityId(): int
    {
        $user = auth()->user();
        $id = session('facility_id') ?? $user->facility_id;
        abort_if(!$id, 403, 'Choose a facility first.');

        return (int) $id;
    }

    public function index()
    {
        $facilityId = $this->facilityId();

        $family = User::with('familyClient')
            ->where('role', 'family')
            ->where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();

        $clients = Client::where('facility_id', $facilityId)->orderBy('name')->get(['id', 'name']);

        return view('kasspro.family-access', compact('family', 'clients'));
    }

    public function store(Request $request)
    {
        $facilityId = $this->facilityId();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'relationship' => ['nullable', 'string', 'max:60'],
            'client_id' => ['required', 'integer'],
        ]);

        $client = Client::where('facility_id', $facilityId)->findOrFail($data['client_id']);

        // Readable one-time password the admin can share by phone or SMS
        $tempPassword = Str::upper(Str::random(4)) . '-' . random_int(1000, 9999) . '-' . Str::lower(Str::random(4));

        $user = new User();
        $user->forceFill([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
            'relationship' => $data['relationship'] ?? null,
            'role' => 'family',
            'facility_id' => $facilityId,
            'client_id' => $client->id,
            'password' => Hash::make($tempPassword),
        ])->save();

        return redirect()->route('kc.family.index')
            ->with('success', "Family access created for {$user->name}.")
            ->with('kc_temp_password', ['email' => $user->email, 'password' => $tempPassword]);
    }

    public function destroy(User $user)
    {
        $facilityId = $this->facilityId();
        abort_unless($user->role === 'family' && (int) $user->facility_id === $facilityId, 404);

        $name = $user->name;
        $user->delete();

        return redirect()->route('kc.family.index')->with('success', "Family access removed for {$name}.");
    }
}
