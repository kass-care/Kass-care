<?php

namespace App\Http\Controllers;

use App\Models\ComplianceDocument;
use App\Models\ReadinessItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;

class ComplianceDocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');
       
$documents = ComplianceDocument::with(['readinessItem', 'uploader', 'client']) 
    ->where('facility_id', $facilityId)
    ->latest()
    ->get();
$clients = Client::where('facility_id', $facilityId)
    ->orderBy('name')
    ->get();

$expiredDocuments = $documents->filter(function ($document) {
    return $document->expires_at && $document->expires_at->isPast();
});

$expiringSoonDocuments = $documents->filter(function ($document) {
    return $document->expires_at
        && !$document->expires_at->isPast()
        && now()->diffInDays($document->expires_at, false) <= 30;
});

        $readinessItems = ReadinessItem::where('facility_id', $facilityId)
            ->orderBy('category')
            ->orderBy('title')
            ->get();
       return view('facility.compliance-documents.index', compact(
    'documents',
    'readinessItems',
    'clients',
    'expiredDocuments',
    'expiringSoonDocuments'
));

    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'readiness_item_id' => ['nullable', 'exists:readiness_items,id'],
           'client_id' => ['nullable', 'exists:clients,id'],
            'expires_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ]);

        $file = $request->file('document');
        $path = $file->store("facility-compliance/{$facilityId}", 'public');

        ComplianceDocument::create([
            'facility_id' => $facilityId,
            'readiness_item_id' => $validated['readiness_item_id'] ?? null,
           'client_id' => $validated['client_id'] ?? null,
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'expires_at' => $validated['expires_at'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => $user->id,
        ]);

        return back()->with('success', 'Compliance document uploaded successfully.');
    }

    public function destroy(ComplianceDocument $document)
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if((int) $document->facility_id !== (int) $facilityId, 403);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Compliance document deleted.');
    }
}
