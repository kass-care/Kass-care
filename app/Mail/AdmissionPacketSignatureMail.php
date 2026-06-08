<?php

namespace App\Mail;

use App\Models\AdmissionPacket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionPacketSignatureMail extends Mailable
{
    use Queueable, SerializesModels;

    public AdmissionPacket $packet;

    public function __construct(AdmissionPacket $packet)
    {
        $this->packet = $packet;
    }

    public function build()
    {
        return $this
            ->subject('Please Sign Admission Agreement')
            ->view('emails.admission-packet-signature');
    }
}
