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
        $carelogs = CareLog::with(['client','caregiver'])->latest()->get();

        return view('carelogs.index', compact('carelogs'));
    }

    public function create()
    {
        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('carelogs.create', compact('clients','caregivers'));
    }

    public function store(Request $request)
    {
        CareLog::create($request->all());

        return redirect()->route('carelogs.index');
    }

    public function show($id)
    {
        $carelog = CareLog::findOrFail($id);

        return view('carelogs.show', compact('carelog'));
    }

    public function edit($id)
    {
        $carelog = CareLog::findOrFail($id);
        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('carelogs.edit', compact('carelog','clients','caregivers'));
    }

    public function update(Request $request, $id)
    {
        $carelog = CareLog::findOrFail($id);
        $carelog->update($request->all());

        return redirect()->route('carelogs.index');
    }

    public function destroy($id)
    {
        CareLog::destroy($id);

        return redirect()->route('carelogs.index');
    }
}
