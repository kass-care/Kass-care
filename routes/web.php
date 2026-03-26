<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProviderManagementController;
use App\Http\Controllers\CaregiverManagementController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\ProviderNoteController;
use App\Http\Controllers\CaregiverDashboardController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CareLogController;
use App\Http\Controllers\PharmacyOrderController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| BASIC RESOURCES
|--------------------------------------------------------------------------
*/
Route::resource('clients', ClientController::class);
Route::resource('visits', VisitController::class);

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (ROLE BASED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'super_admin', 'admin' => redirect()->route('admin.dashboard'),
        'provider' => redirect()->route('provider.dashboard'),
        'caregiver' => redirect()->route('caregiver.dashboard'),
        default => redirect()->route('login'),
    };
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| BILLING
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
});

/*
|--------------------------------------------------------------------------
| FACILITY SELECTOR
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->post('/select-facility/{id}', function (Request $request, $id) {
    $request->session()->put('facility_id', $id);

    return redirect()->route('admin.dashboard')
        ->with('success', 'Facility selected successfully.');
})->name('facility.select');

/*
|--------------------------------------------------------------------------
| SUPER ADMIN SWITCH
|--------------------------------------------------------------------------
*/
Route::post('/superadmin/switch-facility', function (Request $request) {
    session()->put('facility_id', $request->facility_id);
    return back();
})->middleware('auth')->name('superadmin.switch.facility');

/*
|--------------------------------------------------------------------------
| ADMIN (SUPER ADMIN + ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('facilities', FacilityController::class);
        Route::resource('providers', ProviderManagementController::class);
        Route::resource('caregivers', CaregiverManagementController::class);
        Route::resource('clients', ClientController::class);
        Route::resource('visits', VisitController::class);
    });

/*
|--------------------------------------------------------------------------
| PROVIDER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:provider,admin,super_admin,superadmin'])
    ->prefix('provider')
    ->name('provider.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
        Route::get('/calendar', [ProviderDashboardController::class, 'calendar'])->name('calendar');
        Route::get('/compliance', [ProviderDashboardController::class, 'compliance'])->name('compliance');

        // NOTES
        Route::get('/notes', [ProviderNoteController::class, 'index'])->name('notes.index');
        Route::get('/notes/create', [ProviderNoteController::class, 'create'])->name('notes.create');
        Route::post('/notes', [ProviderNoteController::class, 'store'])->name('notes.store');
        Route::get('/notes/{providerNote}', [ProviderNoteController::class, 'show'])->name('notes.show');

        // CARE LOGS
        Route::get('/care-logs/create', [CareLogController::class, 'create'])->name('care-logs.create');
        Route::post('/care-logs', [CareLogController::class, 'store'])->name('care-logs.store');

        // PHARMACY
        Route::get('/pharmacy', [PharmacyOrderController::class, 'index'])->name('pharmacy.index');
        Route::get('/pharmacy/create', [PharmacyOrderController::class, 'create'])->name('pharmacy.create');
        Route::post('/pharmacy', [PharmacyOrderController::class, 'store'])->name('pharmacy.store');
        Route::post('/pharmacy/{order}/status', [PharmacyOrderController::class, 'updateStatus'])->name('pharmacy.status');
        Route::get('/pharmacy/{order}/pdf', [PharmacyOrderController::class, 'downloadPdf'])->name('pharmacy.pdf');
        Route::post('/pharmacy/{order}/email', [PharmacyOrderController::class, 'emailPrescription'])->name('pharmacy.email');
    });

/*
|--------------------------------------------------------------------------
| CAREGIVER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:caregiver'])
    ->prefix('caregiver')
    ->name('caregiver.')
    ->group(function () {

        Route::get('/dashboard', [CaregiverDashboardController::class, 'index'])->name('dashboard');

        Route::get('/care-logs', [CareLogController::class, 'index'])->name('care-logs.index');
        Route::get('/care-logs/create', [CareLogController::class, 'create'])->name('care-logs.create');
        Route::post('/care-logs', [CareLogController::class, 'store'])->name('care-logs.store');
        Route::get('/care-logs/{careLog}', [CareLogController::class, 'show'])->name('care-logs.show');

        Route::get('/check-in/{id}', [CaregiverController::class, 'checkIn'])->name('checkin');
        Route::post('/check-in/{id}', [CaregiverController::class, 'storeCheckIn'])->name('checkin.store');

        Route::get('/check-out/{id}', [CaregiverController::class, 'checkOut'])->name('checkout');
        Route::post('/check-out/{id}', [CaregiverController::class, 'storeCheckOut'])->name('checkout.save');
    });

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
