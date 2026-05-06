<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeWebhookController extends CashierController
{
    public function handleInvoicePaymentSucceeded($payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;

        if (! $customerId) {
            return response()->json(['status' => 'no customer'], 200);
        }

        $user = User::where('stripe_id', $customerId)->first();

        if ($user) {
            $user->update([
                'subscription_status' => 'active',
                'subscription_starts_at' => now(),
                'subscription_ends_at' => now()->addMonth(),
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function handleInvoicePaymentFailed($payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;

        if (! $customerId) {
            return response()->json(['status' => 'no customer'], 200);
        }

        $user = User::where('stripe_id', $customerId)->first();

        if ($user) {
            $user->update([
                'subscription_status' => 'past_due',
            ]);
        }

        return response()->json(['status' => 'payment_failed'], 200);
    }

    public function handleCustomerSubscriptionDeleted($payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;

        if (! $customerId) {
            return response()->json(['status' => 'no customer'], 200);
        }

        $user = User::where('stripe_id', $customerId)->first();

        if ($user) {
            $user->update([
                'subscription_status' => 'inactive',
                'subscription_ends_at' => now(),
            ]);
        }

        return response()->json(['status' => 'cancelled'], 200);
    }
}
