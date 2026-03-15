<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Client;
use App\Http\Controllers\CaregiverController;

Route::get('/', function () {
    return redirect('/calendar');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::get('/calendar', function () {
    return view('calendar.index');
})->name('calendar');

Route::get('/calendar/events', function () {
    $visits = Visit::with('client')->get();

    $events = [];

    foreach ($visits as $visit) {
        $color = '#3b82f6';

        if ($visit->status === 'completed') {
            $color = '#16a34a';
        }

        if ($visit->status === 'missed') {
            $color = '#dc2626';
        }

        $events[] = [
            'id' => $visit->id,
            'title' => $visit->client->name ?? 'Visit',
            'start' => $visit->visit_date,
            'color' => $color,
        ];
    }

    return response()->json($events);
})->name('calendar.events');

Route::get('/visits/create', function () {
    $clients = Client::all();

    return view('visits.create', compact('clients'));
})->name('visits.create');

Route::post('/visits', function (Request $request) {
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'visit_date' => 'required|date',
    ]);

    Visit::create([
        'client_id' => $request->client_id,
        'visit_date' => $request->visit_date,
        'status' => 'scheduled',
    ]);

    return redirect()->route('calendar');
})->name('visits.store');

Route::get('/clients/create', function () {
    return view('clients.create');
})->name('clients.create');

Route::post('/clients', function () {
    return redirect()->route('dashboard');
})->name('clients.store');
Route::get('/facilities', function () {
    $facilities = \App\Models\Facility::latest()->get();

    return view('facilities.index', [
        'facilities' => $facilities,
    ]);
})->name('facilities.index');

Route::get('/facilities/create', function () {
    return view('facilities.create');
})->name('facilities.create');

Route::post('/facilities', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    \App\Models\Facility::create([
        'name' => $request->name,
    ]);

    return redirect()
        ->route('facilities.index')
        ->with('success', 'Facility added successfully.');
})->name('facilities.store');
Route::resource('caregivers', CaregiverController::class)->only([
    'index',
    'create',
    'store',
    'destroy',
]);
