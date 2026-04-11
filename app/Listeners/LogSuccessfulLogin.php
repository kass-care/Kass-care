<?php

namespace App\Listeners;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        AuditLogger::log(
            'Authentication',
            'Login',
            'User logged in: ' . $user->name . ' (' . $user->email . ')'
        );
    }
}
