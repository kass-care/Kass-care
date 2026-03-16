<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Role Redirect
|--------------------------------------------------------------------------
*/

Route::get('/redirect-by-role', function () {

    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    }

    if ($user->role === 'provider') {
        return redirect('/provider/dashboard');
    }

    if ($user->role === 'caregiver') {
        return redirect('/caregiver/dashboard');
    }

    return redirect('/dashboard');

})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Role Dashboards
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/provider/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');

    Route::get('/caregiver/dashboard', [CaregiverDashboardController::class, 'index'])->name('caregiver.dashboard');

});

/*
|--------------------------------------------------------------------------
| Core Modules
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::resource('clients', ClientController::class);

    Route::resource('caregivers', CaregiverController::class);

    Route::resource('facilities', FacilityController::class);

    Route::resource('visits', VisitController::class);

    Route::resource('care-logs', CareLogController::class);

});

/*
|--------------------------------------------------------------------------
| Calendar
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:provider'])->group(function () {
    Route::get('/provider/dashboard', function () {
        return view('provider.dashboard');
    })->name('provider.dashboard');
});

Route::middleware(['auth', 'role:caregiver'])->group(function () {
    Route::get('/caregiver/dashboard', function () {
        return view('caregiver.dashboard');
    })->name('caregiver.dashboard');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:provider'])->group(function () {

    Route::get('/provider/dashboard', function () {
        return view('provider.dashboard');
    })->name('provider.dashboard');

    Route::get('/provider/calendar', [\App\Http\Controllers\ProviderCalendarController::class, 'index'])
        ->name('provider.calendar');

});

Route::middleware(['auth', 'role:caregiver'])->group(function () {

    Route::get('/caregiver/dashboard', function () {
        return view('caregiver.dashboard');
    })->name('caregiver.dashboard');

});
