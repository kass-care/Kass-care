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
            'starter' => [
                'name' => 'Starter',
                'price_id' => 'price_1TDStlIdKc8nV1Tpg4nYJbwe',
                'price_label' => '$49/month',
                'facility_limit' => 1,
                'features' => [
                    '1 facility',
                    'Patient management',
                    'Visits and care logs',
                    'Basic alerts',
                ],
            ],

            'growth' => [
                'name' => 'Growth',
                'price_id' => 'price_1TDSw5IdKc8nV1TpLHFNmDDE',
                'price_label' => '$99/month',
                'facility_limit' => 3,
                'features' => [
                    'Up to 3 facilities',
                    'Provider workspace',
                    'Alerts engine',
                    'Compliance tools',
                ],
            ],

            'enterprise' => [
                'name' => 'Enterprise',
                'price_id' => 'price_1TDSxJIdKc8nV1TpMwhISvKk',
                'price_label' => '$199/month',
                'facility_limit' => 10,
                'features' => [
                    'Up to 10 facilities',
                    'Global patient command center',
                    'Rounds planner',
                    'Advanced SaaS controls',
                ],
            ],

            'monthly' => [
                'name' => 'KASSCare Monthly',
                'price_id' => 'price_1TDwRrIdKc8nV1TpxXO5SDqV',
                'price_label' => '$29/month',
                'facility_limit' => 1,
                'features' => [
                    'Simple subscription',
                    'Core access',
                    'Basic billing setup',
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
            'plan' => 'required|string|in:starter,growth,enterprise,monthly',
        ]);

        $selectedPlan = $request->plan;
        $selectedPlanConfig = $plans[$selectedPlan];

        $priceId = $selectedPlanConfig['price_id'];
        $facilityLimit = $selectedPlanConfig['facility_limit'];

        // Save intended subscription locally before Stripe checkout
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
    $plan = $request->get('plan', $user->plan ?? 'starter');

    if (! array_key_exists($plan, $plans)) {
        $plan = 'starter';
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
