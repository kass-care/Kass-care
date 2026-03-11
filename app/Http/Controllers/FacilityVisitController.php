<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Client;

class FacilityVisitController extends Controller
{

    public function show($id)
    {
        $facility = Facility::findOrFail($id);

        $clients = Client::where('facility_id', $id)->get();

        return view('facility_visits.show', compact('facility','clients'));
    }

}
