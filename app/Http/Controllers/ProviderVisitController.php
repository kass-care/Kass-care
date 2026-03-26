<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderVisit;
use App\Models\Client;

class ProviderVisitController extends Controller
{

    public function index()
    {
        $provider_visits = ProviderVisit::with('client')->get();

        return view('provider-visits.index', compact('provider_visits'));
    }


    public function create()
    {
        $clients = Client::all();

        return view('provider-visits.create', compact('clients'));
    }


    public function store(Request $request)
    {

        ProviderVisit::create([

            'client_id' => $request->client_id,
            'provider_name' => $request->provider_name,
            'visit_date' => $request->visit_date,
            'notes' => $request->notes

        ]);

        return redirect('/provider-visits')
            ->with('success','Provider visit created successfully');

    }


    public function show($id)
    {
        $provider_visit = ProviderVisit::findOrFail($id);

        return view('provider-visits.show', compact('provider_visit'));
    }


    public function edit($id)
    {
        $provider_visit = ProviderVisit::findOrFail($id);
        $clients = Client::all();

        return view('provider-visits.edit', compact('provider_visit','clients'));
    }


    public function update(Request $request, $id)
    {

        $provider_visit = ProviderVisit::findOrFail($id);

        $provider_visit->update([

            'client_id' => $request->client_id,
            'provider_name' => $request->provider_name,
            'visit_date' => $request->visit_date,
            'notes' => $request->notes

        ]);

        return redirect('/provider-visits')
            ->with('success','Provider visit updated successfully');

    }


    public function destroy($id)
    {
        $provider_visit = ProviderVisit::findOrFail($id);

        $provider_visit->delete();

        return redirect('/provider-visits')
            ->with('success','Provider visit deleted successfully');
    }

}
