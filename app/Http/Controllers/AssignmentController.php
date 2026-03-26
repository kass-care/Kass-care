<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Caregiver;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{

    public function create($clientId)
    {
        $client = Client::findOrFail($clientId);

        $caregivers = Caregiver::all();

        return view('assignments.create', compact('client','caregivers'));
    }



    public function store(Request $request)
    {

        $request->validate([
            'client_id' => 'required',
            'caregiver_id' => 'required'
        ]);


        DB::table('caregiver_client')->insert([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id
        ]);


        return redirect('/clients/' . $request->client_id);

    }

}
