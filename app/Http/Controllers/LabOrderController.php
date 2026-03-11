<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabOrder;
use App\Models\Client;

class LabOrderController extends Controller
{

    public function index()
    {
        $orders = LabOrder::with('client')->latest()->get();
        return view('lab_orders.index', compact('orders'));
    }


    public function create()
    {
        $clients = Client::all();
        return view('lab_orders.create', compact('clients'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'lab_type' => 'required',
            'instructions' => 'nullable'
        ]);

        LabOrder::create([
            'client_id' => $request->client_id,
            'lab_type' => $request->lab_type,
            'instructions' => $request->instructions,
            'status' => 'pending'
        ]);

        return redirect()->route('lab_orders.index');
    }


    public function complete($id)
    {
        $order = LabOrder::findOrFail($id);

        $order->status = 'completed';
        $order->save();

        return redirect()->route('lab_orders.index');
    }

}
