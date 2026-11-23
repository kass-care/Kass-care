<?php

namespace App\Http\Controllers\KassPro;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Patient;
use App\Models\StaffCredential;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

/**
 * Agency (facility admin) home: what is happening today and what needs fixing.
 */
class FacilityHomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user || !in_array($user->role, ['admin', 'super_admin', 'provider']), 403, 'Unauthorized');

        $facilityId = session('facility_id') ?? $user->facility_id;
        $facility = $facilityId ? Facility::find($facilityId) : null;

        $data = [
            'facility' => $facility,
            'clientsCount' => 0,
            'caregiversCount' => 0,
            'providersCount' => 0,
            'visitsCount' => 0,
            'todayVisits' => collect(),
            'lateVisits' => collect(),
            'unassignedCount' => 0,
            'credentialAlerts' => collect(),
            'familyCount' => 0,
            'expiredDocuments' => 0,
            'expiringSoonDocuments' => 0,
            'trialDaysRemaining' => null,
        ];

        if ($facility && ($facility->subscription_status ?? null) === 'trialing' && $facility->trial_ends_at) {
            $data['trialDaysRemaining'] = (int) ceil(now()->diffInDays($facility->trial_ends_at, false));
        }

        if (!$facilityId) {
            return view('admin.facility-home', $data);
        }

        $data['clientsCount'] = $this->safe(fn () => \App\Models\Client::where('facility_id', $facilityId)->count())
            ?: $this->safe(fn () => Patient::where('facility_id', $facilityId)->count());
        $data['caregiversCount'] = $this->safe(fn () => User::where('facility_id', $facilityId)->where('role', 'caregiver')->count());
        $data['providersCount'] = $this->safe(fn () => User::where('facility_id', $facilityId)->where('role', 'provider')->count());
        $data['familyCount'] = $this->safe(fn () => User::where('facility_id', $facilityId)->where('role', 'family')->count());
        $data['visitsCount'] = $this->safe(fn () => Visit::where('facility_id', $facilityId)->count());

        if (class_exists(\App\Models\ComplianceDocument::class) && Schema::hasTable('compliance_documents')) {
            $data['expiredDocuments'] = $this->safe(fn () => \App\Models\ComplianceDocument::where('facility_id', $facilityId)
                ->whereDate('expires_at', '<', now())->count());
            $data['expiringSoonDocuments'] = $this->safe(fn () => \App\Models\ComplianceDocument::where('facility_id', $facilityId)
                ->whereDate('expires_at', '>=', now())
                ->whereDate('expires_at', '<=', now()->addDays(30))->count());
        }

        $today = Carbon::today();

        $data['todayVisits'] = $this->safe(function () use ($facilityId, $today) {
            return Visit::with(['client', 'caregiver'])
                ->where('facility_id', $facilityId)
                ->whereDate('visit_date', $today)
                ->get()
                ->sortBy(fn ($v) => $v->start_time ?? '99:99')
                ->values();
        }, collect());

        // Scheduled visits whose start time passed more than 15 minutes ago without a check-in
        $data['lateVisits'] = $data['todayVisits']->filter(function ($visit) {
            if (strtolower($visit->status ?? '') !== 'scheduled' || empty($visit->start_time)) {
                return false;
            }
            try {
                $start = Carbon::parse($visit->start_time);
                $start = Carbon::today()->setTimeFrom($start);
                return $start->lt(now()->subMinutes(15));
            } catch (\Throwable $e) {
                return false;
            }
        })->values();

        $data['unassignedCount'] = $this->safe(fn () => Visit::where('facility_id', $facilityId)
            ->whereNull('caregiver_id')
            ->whereDate('visit_date', '>=', $today)
            ->count());

        if (Schema::hasTable('staff_credentials')) {
            $data['credentialAlerts'] = StaffCredential::with('user')
                ->where('facility_id', $facilityId)
                ->needsAttention()
                ->orderBy('expires_on')
                ->take(6)
                ->get();
        }

        return view('admin.facility-home', $data);
    }

    private function safe(callable $fn, $fallback = 0)
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            report($e);
            return $fallback;
        }
    }
}
