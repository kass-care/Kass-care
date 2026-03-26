<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeWebhookController extends CashierController
{
    public function handleInvoicePaymentSucceeded($payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;

        if (!$customerId) {
            return response()->json(['status' => 'no customer'], 200);
        }

        $facility = Facility::where('stripe_id', $customerId)->first();

        if ($facility) {
            $facility->update([
                'subscription_status' => 'active',
                'subscription_starts_at' => now(),
                'subscription_ends_at' => now()->addMonth(),
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function handleCustomerSubscriptionDeleted($payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;

        if (!$customerId) {
            return response()->json(['status' => 'no customer'], 200);
        }

        $facility = Facility::where('stripe_id', $customerId)->first();

        if ($facility) {
            $facility->update([
                'subscription_status' => 'inactive',
            ]);
        }

        return response()->json(['status' => 'cancelled'], 200);
    }
}
