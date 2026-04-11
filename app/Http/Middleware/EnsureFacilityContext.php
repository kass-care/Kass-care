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

        $sessionFacilityId = session('facility_id');

        /*
        |--------------------------------------------------------------------------
        | Super admin
        |--------------------------------------------------------------------------
        | Super admin may browse platform-wide and may also select a facility context.
        | If none is selected, send them back to admin dashboard.
        */
        if ($user->role === 'super_admin') {
            if (empty($sessionFacilityId)) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', 'Please select a facility first.');
            }

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Facility admin / provider / caregiver
        |--------------------------------------------------------------------------
        | These users must always stay inside their own facility.
        */
        if (in_array($user->role, ['admin', 'provider', 'caregiver'])) {
            if (empty($user->facility_id)) {
                abort(403, 'No facility assigned to this account.');
            }

            /*
            |--------------------------------------------------------------------------
            | If session facility is missing, restore it automatically
            |--------------------------------------------------------------------------
            */
            if (empty($sessionFacilityId)) {
                session(['facility_id' => $user->facility_id]);

                return $next($request);
            }

            /*
            |--------------------------------------------------------------------------
            | If session facility does not match user's facility, block crossover
            |--------------------------------------------------------------------------
            */
            if ((int) $sessionFacilityId !== (int) $user->facility_id) {
                session(['facility_id' => $user->facility_id]);

                return redirect()
                    ->route('facility.admin.home')
                    ->with('error', 'Facility context was corrected automatically.');
            }

            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
