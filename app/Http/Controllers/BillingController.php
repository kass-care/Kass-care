<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * Show billing page
     */
    public function index()
    {
        return view('billing.index');
    }

    /**
     * Start subscription checkout
     */
    public function subscribe(Request $request)
    {
        $user = Auth::user();

        // Make sure user has Stripe customer
        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        // Plan price from form
        $priceId = $request->price_id;

        return $user->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('billing.success'),
                'cancel_url'  => route('billing.index'),
            ]);
    }

    /**
     * After successful payment
     */
    public function success()
    {
        return redirect()
            ->route('dashboard')
            ->with('success', 'Subscription successful 🎉');
    }
}
