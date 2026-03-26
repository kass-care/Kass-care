<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients
     */
    public function index()
    {
        $facilityId = session('facility_id');

        $clients = Client::when($facilityId, function ($query) use ($facilityId) {
            return $query->where('facility_id', $facilityId);
        })->latest()->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show form to create client
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store new client
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'facility_id' => session('facility_id'), // 🔥 KEY LINE
        ]);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created successfully');
    }

    /**
     * Show single client
     */
    public function show(Client $client)
    {
        $this->authorizeClient($client);

        return view('clients.show', compact('client'));
    }

    /**
     * Edit client
     */
    public function edit(Client $client)
    {
        $this->authorizeClient($client);

        return view('clients.edit', compact('client'));
    }

    /**
     * Update client
     */
    public function update(Request $request, Client $client)
    {
        $this->authorizeClient($client);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully');
    }

    /**
     * Delete client
     */
    public function destroy(Client $client)
    {
        $this->authorizeClient($client);

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully');
    }

    /**
     * 🔐 Protect client by facility
     */
    private function authorizeClient(Client $client)
    {
        $facilityId = session('facility_id');

        if ($facilityId && $client->facility_id != $facilityId) {
            abort(403, 'Unauthorized access to this client.');
        }
    }
}
