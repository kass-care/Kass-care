<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientDocument;
use Illuminate\Support\Facades\Storage;

class PatientDocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'document' => 'required|file|max:10240'
        ]);

        $filePath = $request->file('document')->store('patient_documents','public');

        PatientDocument::create([
            'facility_id' => session('facility_id'),
            'patient_id' => $request->patient_id,
            'title' => $request->title,
            'category' => $request->category,
            'file_path' => $filePath,
            'uploaded_by' => auth()->id()
        ]);

        return back()->with('success','Document uploaded successfully.');
    }

    public function download($id)
    {
        $document = PatientDocument::findOrFail($id);

        return Storage::disk('public')->download($document->file_path);
    }
}
