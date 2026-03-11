<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Client;
use App\Models\Alert;

class LabController extends Controller
{

    public function index()
    {
        $labs = Lab::with('client')->latest()->get();

        return view('labs.index', compact('labs'));
    }


    public function create()
    {
        $clients = Client::all();

        return view('labs.create', compact('clients'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'test_type' => 'required',
            'priority' => 'required'
        ]);

        Lab::create([
            'client_id' => $request->client_id,
            'test_type' => $request->test_type,
            'priority' => $request->priority,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        Alert::create([
            'organization_id' => auth()->user()->organization_id ?? 1,
            'type' => 'lab',
            'message' => 'New lab result uploaded. Provider review required.'
        ]);

        return redirect('/labs')->with('success', 'Lab request created');
    }


    public function updateStatus(Request $request, $id)
    {
        $lab = Lab::findOrFail($id);

        $lab->status = $request->status;
        $lab->save();

        return redirect()->back()->with('success', 'Lab status updated');
    }

}
