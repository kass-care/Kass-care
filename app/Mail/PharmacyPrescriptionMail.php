<?php

namespace App\Mail;

use App\Models\PharmacyOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class PharmacyPrescriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(PharmacyOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $pdf = Pdf::loadView('provider.pharmacy.pdf', ['order' => $this->order]);

        return $this->subject('Prescription for ' . ($this->order->client->name ?? 'Client'))
            ->view('emails.pharmacy_prescription')
            ->attachData(
                $pdf->output(),
                'prescription-' . $this->order->id . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
