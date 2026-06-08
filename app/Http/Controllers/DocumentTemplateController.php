<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;

class DocumentTemplateController extends Controller
{
    public function index()
    {
        $templates = DocumentTemplate::latest()->paginate(20);

        $requiredCategories = [
            'Admission Agreement',
            'Resident Rights',
            'HIPAA Notice',
            'Financial Agreement',
            'Medication Consent',
            'House Rules',
        ];

        $states = ['OR', 'WA', 'CA'];

        $complianceMatrix = [];

        foreach ($states as $state) {
            $loadedCategories = DocumentTemplate::where('active', true)
                ->where('state', $state)
                ->pluck('category')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            $complianceMatrix[$state] = [
                'loaded' => $loadedCategories,
                'missing' => array_values(array_diff($requiredCategories, $loadedCategories)),
                'total_required' => count($requiredCategories),
                'total_loaded' => count(array_intersect($requiredCategories, $loadedCategories)),
            ];
        }

        return view(
            'facility.document-templates.index',
            compact('templates', 'requiredCategories', 'states', 'complianceMatrix')
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
public function state(string $state)
{
    $templates = DocumentTemplate::where('active', true)
        ->where('state', strtoupper($state))
        ->orderBy('category')
        ->orderBy('title')
        ->get();

    return view('facility.document-templates.state', [
        'state' => strtoupper($state),
        'templates' => $templates,
    ]);
}
}
