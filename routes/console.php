<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('subscriptions:expire-trials', function () {
    $expired = \App\Models\User::where('subscription_status', 'trialing')
        ->whereNotNull('trial_ends_at')
        ->where('trial_ends_at', '<=', now())
        ->update([
            'subscription_status' => 'inactive',
        ]);

    $this->info("Expired {$expired} trial subscription(s).");
})->purpose('Expire provider trials after trial_ends_at');

Schedule::command('subscriptions:expire-trials')->dailyAt('00:15');

Schedule::command('backup:run')->dailyAt('02:00');
Schedule::command('backup:clean')->dailyAt('03:00');
