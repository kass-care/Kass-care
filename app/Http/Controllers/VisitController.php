<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Client;
use App\Models\Caregiver;

class VisitController extends Controller
{

    public function index()
    {
        $visits = Visit::latest()->get();

        return view('visits.index', compact('visits'));
    }


    public function create()
    {
        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('visits.create', compact('clients','caregivers'));
    }


    public function store(Request $request)
    {
        Visit::create($request->all());

        return redirect()->route('visits.index')
            ->with('success','Visit created successfully');
    }


    public function show($id)
    {
        $visit = Visit::findOrFail($id);

        return view('visits.show', compact('visit'));
    }


    public function edit($id)
    {
        $visit = Visit::findOrFail($id);

        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('visits.edit', compact('visit','clients','caregivers'));
    }


    public function update(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);

        $visit->update($request->all());

        return redirect()->route('visits.index')
            ->with('success','Visit updated');
    }


    public function destroy($id)
    {
        Visit::destroy($id);

        return redirect()->route('visits.index')
            ->with('success','Visit deleted');
    }

}
