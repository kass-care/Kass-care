<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientDocumentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'document' => ['required', 'file', 'max:10240'],
        ]);

        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $client = Client::findOrFail($validated['patient_id']);

        $facilityId = session('facility_id') ?? $user->facility_id ?? $client->facility_id;

        abort_if((int) $client->facility_id !== (int) $facilityId, 403, 'Unauthorized facility access.');

        $filePath = $request->file('document')->store('patient_documents', 'public');

        PatientDocument::create([
            'facility_id' => $facilityId,
            'patient_id' => $client->id,
            'title' => $validated['title'],
            'category' => $validated['category'] ?? 'General',
            'file_path' => $filePath,
            'uploaded_by' => $user->id,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function download(PatientDocument $patientDocument)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id ?? null;

        if ($facilityId) {
            abort_if((int) $patientDocument->facility_id !== (int) $facilityId, 403, 'Unauthorized facility access.');
        }

        abort_if(!Storage::disk('public')->exists($patientDocument->file_path), 404, 'Document file not found.');

        return Storage::disk('public')->download($patientDocument->file_path, $patientDocument->title);
    }

    public function destroy(PatientDocument $patientDocument)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id ?? null;

        if ($facilityId) {
            abort_if((int) $patientDocument->facility_id !== (int) $facilityId, 403, 'Unauthorized facility access.');
        }

        if (Storage::disk('public')->exists($patientDocument->file_path)) {
            Storage::disk('public')->delete($patientDocument->file_path);
        }

        $patientDocument->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
