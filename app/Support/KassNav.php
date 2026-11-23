<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * One place that decides what each person sees in the menu.
 *
 * Each item lists one or more possible route names. The first one that
 * exists in routes/web.php is used, and items whose routes do not exist
 * are hidden, so the menu never shows a broken link.
 */
class KassNav
{
    public static function roleLabel(?string $role): string
    {
        return match ($role) {
            'super_admin' => 'Platform admin',
            'admin' => 'Facility admin',
            'provider' => 'Provider / nurse',
            'caregiver' => 'Caregiver',
            'family' => 'Family member',
            default => 'Team member',
        };
    }

    /** Menu definition per role: [group label => [[label, icon, [route names]], ...]] */
    protected static function definition(?string $role): array
    {
        return match ($role) {
            'caregiver' => [
                'My day' => [
                    ['Today', 'home', ['caregiver.dashboard']],
                    ['Visits', 'calendar', ['caregiver.visits']],
                    ['Medications', 'pill', ['caregiver.emar.index']],
                    ['Care logs', 'clipboard', ['caregiver.care-logs.index']],
                    ['My shifts', 'clock', ['caregiver.shifts.index']],
                ],
                'Account' => [
                    ['Alerts', 'bell', ['alerts.index']],
                    ['Profile', 'user', ['profile.edit']],
                ],
            ],

            'provider' => [
                'Clinical' => [
                    ['Home', 'home', ['provider.dashboard']],
                    ['Census', 'grid', ['kc.census']],
                    ['My patients', 'users', ['provider.patients.index']],
                    ['Rounds', 'stethoscope', ['provider.rounds.index']],
                    ['Calendar', 'calendar', ['provider.calendar']],
                    ['Notes', 'file-text', ['provider.notes.index']],
                    ['Smart phrases', 'sparkle', ['provider.smart.phrases']],
                    ['Care logs', 'clipboard', ['provider.care-logs.index']],
                    ['Diagnoses', 'activity', ['provider.diagnosis.index']],
                    ['Pharmacy', 'pill', ['provider.pharmacy.index', 'pharmacy.index']],
                ],
                'Team' => [
                    ['Alerts', 'bell', ['provider.alerts.index', 'alerts.index']],
                    ['Messages', 'message', ['provider.messages.index', 'messages.index']],
                    ['Tasks', 'check-square', ['provider.tasks.index']],
                ],
                'Billing' => [
                    ['Claims', 'receipt', ['provider.claims.index']],
                    ['Coding assistant', 'sparkle', ['provider.coding.assistant', 'coding.assistant']],
                    ['Compliance', 'shield', ['provider.compliance']],
                ],
            ],

            'admin' => [
                'Facility' => [
                    ['Home', 'home', ['facility.admin.home', 'dashboard']],
                    ['Census', 'grid', ['kc.census']],
                    ['Patients', 'users', ['facility.patients.index']],
                    ['Caregivers', 'heart', ['facility.caregivers.index']],
                    ['Providers', 'stethoscope', ['providers.index']],
                    ['Staff credentials', 'shield', ['kc.credentials.index']],
                    ['Family access', 'users', ['kc.family.index']],
                    ['Visits', 'calendar', ['facility.visits.index', 'visits.index']],
                    ['Shifts', 'clock', ['facility.shifts.index']],
                    ['Medication record (MAR)', 'pill', ['facility.mar.index']],
                    ['Caregiver activity', 'activity', ['facility.caregiver.activity', 'caregiver.activity']],
                    ['Provider rounds', 'repeat', ['facility-provider-cycles.index']],
                    ['Pharmacy', 'pill', ['pharmacy.index']],
                ],
                'Admissions & compliance' => [
                    ['Admission packets', 'file-text', ['facility.admission-packets.index']],
                    ['Document templates', 'clipboard', ['facility.document-templates.index']],
                    ['Compliance documents', 'shield', ['facility.compliance-documents.index']],
                    ['Inspection readiness', 'check-square', ['facility.readiness.index']],
                    ['Compliance marketplace', 'building', ['facility.compliance-marketplace.index']],
                ],
                'Team' => [
                    ['Messages', 'message', ['facility.messages.index', 'messages.index']],
                    ['Alerts', 'bell', ['alerts.index']],
                ],
                'Business' => [
                    ['Revenue', 'chart', ['facility.revenue']],
                    ['Billing', 'receipt', ['billing.index']],
                ],
            ],

            'super_admin' => [
                'Platform' => [
                    ['Overview', 'home', ['admin.dashboard']],
                    ['Facilities', 'building', ['admin.facilities.index']],
                    ['Providers', 'stethoscope', ['admin.providers.index']],
                    ['Activity', 'activity', ['admin.activity.index']],
                    ['Tasks', 'check-square', ['admin.tasks.index']],
                ],
                'Selected facility' => [
                    ['Census', 'grid', ['kc.census']],
                    ['Caregivers', 'heart', ['admin.caregivers.index']],
                    ['Clients', 'users', ['admin.clients.index']],
                    ['Visits', 'calendar', ['admin.visits.index']],
                    ['Claims', 'receipt', ['admin.claims.index']],
                    ['Staff credentials', 'shield', ['kc.credentials.index']],
                    ['Family access', 'users', ['kc.family.index']],
                ],
            ],

            'family' => [
                'My family' => [
                    ['Care updates', 'heart', ['family.dashboard']],
                    ['Profile', 'user', ['profile.edit']],
                ],
            ],

            default => [],
        };
    }

    /**
     * Resolved menu for a role.
     * Returns: [group => [['label','icon','url','route','active'], ...]]
     */
    public static function for(?string $role): array
    {
        $current = Route::currentRouteName() ?? '';
        $groups = [];

        foreach (static::definition($role) as $group => $items) {
            foreach ($items as [$label, $icon, $names]) {
                $name = collect($names)->first(fn ($n) => Route::has($n));
                if (!$name) {
                    continue;
                }

                try {
                    $url = route($name);
                } catch (\Throwable $e) {
                    continue; // route needs parameters; skip it in the menu
                }

                // "provider.notes.index" is active for provider.notes.*
                $base = preg_replace('/\.(index|dashboard)$/', '', $name);
                $active = $current === $name || ($base !== $name && str_starts_with($current, $base . '.'));

                $groups[$group][] = compact('label', 'icon', 'url', 'name', 'active');
            }
        }

        return $groups;
    }

    /** First few items for the phone bottom bar. */
    public static function tabs(?string $role, int $max = 4): array
    {
        return array_slice(array_merge(...array_values(static::for($role) ?: [[]])), 0, $max);
    }

    /** Where the logo should take this person. */
    public static function homeUrl(?string $role): string
    {
        $candidates = match ($role) {
            'super_admin' => ['admin.dashboard'],
            'admin' => ['facility.admin.home', 'dashboard'],
            'provider' => ['provider.dashboard'],
            'caregiver' => ['caregiver.dashboard'],
            'family' => ['family.dashboard'],
            default => ['dashboard'],
        };

        foreach ($candidates as $name) {
            if (Route::has($name)) {
                try {
                    return route($name);
                } catch (\Throwable $e) {
                }
            }
        }

        return url('/');
    }

    /** A page title from the current route when the page does not set one. */
    public static function pageTitle(?string $role): string
    {
        foreach (static::for($role) as $items) {
            foreach ($items as $item) {
                if ($item['active']) {
                    return $item['label'];
                }
            }
        }

        $name = Route::currentRouteName() ?? '';
        $parts = array_values(array_filter(explode('.', $name), fn ($p) => !in_array($p, ['index', 'provider', 'facility', 'admin', 'caregiver'])));
        $last = end($parts) ?: 'KASSCare';

        return ucfirst(str_replace(['-', '_'], ' ', $last));
    }
}
