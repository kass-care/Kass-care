<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;

class DocumentTemplateController extends Controller
{
    public function index()
    {
        $templates = DocumentTemplate::latest()->paginate(20);

        return view(
            'facility.document-templates.index',
            compact('templates')
        );
    }

    public function create()
    {
        return view('facility.document-templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:10'],
            'category' => ['nullable', 'string', 'max:255'],
            'content' => ['required'],
        ]);

        DocumentTemplate::create([
            'title' => $data['title'],
            'state' => $data['state'] ?? null,
            'category' => $data['category'] ?? null,
            'content' => $data['content'],
            'active' => $request->has('active'),
        ]);

        return redirect()
            ->route('facility.document-templates.index')
            ->with('success', 'Template created successfully.');
    }

    public function show(DocumentTemplate $documentTemplate)
    {
        return view(
            'facility.document-templates.show',
            compact('documentTemplate')
        );
    }
public function generatePacket(DocumentTemplate $documentTemplate)
{
    return redirect()->route('facility.admission-packets.create', [
        'template_id' => $documentTemplate->id,
    ]);
}
}
