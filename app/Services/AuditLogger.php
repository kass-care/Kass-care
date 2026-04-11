<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log($module, $action, $description = null)
    {
        try {
            $user = Auth::user();

            AuditLog::create([
                'user_id' => $user ? $user->id : null,
                'facility_id' => session('facility_id'),
                'module' => $module,
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // fail silently so logging never breaks the app
        }
    }
}
