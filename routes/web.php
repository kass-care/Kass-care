<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\CaregiverDashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\CareLogController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CaregiverActionController;
use App\Http\Controllers\ProviderNoteController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (MAIN FIX 🔥)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = Auth::user();

    if (in_array($user->role, ['admin', 'super_admin'])) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'provider') {
        return redirect()->route('provider.dashboard');
    }

    if ($user->role === 'caregiver') {
        return redirect()->route('caregiver.dashboard');
    }

    abort(403);
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Role Dashboards
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Provider Routes
Route::middleware(['auth', 'role:provider'])->prefix('provider')->group(function () {
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('provider.calendar');
    Route::get('/notes', [ProviderNoteController::class, 'index'])->name('provider.notes.index');
});

// Caregiver Routes (FIXED THE NAMES HERE)
Route::middleware(['auth', 'role:caregiver'])->group(function () {
    Route::get('/caregiver/dashboard', [CaregiverDashboardController::class, 'index'])->name('caregiver.dashboard');

    // Check-in / Check-out
    Route::get('/caregiver/check-in/{id}', [CaregiverActionController::class, 'checkIn'])->name('caregiver.checkin');
    Route::post('/caregiver/check-in/{id}', [CaregiverActionController::class, 'saveCheckIn'])->name('caregiver.checkin.save');
    
    Route::get('/caregiver/check-out/{id}', [CaregiverActionController::class, 'checkOut'])->name('caregiver.checkout');
    Route::post('/caregiver/check-out/{id}', [CaregiverActionController::class, 'saveCheckOut'])->name('caregiver.checkout.save');

    // Care Log Routes
    Route::get('/caregiver/care-log/{id}', [CaregiverActionController::class, 'showCareLog'])->name('caregiver.carelog');
    Route::post('/caregiver/care-log/{id}', [CaregiverActionController::class, 'storeCareLog'])->name('caregiver.carelog.store');

    // Visits
    Route::get('/visits/create', [VisitController::class, 'create'])->name('visits.create');
    Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
});

/*
|--------------------------------------------------------------------------
| Core Modules
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('caregivers', CaregiverController::class);
    Route::resource('facilities', FacilityController::class);
    Route::resource('visits', VisitController::class)->except(['create', 'store']);
    Route::resource('care-logs', CareLogController::class);
    
    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
