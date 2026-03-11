<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->get();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        Client::create($request->all());

        return redirect()->route('clients.index');
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);

        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $client->update($request->all());

        return redirect()->route('clients.index');
    }

    public function destroy($id)
    {
        Client::destroy($id);

        return redirect()->route('clients.index');
    }
}
