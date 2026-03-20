<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
public function index()
{
    $facilityId = session('facility_id');

    $query = \App\Models\Client::query();

    if ($facilityId) {
        $query->where('facility_id', $facilityId);
    }

    $clients = $query->latest()->get();

    return view('clients.index', compact('clients'));
}
    public function create() {
        return view('clients.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        
        Client::create([
            'name' => $request->name,
            'organization_id' => auth()->user()->organization_id ?? 1,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client added!');
    }
}
