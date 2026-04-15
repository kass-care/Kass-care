<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillingController;
use Laravel\Cashier\Http\Controllers\WebhookController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProviderManagementController;
use App\Http\Controllers\CaregiverManagementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\EvvController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PatientTimelineController;
use App\Http\Controllers\PatientDocumentController;

use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\ProviderPatientController;
use App\Http\Controllers\ProviderRoundsController;
use App\Http\Controllers\ProviderNoteController;
use App\Http\Controllers\ProviderAlertController;
use App\Http\Controllers\PharmacyOrderController;

use App\Http\Controllers\CaregiverDashboardController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\ProviderCalendarController;

use App\Http\Controllers\CareLogController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\FacilityProviderCycleController;
use App\Http\Controllers\AdminActivityController;
use App\Http\Controllers\ClaimLedgerController;
use App\Http\Controllers\FacilityOnboardingController;

use App\Http\Controllers\FacilityProviderController;
use App\Http\Controllers\Facility\PatientController;
use App\Http\Controllers\Facility\CaregiverController as FacilityCaregiverController;
use App\Http\Controllers\FacilityVisitController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/register-facility', [FacilityOnboardingController::class, 'create'])
    ->name('register-facility');

Route::post('/register-facility', [FacilityOnboardingController::class, 'store'])
    ->name('register-facility.store');

Route::view('/terms', 'legal.terms')->name('terms');

Route::get('/', function () {
    return view('welcome');
});
/*
Auth scaffolding
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Facility context switch + dashboard redirects
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/select-facility/{facility}', function (\App\Models\Facility $facility) {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        // Caregivers do not switch facility context manually
        if ($user->role === 'caregiver') {
            abort(403, 'Caregivers cannot switch facility context.');
        }

        // Save selected facility in session
        session([
            'facility_id'   => $facility->id,
            'facility_name' => $facility->name,
        ]);

        // Role-aware redirect
        return match ($user->role) {
            'super_admin' => redirect()
                ->route('admin.dashboard')
                ->with('success', 'You are now managing: ' . $facility->name),

            'admin' => redirect()
                ->route('facility.admin.home')
                ->with('success', 'You are now managing: ' . $facility->name),

            'provider' => redirect()
                ->route('provider.dashboard')
                ->with('success', 'Facility context changed to: ' . $facility->name),

            default => redirect()
                ->route('dashboard'),
        };
    })->name('select.facility');

    Route::post('/clear-facility-context', function () {
        session()->forget(['facility_id', 'facility_name']);

        return back()->with('success', 'Facility context cleared.');
    })->name('clear.facility');

    Route::get('/redirect-by-role', function () {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        return match ($user->role) {
            'super_admin' => redirect()->route('admin.dashboard'),

            'admin' => redirect()->route('facility.admin.home'),

            'provider' => redirect()->route('provider.dashboard'),

            'caregiver' => redirect()->route('caregiver.dashboard'),

            default => redirect()->route('login'),
        };
    })->name('redirect.by.role');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        return match ($user->role) {
            'super_admin' => redirect()->route('admin.dashboard'),

            'admin' => redirect()->route('facility.admin.home'),

            'provider' => redirect()->route('provider.dashboard'),

            'caregiver' => redirect()->route('caregiver.dashboard'),

            default => redirect()->route('login'),
        };
    })->name('dashboard');

    Route::get('/debug-user', function () {
        return response()->json([
            'id'                  => auth()->user()?->id,
            'name'                => auth()->user()?->name,
            'email'               => auth()->user()?->email,
            'role'                => auth()->user()?->role,
            'facility_id'         => auth()->user()?->facility_id,
            'session_facility_id' => session('facility_id'),
            'session_facility_name' => session('facility_name'),
        ]);
    })->name('debug.user');
});

/*
|--------------------------------------------------------------------------
| Facility selector
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->get('/facility-admin/home', function () {
    $user = auth()->user();

    if (!$user || $user->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    $facilityId = session('facility_id') ?? $user->facility_id;

    if (!$facilityId) {
        return view('admin.facility-home', [
            'facility' => null,
            'clientsCount' => 0,
            'caregiversCount' => 0,
            'visitsCount' => 0,
            'providersCount' => 0,
        ]);
    }

    $facility = \App\Models\Facility::find($facilityId);

    $clientsCount = \App\Models\Patient::where('facility_id', $facilityId)->count();

    $caregiversCount = \App\Models\User::where('facility_id', $facilityId)
        ->where('role', 'caregiver')
        ->count();

    $visitsCount = \App\Models\Visit::where('facility_id', $facilityId)->count();

    $providersCount = \App\Models\User::where('facility_id', $facilityId)
        ->where('role', 'provider')
        ->count();

    return view('admin.facility-home', [
        'facility' => $facility,
        'clientsCount' => $clientsCount,
        'caregiversCount' => $caregiversCount,
        'visitsCount' => $visitsCount,
        'providersCount' => $providersCount,
    ]);
})->name('facility.admin.home');
/*
|--------------------------------------------------------------------------
| Admin platform routes - Super Admin only
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('facilities', FacilityController::class);
        Route::resource('providers', ProviderManagementController::class);

        Route::middleware(['facility.context'])->group(function () {
            Route::resource('caregivers', CaregiverManagementController::class);
            Route::resource('clients', ClientController::class);
            Route::resource('visits', VisitController::class);
            Route::resource('claims', ClaimLedgerController::class);
        });

        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/activity', [AdminActivityController::class, 'index'])->name('activity.index');
    });

/*
|--------------------------------------------------------------------------
| Facility provider cycles - 60 day facility visit engine
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:provider,admin,super_admin'])->group(function () {
    Route::get('/facility-cycles', [FacilityProviderCycleController::class, 'index'])
        ->name('facility-provider-cycles.index');

    Route::get('/facility-cycles/create', [FacilityProviderCycleController::class, 'create'])
        ->name('facility-provider-cycles.create');

    Route::post('/facility-cycles', [FacilityProviderCycleController::class, 'store'])
        ->name('facility-provider-cycles.store');

    Route::get('/facility-cycles/{facilityProviderCycle}/edit', [FacilityProviderCycleController::class, 'edit'])
        ->name('facility-provider-cycles.edit');

    Route::put('/facility-cycles/{facilityProviderCycle}', [FacilityProviderCycleController::class, 'update'])
        ->name('facility-provider-cycles.update');

    Route::delete('/facility-cycles/{facilityProviderCycle}', [FacilityProviderCycleController::class, 'destroy'])
        ->name('facility-provider-cycles.destroy');
});

/*
|--------------------------------------------------------------------------
| Facility routes - Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('facility')
    ->name('facility.')
    ->group(function () {
        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');
        Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');

        Route::get('/caregivers', [FacilityCaregiverController::class, 'index'])->name('caregivers.index');
        Route::get('/caregivers/create', [FacilityCaregiverController::class, 'create'])->name('caregivers.create');
        Route::post('/caregivers', [FacilityCaregiverController::class, 'store'])->name('caregivers.store');
        Route::get('/caregivers/{id}/edit', [FacilityCaregiverController::class, 'edit'])->name('caregivers.edit');
        Route::put('/caregivers/{id}', [FacilityCaregiverController::class, 'update'])->name('caregivers.update');
        Route::delete('/caregivers/{id}', [FacilityCaregiverController::class, 'destroy'])->name('caregivers.destroy');

        Route::get('/visits', [FacilityVisitController::class, 'index'])->name('visits.index');
        Route::get('/visits/create', [FacilityVisitController::class, 'create'])->name('visits.create');
        Route::post('/visits', [FacilityVisitController::class, 'store'])->name('visits.store');
        Route::get('/facility/visits/{visit}', [FacilityVisitController::class, 'show'])->name('facility.visits.show');
        Route::get('/facility/visits/{visit}', [FacilityVisitController::class, 'show'])->name('facility.visits.show');

        Route::get('/providers', [FacilityProviderController::class, 'index'])->name('providers.index');
        Route::post('/providers', [FacilityProviderController::class, 'store'])->name('providers.store');
    });

/*
|--------------------------------------------------------------------------
| Basic visits shortcut by role
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/visits', function () {
        $user = auth()->user();

        return match ($user->role) {
            'super_admin' => redirect()->route('admin.visits.index'),
            'caregiver'   => redirect()->route('caregiver.visits'),
            'provider'    => redirect()->route('provider.dashboard'),
            'admin'       => redirect()->route('facility.admin.home'),
            default       => redirect()->route('dashboard'),
        };
    })->name('visits.index.redirect');
});

/*
|--------------------------------------------------------------------------
| Provider
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:provider,super_admin', 'check.subscription'])
    ->prefix('provider')
    ->name('provider.')
    ->group(function () {
        Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
        Route::get('/calendar', [ProviderCalendarController::class, 'index'])->name('calendar');
        Route::get('/compliance', [ProviderDashboardController::class, 'compliance'])->name('compliance');
        Route::get('/alerts', [ProviderDashboardController::class, 'alerts'])->name('alerts');
        Route::get('/summary', [ProviderDashboardController::class, 'summary'])->name('summary');

        Route::post('/care-logs/{id}/review', [ProviderDashboardController::class, 'markReviewed'])
            ->name('care-logs.review');

        Route::middleware(['auth', 'role:provider,super_admin'])->group(function () {
            Route::get('/alerts/list', [ProviderAlertController::class, 'index'])->name('alerts.index');
            Route::post('/alerts/{careLog}/review', [ProviderAlertController::class, 'markReviewed'])
                ->name('alerts.review');
        });

        Route::get('/patients/{id}', [ProviderPatientController::class, 'index'])
            ->name('patients.workspace');

        Route::get('/patients/{id}/summary', [ProviderPatientController::class, 'summary'])
            ->name('patients.summary');
 /*
|--------------------------------------------------------------------------
| Provider Care Logs
|--------------------------------------------------------------------------
*/

Route::get('/care-logs', [CareLogController::class, 'index'])
    ->name('care-logs.index');

Route::get('/care-logs/create', [CareLogController::class, 'create'])
    ->name('care-logs.create');

Route::post('/care-logs', [CareLogController::class, 'store'])
    ->name('care-logs.store');

Route::get('/care-logs/{careLog}', [CareLogController::class, 'show'])
    ->name('care-logs.show');       

       /*
        |--------------------------------------------------------------------------
        | Patient Documents
        |--------------------------------------------------------------------------
        */

        Route::post('/patient-documents', [PatientDocumentController::class, 'store'])
            ->name('patient-documents.store');

        Route::get('/patient-documents/{patientDocument}/download', [PatientDocumentController::class, 'download'])
            ->name('patient-documents.download');

        Route::delete('/patient-documents/{patientDocument}', [PatientDocumentController::class, 'destroy'])
            ->name('patient-documents.destroy');

        Route::get('/rounds', [ProviderRoundsController::class, 'index'])->name('rounds.index');
        Route::post('/rounds/visit/mark-rounded', [ProviderRoundsController::class, 'markRounded'])
            ->name('rounds.markRounded');

        Route::get('/diagnoses', [DiagnosisController::class, 'index'])->name('diagnosis.index');

        Route::get('/notes', [ProviderNoteController::class, 'index'])->name('notes.index');
        Route::get('/notes/create', [ProviderNoteController::class, 'create'])->name('notes.create');
        Route::post('/notes', [ProviderNoteController::class, 'store'])->name('notes.store');
        Route::get('/notes/{providerNote}', [ProviderNoteController::class, 'show'])->name('notes.show');

        Route::get('/care-logs', [CareLogController::class, 'index'])->name('care-logs.index');
        Route::get('/care-logs/create', [CareLogController::class, 'create'])->name('care-logs.create');
        Route::post('/care-logs', [CareLogController::class, 'store'])->name('care-logs.store');
        Route::get('/care-logs/{careLog}', [CareLogController::class, 'show'])->name('care-logs.show');

        Route::get('/pharmacy', [PharmacyOrderController::class, 'index'])->name('pharmacy.index');
        Route::get('/pharmacy/create', [PharmacyOrderController::class, 'create'])->name('pharmacy.create');
        Route::post('/pharmacy/store', [PharmacyOrderController::class, 'store'])->name('pharmacy.store');
        Route::post('/pharmacy/{id}/status', [PharmacyOrderController::class, 'updateStatus'])->name('pharmacy.status');
        Route::post('/pharmacy/order/{pdf}', [PharmacyOrderController::class, 'downloadPdf'])->name('pharmacy.pdf');
        Route::post('/pharmacy/order/prescription/email', [PharmacyOrderController::class, 'email'])->name('pharmacy.email');

        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    });
/*
|--------------------------------------------------------------------------
| Caregiver
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:caregiver'])
    ->prefix('caregiver')
    ->name('caregiver.')
    ->group(function () {

        Route::get('/dashboard', [CaregiverDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/visits', [CaregiverController::class, 'visits'])
            ->name('visits');

        Route::get('/check-in/{id}', [CaregiverController::class, 'checkIn'])
            ->name('checkin');

        Route::post('/check-in/{id}', [CaregiverController::class, 'storeCheckIn'])
            ->name('checkin.store');

        Route::get('/check-out/{id}', [CaregiverController::class, 'checkOut'])
            ->name('checkout');

        Route::post('/check-out/{id}', [CaregiverController::class, 'storeCheckOut'])
            ->name('visits.checkout');

        Route::get('/care-logs', [CareLogController::class, 'index'])
            ->name('care-logs.index');

        Route::get('/care-logs/create', [CareLogController::class, 'create'])
            ->name('care-logs.create');

        Route::post('/care-logs', [CareLogController::class, 'store'])
            ->name('care-logs.store');

        Route::get('/care-logs/{careLog}', [CareLogController::class, 'show'])
            ->name('care-logs.show');
Route::get('/check-out/{visit}', [CaregiverController::class, 'checkOut'])
    ->name('checkout');

Route::post('/check-out/{visit}', [CaregiverController::class, 'checkoutSave'])
    ->name('checkout.save');
    });
/*
|--------------------------------------------------------------------------
| Profile + alerts
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/notifications/fetch', [AlertController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/read-all', [AlertController::class, 'markAllRead'])->name('notifications.readAll');
});

/*
|--------------------------------------------------------------------------
| Compliance dashboard
|--------------------------------------------------------------------------
*/

Route::get('/compliance', [ComplianceController::class, 'index'])
    ->middleware('auth')
    ->name('compliance.dashboard');

/*
|--------------------------------------------------------------------------
| Client diagnosis routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/clients/{client}/diagnoses/create', [DiagnosisController::class, 'create'])
        ->name('diagnoses.create');

    Route::post('/clients/{client}/diagnoses', [DiagnosisController::class, 'store'])
        ->name('diagnoses.store');

    Route::get('/clients/{client}/diagnoses/{diagnosis}/edit', [DiagnosisController::class, 'edit'])
        ->name('diagnoses.edit');

    Route::put('/clients/{client}/diagnoses/{diagnosis}', [DiagnosisController::class, 'update'])
        ->name('diagnoses.update');

    Route::delete('/clients/{client}/diagnoses/{diagnosis}', [DiagnosisController::class, 'destroy'])
        ->name('diagnoses.destroy');
});

/*
|--------------------------------------------------------------------------
| Client medication routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/clients/{client}/medications/create', [MedicationController::class, 'create'])
        ->name('medications.create');

    Route::post('/clients/{client}/medications', [MedicationController::class, 'store'])
        ->name('medications.store');
});

/*
|--------------------------------------------------------------------------
| Client timeline
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/clients/{client}/timeline', [PatientTimelineController::class, 'show'])
        ->name('patient.timeline.show');
});

/*
|--------------------------------------------------------------------------
| Billing
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/notice', [BillingController::class, 'notice'])->name('billing.notice');
});

/*
|--------------------------------------------------------------------------
| Stripe webhook
|--------------------------------------------------------------------------
*/

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])
    ->name('cashier.webhook');
Route::post('/select-facility/{facility}', function (\App\Models\Facility $facility) {
    $user = auth()->user();

    abort_if(!$user, 403, 'Unauthorized.');

    if (in_array($user->role, ['super_admin', 'admin'])) {
        session([
            'facility_id' => $facility->id,
            'facility_name' => $facility->name,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'You are now managing: ' . $facility->name);
    }

    if ($user->role === 'provider') {
        session([
            'facility_id' => $facility->id,
            'facility_name' => $facility->name,
        ]);

        return redirect()->route('provider.dashboard')
            ->with('success', 'Facility context changed to: ' . $facility->name);
    }

    if ($user->role === 'caregiver') {
        abort(403, 'Caregivers cannot switch facility context.');
    }

    abort(403, 'Access denied.');
})->middleware('auth')->name('select.facility');
Route::post('/clear-facility-context', function () {
    session()->forget(['facility_id', 'facility_name']);
    return redirect('/admin/dashboard');
})->middleware('auth')->name('facility.clear');
