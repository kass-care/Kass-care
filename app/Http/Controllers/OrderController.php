<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Visit;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            $orders = Order::with(['client', 'creator', 'visit'])
                ->latest()
                ->get();
        } else {
            abort_if(!$user || !$user->facility_id, 403, 'No facility assigned.');

            $orders = Order::with(['client', 'creator', 'visit'])
                ->where('facility_id', $user->facility_id)
                ->latest()
                ->get();
        }

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $selectedVisit = null;
        $selectedClientId = null;

        if ($request->filled('visit_id')) {
            $visitQuery = Visit::with('client');

            if ($user->role !== 'super_admin') {
                abort_if(!$user->facility_id, 403, 'No facility assigned.');
                $visitQuery->where('facility_id', $user->facility_id);
            }

            $selectedVisit = $visitQuery->findOrFail($request->visit_id);
            $selectedClientId = $selectedVisit->client_id;
        }

        if ($user->role === 'super_admin') {
            $clients = Client::latest()->get();
        } else {
            abort_if(!$user->facility_id, 403, 'No facility assigned.');

            $clients = Client::where('facility_id', $user->facility_id)
                ->latest()
                ->get();
        }

        return view('orders.create', compact('clients', 'selectedVisit', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        if ($user->role !== 'super_admin') {
            abort_if(!$user->facility_id, 403, 'No facility assigned.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'visit_id' => 'nullable|exists:visits,id',
            'type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination' => 'nullable|string|max:255',
            'priority' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'ordered_date' => 'nullable|date',
            'follow_up_date' => 'nullable|date',
            'result_notes' => 'nullable|string',
        ]);

        $clientQuery = Client::query();
        if ($user->role !== 'super_admin') {
            $clientQuery->where('facility_id', $user->facility_id);
        }
        $client = $clientQuery->findOrFail($validated['client_id']);

        $visit = null;
        if (!empty($validated['visit_id'])) {
            $visitQuery = Visit::query();

            if ($user->role !== 'super_admin') {
                $visitQuery->where('facility_id', $user->facility_id);
            }

            $visit = $visitQuery->findOrFail($validated['visit_id']);

            abort_if(
                $visit->client_id != $client->id,
                422,
                'Selected visit does not belong to selected client.'
            );
        }

        Order::create([
            'client_id' => $validated['client_id'],
            'visit_id' => $validated['visit_id'] ?? null,
            'facility_id' => $user->role === 'super_admin' ? $client->facility_id : $user->facility_id,
            'created_by' => $user->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'destination' => $validated['destination'] ?? null,
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'ordered_date' => $validated['ordered_date'] ?? null,
            'follow_up_date' => $validated['follow_up_date'] ?? null,
            'result_notes' => $validated['result_notes'] ?? null,
        ]);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        if ($user->role !== 'super_admin') {
            abort_if(!$user->facility_id, 403, 'No facility assigned.');
            abort_if($order->facility_id != $user->facility_id, 403, 'Unauthorized.');
        }

        $order->load(['client', 'creator', 'visit']);

        return view('orders.show', compact('order'));
    }
}
