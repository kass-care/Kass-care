<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{

    // GET all clients
    public function index()
    {
        return Client::all();
    }

    // POST create new client
    
public function store(Request $request)
{
    $client = Client::create([
        'organization_id' => 1,
        'name' => $request->first_name . ' ' . $request->last_name,
        'notes' => $request->notes,
        'age' => null,
        'room' => null
    ]);

    return response()->json($client);
}}