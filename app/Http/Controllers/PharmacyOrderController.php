<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PharmacyOrder;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\PharmacyPrescriptionMail;

class PharmacyOrderController extends Controller
{
    public function index()
    {
        $orders = PharmacyOrder::with('client')
            ->latest()
            ->get();

        return view('provider.pharmacy.index', compact('orders'));
    }

    public function create()
    {
        $clients = Client::all();

        return view('provider.pharmacy.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'        => ['required'],
            'medication_name'  => ['required', 'string', 'max:255'],
            'dosage'           => ['nullable', 'string', 'max:255'],
            'frequency'        => ['nullable', 'string', 'max:255'],
            'quantity'         => ['nullable', 'numeric'],
            'refills'          => ['nullable', 'numeric'],
            'pharmacy_name'    => ['nullable', 'string', 'max:255'],
            'pharmacy_phone'   => ['nullable', 'string', 'max:255'],
            'pharmacy_fax'     => ['nullable', 'string', 'max:255'],
            'pharmacy_email'   => ['nullable', 'email', 'max:255'],
            'instructions'     => ['nullable', 'string'],
        ]);

        PharmacyOrder::create([
            'client_id'        => $request->client_id,
            'provider_id'      => Auth::id(),
            'medication_name'  => $request->medication_name,
            'dosage'           => $request->dosage,
            'frequency'        => $request->frequency,
            'quantity'         => $request->quantity,
            'refills'          => $request->refills,
            'pharmacy_name'    => $request->pharmacy_name,
            'pharmacy_phone'   => $request->pharmacy_phone,
            'pharmacy_fax'     => $request->pharmacy_fax,
            'pharmacy_email'   => $request->pharmacy_email,
            'instructions'     => $request->instructions,
            'status'           => 'pending',
            'prescribed_at'    => now(),
        ]);

        return redirect()
            ->route('provider.pharmacy.index')
            ->with('success', 'Prescription created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:pending,sent,filled,delivered,cancelled'],
        ]);

        $order = PharmacyOrder::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Prescription status updated successfully!');
    }

    public function downloadPdf(PharmacyOrder $order)
    {
        $pdf = Pdf::loadView('provider.pharmacy.pdf', compact('order'));

        return $pdf->download('prescription-' . $order->id . '.pdf');
    }

public function emailPrescription(PharmacyOrder $order)
{
    if (!$order->pharmacy_email) {
        return back()->with('success', 'No pharmacy email saved for this prescription.');
    }

    Mail::to($order->pharmacy_email)->send(new PharmacyPrescriptionMail($order));

    return back()->with('success', 'Prescription emailed successfully!');
}
}
