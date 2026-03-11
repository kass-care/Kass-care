<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['client','caregiver'])->latest()->get();
        return view('visits.index', compact('visits'));
    }

    public function show($id)
    {
        $visit = Visit::with(['client','caregiver'])->findOrFail($id);
        return view('visits.show', compact('visit'));
    }

    public function create()
    {
        return view('visits.create');
    }

    public function store(Request $request)
    {
        $visit = Visit::create([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'visit_date' => now(),
            'start_time' => now(),
            'status' => 'pending'
        ]);

        return redirect('/visits/'.$visit->id);
    }

    public function edit($id)
    {
        $visit = Visit::findOrFail($id);
        return view('visits.edit', compact('visit'));
    }

    public function update(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);

        $visit->update($request->all());

        return redirect('/visits/'.$visit->id);
    }

    public function destroy($id)
    {
        Visit::destroy($id);
        return redirect('/visits');
    }
}
