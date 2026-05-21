<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFacilityContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super admin can browse globally without facility context.
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        $sessionFacilityId = session('facility_id');

        if (empty($sessionFacilityId)) {
            if (empty($user->facility_id)) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', 'Please select a facility first.');
            }

            session(['facility_id' => $user->facility_id]);
            return $next($request);
        }

        if (! empty($user->facility_id) && (int) $sessionFacilityId !== (int) $user->facility_id) {
            session(['facility_id' => $user->facility_id]);
        }

        return $next($request);
    }
}
