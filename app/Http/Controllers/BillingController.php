<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * Central plan catalog for Kass Care SaaS
     */
    protected function plans(): array
    {
        return [
            'facility' => [
                'name' => 'Facility',
                'price_id' => env('STRIPE_PRICE_FACILITY'),
                'price_label' => '$79/month',
                'facility_limit' => 1,
                'features' => [
                    'Clients and caregivers',
                    'Visits and care logs',
                    'Alerts and compliance',
                    'Facility dashboard',
                ],
            ],

            'provider_solo' => [
                'name' => 'Provider Solo',
                'price_id' => env('STRIPE_PRICE_PROVIDER_SOLO'),
                'price_label' => '$99/month',
                'facility_limit' => 1,
                'features' => [
                    'Provider notes',
                    'Clinical coding assistant',
                    'Claims generation',
                    'Provider dashboard',
                ],
            ],

            'provider_pro' => [
                'name' => 'Provider Pro',
                'price_id' => env('STRIPE_PRICE_PROVIDER_PRO'),
                'price_label' => '$199/month',
                'facility_limit' => 10,
                'features' => [
                    'Everything in Provider Solo',
                    'Claims intelligence',
                    'Revenue dashboard',
                    'Multi-facility provider workflow',
                ],
            ],
        ];
    }

    /**
     * Show billing page
     */
    public function index()
    {
        $user = Auth::user();
        $plans = $this->plans();

        return view('billing.index', [
            'user' => $user,
            'plans' => $plans,
            'currentPlan' => $user->plan ?? 'none',
            'subscriptionStatus' => $user->subscription_status ?? 'inactive',
        ]);
    }

    /**
     * Start Stripe checkout
     */
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        $plans = $this->plans();

        $request->validate([
            'plan' => 'required|string|in:facility,provider_solo,provider_pro',
        ]);

        $selectedPlan = $request->plan;
        $selectedPlanConfig = $plans[$selectedPlan];

        $priceId = $selectedPlanConfig['price_id'];
if (empty($priceId) || str_contains($priceId, 'PUT_')) {
    return back()->with('error', 'Stripe price is not configured for this plan yet.');
}        
$facilityLimit = $selectedPlanConfig['facility_limit'];

        $user->update([
            'plan' => $selectedPlan,
            'facility_limit' => $facilityLimit,
            'subscription_status' => 'pending',
        ]);

        if (! $user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        return $user->newSubscription('default', $priceId)->checkout([
            'success_url' => route('billing.success') . '?plan=' . $selectedPlan,
            'cancel_url' => route('billing.index'),
        ]);
    }

    /**
     * After successful payment
     */
    public function success(Request $request)
    {
        $user = Auth::user();

        $plans = $this->plans();
        $plan = $request->get('plan', $user->plan ?? 'facility');

        if (! array_key_exists($plan, $plans)) {
            $plan = 'facility';
        }

        $facilityLimit = $plans[$plan]['facility_limit'];

        $user->update([
            'plan' => $plan,
            'subscription_status' => 'active',
            'subscription_starts_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
            'facility_limit' => $facilityLimit,
        ]);

        if ($user->role === 'admin') {
            if (! session()->has('facility_id') && ! empty($user->facility_id)) {
                session(['facility_id' => $user->facility_id]);
            }

            return redirect()
                ->route('facility.admin.home')
                ->with('success', '🎉 Subscription successful! Your facility is now active.');
        }

        if ($user->role === 'provider') {
            return redirect()
                ->route('provider.dashboard')
                ->with('success', '🎉 Subscription successful! Your plan is now active.');
        }

        if ($user->role === 'caregiver') {
            return redirect()
                ->route('caregiver.dashboard')
                ->with('success', '🎉 Subscription successful! Your plan is now active.');
        }

        return redirect()
            ->route('facility.admin.home')
            ->with('success', '🎉 Subscription successful! Your plan is now active.');
    }

    /**
     * Billing notice page for inactive subscriptions
     */
    public function notice()
    {
        $user = Auth::user();

        return view('billing.notice', compact('user'));
    }
}
