<?php

namespace App\Http\Controllers;

use App\Models\AdmissionPacket;
use App\Models\Client;
use App\Models\DocumentTemplate;
use App\Models\AdmissionPacketDocument;
use App\Mail\AdmissionPacketSignatureMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmissionPacketController extends Controller
{
    public function index()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $packets = AdmissionPacket::with(['client', 'facility', 'creator'])
            ->where('facility_id', $facilityId)
            ->latest()
            ->paginate(10);

        return view('facility.admission-packets.index', compact('packets'));
    }

    public function create(Request $request)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $clients = Client::where('facility_id', $facilityId)
            ->orderBy('name')
            ->get();

        $selectedTemplate = null;

        if ($request->filled('template_id')) {
            $selectedTemplate = DocumentTemplate::find($request->template_id);
        }

        return view('facility.admission-packets.create', compact('clients', 'selectedTemplate'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'resident_name' => ['required', 'string', 'max:255'],
            'representative_name' => ['nullable', 'string', 'max:255'],
            'representative_relationship' => ['nullable', 'string', 'max:255'],
            'monthly_rate' => ['nullable', 'string', 'max:255'],
            'admission_date' => ['nullable', 'date'],
            'document_text' => ['nullable', 'string'],
            'template_id' => ['nullable', 'exists:document_templates,id'],
            'package_state' => ['required', 'string', 'in:WA,OR,CA'],
        ]);

        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $templateText = null;

        if (!empty($data['template_id'])) {
            $templateText = DocumentTemplate::find($data['template_id'])?->content;
        }

        $finalDocumentText = $data['document_text']
            ?: $templateText
            ?: $this->defaultAgreementText();

        $mergeValues = [
            '{{resident_name}}' => $data['resident_name'] ?? '',
            '{{representative_name}}' => $data['representative_name'] ?? '',
            '{{representative_relationship}}' => $data['representative_relationship'] ?? '',
            '{{monthly_rate}}' => $data['monthly_rate'] ?? '',
            '{{admission_date}}' => !empty($data['admission_date'])
                ? \Carbon\Carbon::parse($data['admission_date'])->format('M d, Y')
                : '',
            '{{facility_name}}' => auth()->user()->facility?->name ?? '',
        ];

        $finalDocumentText = str_replace(
            array_keys($mergeValues),
            array_values($mergeValues),
            $finalDocumentText
        );

        $packet = AdmissionPacket::create([
            'facility_id' => $facilityId,
            'client_id' => $data['client_id'] ?? null,
            'created_by' => auth()->id(),
            'packet_name' => 'Resident Admission Agreement',
            'status' => 'draft',
            'resident_name' => $data['resident_name'],
            'representative_name' => $data['representative_name'] ?? null,
            'representative_relationship' => $data['representative_relationship'] ?? null,
            'monthly_rate' => $data['monthly_rate'] ?? null,
            'admission_date' => $data['admission_date'] ?? null,
            'document_text' => $finalDocumentText,
            'public_token' => Str::uuid(),
        ]);

        $packageState = $data['package_state'];

        $templates = DocumentTemplate::where('active', true)
            ->where('state', $packageState)
            ->orderBy('id')
            ->get();

$sortOrder = 1;

foreach ($templates as $template) {
    $content = str_replace(
        array_keys($mergeValues),
        array_values($mergeValues),
        $template->content
    );

    AdmissionPacketDocument::create([
        'admission_packet_id' => $packet->id,
        'document_template_id' => $template->id,
        'title' => $template->title,
        'category' => $template->category,
        'content' => $content,
        'sort_order' => $sortOrder++,
    ]);
}
        return redirect()
            ->route('facility.admission-packets.index')
            ->with('success', 'Admission packet created successfully.');
    }
    public function show(AdmissionPacket $admissionPacket)
{
    $facilityId = session('facility_id') ?? auth()->user()->facility_id;

    abort_if(
        $admissionPacket->facility_id != $facilityId,
        403,
        'Unauthorized.'
    );

    return view(
        'facility.admission-packets.show',
        compact('admissionPacket')
    );
}
public function publicSign(string $token)
{
    $admissionPacket = AdmissionPacket::where('public_token', $token)->firstOrFail();

    return view('facility.admission-packets.public-sign', compact('admissionPacket'));
}

public function publicStoreSignature(Request $request, string $token)
{
    $admissionPacket = AdmissionPacket::where('public_token', $token)->firstOrFail();

    $data = $request->validate([
        'signed_by' => ['required', 'string', 'max:255'],
        'signature' => ['nullable', 'string', 'max:255'],
        'signature_image' => ['required', 'string'],
        'agreement_acceptance' => ['accepted'],
    ]);

    $admissionPacket->update([
        'signed_by' => $data['signed_by'],
        'signature' => $data['signature'] ?? $data['signed_by'],
        'signature_image' => $data['signature_image'],
        'signed_at' => now(),
        'status' => 'signed',
    ]);

    return view('facility.admission-packets.signed', compact('admissionPacket'));
}
    public function sendToFamily(Request $request, AdmissionPacket $admissionPacket)
{
    $facilityId = session('facility_id') ?? auth()->user()->facility_id;

    abort_if(
        $admissionPacket->facility_id != $facilityId,
        403,
        'Unauthorized.'
    );

    $data = $request->validate([
        'recipient_email' => ['required', 'email', 'max:255'],
    ]);

    Mail::to($data['recipient_email'])
        ->send(new AdmissionPacketSignatureMail($admissionPacket));

    $admissionPacket->update([
        'status' => 'sent',
        'sent_at' => now(),
    ]);

    return back()->with('success', 'Admission packet signing link sent successfully.');
}
public function downloadPdf(AdmissionPacket $admissionPacket)
{
    $facilityId = session('facility_id') ?? auth()->user()->facility_id;

    abort_if(
        $admissionPacket->facility_id != $facilityId,
        403,
        'Unauthorized.'
    );

    $pdf = Pdf::loadView('facility.admission-packets.pdf', [
        'admissionPacket' => $admissionPacket,
    ])->setPaper('letter');

    $fileName = 'admission-packet-' . str_replace(' ', '-', strtolower($admissionPacket->resident_name)) . '.pdf';

    return $pdf->download($fileName);
}
    private function defaultAgreementText(): string
    {
        return "Resident Admission Agreement\n\nThis admission packet documents the basic understanding between the adult family home, the resident, and/or the resident representative. Facility owners should review this agreement with their legal advisor and customize it according to applicable state requirements, facility policies, services provided, fees, resident rights, discharge policies, medication management, emergency procedures, and house rules.\n\nThis document is generated by KassCare as an operational template and should be reviewed before signature.";
    }
}
