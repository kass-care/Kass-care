<?php

namespace App\Http\Controllers;

use App\Models\FacilityProviderCycle;
use App\Models\Visit;
use Carbon\Carbon;

class ComplianceController extends Controller
{
    public function index()
    {
        $this->syncCyclesFromCompletedVisits();

        $user = auth()->user();
        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $cycles = FacilityProviderCycle::with(['facility', 'provider'])
            ->when($facilityId && $user?->role !== 'super_admin', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->orderBy('next_due_at')
            ->get()
            ->map(function ($cycle) {
                $cycle->computed_status = $this->computeStatus($cycle->next_due_at);
                return $cycle;
            });

        $overdue = $cycles->where('computed_status', 'overdue')->count();
        $dueSoon = $cycles->where('computed_status', 'due_soon')->count();
        $due = $cycles->where('computed_status', 'due')->count();
        $current = $cycles->where('computed_status', 'current')->count();

        return view('compliance.index', compact(
            'cycles',
            'overdue',
            'dueSoon',
            'due',
            'current'
        ));
    }

    private function syncCyclesFromCompletedVisits(): void
    {
        $visits = Visit::with(['facility', 'provider', 'providerNote'])
            ->whereNotNull('facility_id')
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('visit_completed', true)
                    ->orWhereNotNull('visit_completed_at')
                    ->orWhereHas('providerNote');
            })
            ->get();

        foreach ($visits as $visit) {
            $providerId = $visit->provider_id ?? $visit->providerNote?->provider_id;

            if (!$providerId) {
                continue;
            }

            $lastCompletedAt = $visit->visit_completed_at
                ?? $visit->providerNote?->signed_at
                ?? $visit->visit_date
                ?? $visit->updated_at
                ?? now();

            $lastCompletedAt = Carbon::parse($lastCompletedAt);
            $nextDueAt = $lastCompletedAt->copy()->addDays(60);

            $cycle = FacilityProviderCycle::firstOrNew([
                'facility_id' => $visit->facility_id,
                'provider_id' => $providerId,
            ]);

            if (
                !$cycle->exists ||
                empty($cycle->last_completed_at) ||
                Carbon::parse($lastCompletedAt)->greaterThan(Carbon::parse($cycle->last_completed_at))
            ) {
                $cycle->review_interval_days = 60;
                $cycle->last_completed_at = $lastCompletedAt;
                $cycle->next_due_at = $nextDueAt;
                $cycle->scheduled_for = $nextDueAt;
                $cycle->completed_for_cycle_at = $lastCompletedAt;
                $cycle->status = $this->computeStatus($nextDueAt);
                $cycle->priority = $cycle->status === 'overdue'
                    ? 'high'
                    : ($cycle->status === 'due_soon' || $cycle->status === 'due' ? 'medium' : 'low');
                $cycle->notes = 'Auto-synced from completed provider visit/note #' . $visit->id;
                $cycle->save();
            }
        }
    }

    private function computeStatus($nextDueAt): string
    {
        if (!$nextDueAt) {
            return 'overdue';
        }

        $nextDue = Carbon::parse($nextDueAt)->startOfDay();
        $today = now()->startOfDay();

        if ($nextDue->lessThan($today)) {
            return 'overdue';
        }

        if ($nextDue->equalTo($today)) {
            return 'due';
        }

        if ($nextDue->diffInDays($today) <= 14) {
            return 'due_soon';
        }

        return 'current';
    }
}
