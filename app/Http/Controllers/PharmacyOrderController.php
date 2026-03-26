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
        $orders = PharmacyOrder::latest()->get();
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
            'client_id' => 'required',
            'medication_name' => 'required',
        ]);

        PharmacyOrder::create([
            'client_id' => $request->client_id,
            'provider_id' => Auth::id(),
            'medication_name' => $request->medication_name,
            'dosage' => $request->dosage,
            'frequency' => $request->frequency,
            'quantity' => $request->quantity,
            'refills' => $request->refills,
            'pharmacy_name' => $request->pharmacy_name,
            'pharmacy_phone' => $request->pharmacy_phone,
            'pharmacy_fax' => $request->pharmacy_fax,
            'instructions' => $request->instructions,
            'status' => 'pending',
            'prescribed_at' => now(),
        ]);

        return redirect()->route('provider.pharmacy.index')
            ->with('success', 'Prescription created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
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
        $demoEmail = 'test@example.com';

        Mail::to($demoEmail)->send(new PharmacyPrescriptionMail($order));

        return back()->with('success', 'Prescription emailed successfully!');
    }
}
