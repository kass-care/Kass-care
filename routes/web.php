<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\CareLogController;
use App\Http\Controllers\LabController;

/*
|--------------------------------------------------------------------------
| Kass Care SaaS Routes
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Calendar
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

// Core Resources
Route::resource('clients', ClientController::class);
Route::resource('caregivers', CaregiverController::class);
Route::resource('visits', VisitController::class);
Route::resource('facilities', FacilityController::class);

// Medical Records
Route::resource('care-logs', CareLogController::class);
Route::resource('labs', LabController::class);
