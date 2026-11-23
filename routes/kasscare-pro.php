<?php

/*
|--------------------------------------------------------------------------
| KASSCare Pro features
|--------------------------------------------------------------------------
| Family portal, family access management and staff credentials.
| Loaded at the end of routes/web.php.
*/

use App\Http\Controllers\KassPro\CensusController;
use App\Http\Controllers\KassPro\CredentialController;
use App\Http\Controllers\KassPro\FamilyAccessController;
use App\Http\Controllers\KassPro\FamilyPortalController;
use Illuminate\Support\Facades\Route;

// Family members (read-only portal)
Route::middleware(['auth', 'role:family'])
    ->prefix('family')
    ->group(function () {
        Route::get('/', [FamilyPortalController::class, 'index'])->name('family.dashboard');
    });

// Agency tools (facility admin and platform admin)
Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('agency')
    ->name('kc.')
    ->group(function () {
        Route::get('/family-access', [FamilyAccessController::class, 'index'])->name('family.index');
        Route::post('/family-access', [FamilyAccessController::class, 'store'])->name('family.store');
        Route::delete('/family-access/{user}', [FamilyAccessController::class, 'destroy'])->name('family.destroy');

        Route::get('/credentials', [CredentialController::class, 'index'])->name('credentials.index');
        Route::post('/credentials', [CredentialController::class, 'store'])->name('credentials.store');
        Route::delete('/credentials/{credential}', [CredentialController::class, 'destroy'])->name('credentials.destroy');
    });

// Census board (agency staff and providers working in a facility)
Route::middleware(['auth', 'role:admin,super_admin,provider'])
    ->get('/census', [CensusController::class, 'index'])
    ->name('kc.census');
