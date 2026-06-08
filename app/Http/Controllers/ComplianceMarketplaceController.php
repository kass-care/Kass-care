<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;

class ComplianceMarketplaceController extends Controller
{
    public function index()
    {
        $packages = $this->packages();

        return view('facility.compliance-marketplace.index', compact('packages'));
    }

    public function install(Request $request, string $state)
    {
        $state = strtoupper($state);
        $packages = $this->packages();

        abort_unless(isset($packages[$state]), 404);

        foreach ($packages[$state]['documents'] as $document) {
            DocumentTemplate::updateOrCreate(
                [
                    'state' => $state,
                    'category' => $document['category'],
                    'title' => $document['title'],
                ],
                [
                    'content' => $document['content'],
                    'active' => true,
                ]
            );
        }

        return redirect()
            ->route('facility.document-templates.index')
            ->with('success', $packages[$state]['name'] . ' installed successfully.');
    }

    private function packages(): array
    {
        return [
            'OR' => [
                'name' => 'Oregon AFH Starter Package',
                'description' => 'Starter compliance documents for Oregon adult foster/adult family home admissions.',
                'documents' => [
                    [
                        'title' => 'Oregon Financial Agreement',
                        'category' => 'Financial Agreement',
                        'content' => "OREGON FINANCIAL AGREEMENT\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\nMonthly Rate: {{monthly_rate}}\nAdmission Date: {{admission_date}}\n\nThe resident and/or representative agrees to the monthly service rate, payment schedule, deposit terms if applicable, and responsibility for approved additional services.\n\nRepresentative Name: {{representative_name}}\nRelationship: {{representative_relationship}}\n\nSignature required.",
                    ],
                    [
                        'title' => 'Oregon Medication Consent',
                        'category' => 'Medication Consent',
                        'content' => "OREGON MEDICATION CONSENT\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\n\nThe resident and/or representative authorizes the facility to assist with medication management according to physician orders, pharmacy instructions, facility policy, and applicable law.\n\nRepresentative Name: {{representative_name}}\nRelationship: {{representative_relationship}}\n\nSignature required.",
                    ],
                    [
                        'title' => 'Oregon House Rules Agreement',
                        'category' => 'House Rules',
                        'content' => "OREGON HOUSE RULES AGREEMENT\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\nAdmission Date: {{admission_date}}\n\nThe resident and/or representative acknowledges receipt of facility house rules, including safety expectations, visitor guidelines, meal routines, smoking policy, complaint process, privacy expectations, and respectful conduct.\n\nSignature required.",
                    ],
                ],
            ],

            'WA' => [
                'name' => 'Washington AFH Starter Package',
                'description' => 'Starter compliance documents for Washington adult family home admissions.',
                'documents' => [
                    [
                        'title' => 'Washington Resident Rights',
                        'category' => 'Resident Rights',
                        'content' => "WASHINGTON RESIDENT RIGHTS\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\nAdmission Date: {{admission_date}}\n\nThe resident has rights to dignity, privacy, participation in care planning, respectful treatment, safe care, communication with representatives, and access to complaint processes.\n\nRepresentative Name: {{representative_name}}\nRelationship: {{representative_relationship}}\n\nSignature required.",
                    ],
                    [
                        'title' => 'Washington Financial Agreement',
                        'category' => 'Financial Agreement',
                        'content' => "WASHINGTON FINANCIAL AGREEMENT\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\nMonthly Rate: {{monthly_rate}}\nAdmission Date: {{admission_date}}\n\nThe resident and/or representative agrees to payment terms, service rate, billing expectations, and responsibility for approved additional services.\n\nSignature required.",
                    ],
                    [
                        'title' => 'Washington Medication Consent',
                        'category' => 'Medication Consent',
                        'content' => "WASHINGTON MEDICATION CONSENT\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\n\nThe resident and/or representative authorizes medication assistance according to provider orders, pharmacy directions, facility policy, and applicable law.\n\nSignature required.",
                    ],
                    [
                        'title' => 'Washington House Rules Agreement',
                        'category' => 'House Rules',
                        'content' => "WASHINGTON HOUSE RULES AGREEMENT\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\nAdmission Date: {{admission_date}}\n\nThe resident and/or representative acknowledges receipt of facility house rules and understands the importance of safety, privacy, respectful conduct, and communication.\n\nSignature required.",
                    ],
                ],
            ],

            'CA' => [
                'name' => 'California Starter Package',
                'description' => 'Starter package placeholder for California residential care admissions.',
                'documents' => [
                    [
                        'title' => 'California Admission Agreement',
                        'category' => 'Admission Agreement',
                        'content' => "CALIFORNIA ADMISSION AGREEMENT\n\nResident Name: {{resident_name}}\nFacility Name: {{facility_name}}\nMonthly Rate: {{monthly_rate}}\nAdmission Date: {{admission_date}}\n\nThis is a starter admission agreement placeholder and should be reviewed by the facility owner and legal advisor before official use.\n\nSignature required.",
                    ],
                ],
            ],
        ];
    }
}
