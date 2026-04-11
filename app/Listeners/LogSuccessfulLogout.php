<?php

namespace App\Listeners;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if (!$user) {
            return;
        }

        AuditLogger::log(
            'Authentication',
            'Logout',
            'User logged out: ' . $user->name . ' (' . $user->email . ')'
        );
    }
}
