use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CareNoteController;
use App\Http\Controllers\LabOrderController;
use App\Http\Controllers\ProviderVisitController;
use App\Http\Controllers\FacilityVisitController;

/*
|--------------------------------------------------------------------------
| Extra Kass Care Modules
|--------------------------------------------------------------------------
*/

// Assignments
Route::resource('assignments', AssignmentController::class);

// Billing
Route::resource('billing', BillingController::class);

// Care Notes
Route::resource('care-notes', CareNoteController::class);

// Lab Orders
Route::resource('lab-orders', LabOrderController::class);

// Provider Visits
Route::resource('provider-visits', ProviderVisitController::class);

// Facility Visits
Route::resource('facility-visits', FacilityVisitController::class);
