<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\CaregiverDashboardController;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\CareLogController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\LabController;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});


/*
|--------------------------------------------------------------------------
| Dashboards
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/provider-dashboard', [ProviderDashboardController::class, 'index'])->name('provider-dashboard.index');

Route::get('/caregiver-dashboard', [CaregiverDashboardController::class, 'index'])->name('caregiver-dashboard.index');


/*
|--------------------------------------------------------------------------
| Core Modules
|--------------------------------------------------------------------------
*/

Route::resource('clients', ClientController::class);
Route::resource('caregivers', CaregiverController::class);
Route::resource('visits', VisitController::class);
Route::resource('care-logs', CareLogController::class);
Route::resource('schedules', ScheduleController::class);
Route::resource('facilities', FacilityController::class);
Route::resource('users', UserController::class);
Route::resource('alerts', AlertController::class);
Route::resource('labs', LabController::class);
