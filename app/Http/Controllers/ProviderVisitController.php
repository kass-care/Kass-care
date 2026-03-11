<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderVisit;
use App\Models\Facility;
use Carbon\Carbon;

class ProviderVisitController extends Controller
{

    public function index()
    {
        $visits = ProviderVisit::with('facility')->get();

        return view('provider_visits.index', compact('visits'));
    }

    public function create()
    {
        $facilities = Facility::all();

        return view('provider_visits.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required',
            'visit_date' => 'required|date'
        ]);

        $next_visit_due = Carbon::parse($request->visit_date)->addDays(60);

        ProviderVisit::create([
            'facility_id' => $request->facility_id,
            'visit_date' => $request->visit_date,
            'next_visit_due' => $next_visit_due,
            'notes' => $request->notes
        ]);

        return redirect('/provider-visits');
    }

}
