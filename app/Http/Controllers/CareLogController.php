<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareLog;
use App\Models\Client;
use App\Models\Caregiver;

class CareLogController extends Controller
{
    public function index()
    {
        $careLogs = CareLog::orderBy('created_at','desc')->get();

        return view('carelogs.index', compact('careLogs'));
    }

    public function create()
{
    $clients = \App\Models\Client::all();
    $caregivers = \App\Models\Caregiver::all();

    return view('care-logs.create', compact('clients','caregivers'));
}

    public function store(Request $request)
    {
        CareLog::create([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'notes' => $request->notes
        ]);

        return redirect()->route('care-logs.index');
    }

    public function show($id)
    {
        $careLog = CareLog::findOrFail($id);

        return view('carelogs.show', compact('careLog'));
    }

    public function edit($id)
    {
        $careLog = CareLog::findOrFail($id);
        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('carelogs.edit', compact('careLog','clients','caregivers'));
    }

    public function update(Request $request, $id)
    {
        $careLog = CareLog::findOrFail($id);

        $careLog->update([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'notes' => $request->notes
        ]);

        return redirect()->route('care-logs.index');
    }

    public function destroy($id)
    {
        CareLog::destroy($id);

        return redirect()->route('care-logs.index');
    }
}
