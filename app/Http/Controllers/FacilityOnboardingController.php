<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FacilityOnboardingController extends Controller
{
    public function create()
    {
       $plans = [
    'facility' => [
        'name' => 'Facility',
        'facility_limit' => 1,
    ],

    'provider_solo' => [
        'name' => 'Provider Solo',
        'facility_limit' => 1,
    ],

    'provider_pro' => [
        'name' => 'Provider Pro',
        'facility_limit' => 10,
    ],
];
        return view('auth.register-facility', compact('plans'));
    }

    public function store(Request $request)
    {
$plans = [
    'facility' => [
        'facility_limit' => 1,
    ],
    'provider_solo' => [
        'facility_limit' => 1,
    ],
    'provider_pro' => [
        'facility_limit' => 10,
    ],
];
        $request->validate([
            'facility_name' => 'required|string|max:255',
            'facility_email' => 'nullable|email|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'plan' => 'required|string|in:facility,provider_solo,provider_pro',
            'accept_terms' => 'accepted',
        ]);

        $selectedPlan = $request->plan;
        $facilityLimit = $plans[$selectedPlan]['facility_limit'];

        DB::beginTransaction();

        try {
            $facility = Facility::create([
                'name' => $request->facility_name,
                'email' => $request->facility_email,
                'accepted_terms' => true,
                'accepted_terms_at' => now(),
                'contact_person' => $request->admin_name,
                'subscription_status' => 'inactive',
                'facility_limit' => $facilityLimit,
            ]);

            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'facility_id' => $facility->id,
                'plan' => $selectedPlan,
                'subscription_status' => 'inactive',
                'facility_limit' => $facilityLimit,
            ]);

            DB::commit();

            Auth::login($user);
            session(['facility_id' => $facility->id]);

            return redirect()
                ->route('billing.notice')
                ->with('success', 'Facility created successfully. Please activate your subscription to continue.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'Facility registration failed: ' . $e->getMessage(),
            ]);
        }
    }
}
