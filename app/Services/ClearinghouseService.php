<?php

namespace App\Services;

use App\Models\Claim;
use Illuminate\Support\Str;

class ClearinghouseService
{
    /**
     * Submit a claim to the clearinghouse.
     *
     * For now this is a safe mock layer.
     * Later we replace the inside with Availity / Waystar / Office Ally API.
     */
    public function submit(Claim $claim): string
    {
        return 'MOCK-' . strtoupper(Str::random(12));
    }

    /**
     * Simulate clearinghouse response.
     */
public function simulateDecision(Claim $claim): array
{
    $denials = [
        [
            'reason' => 'ICD/CPT mismatch',
            'message' => 'Submitted CPT code is not supported by diagnosis coding.',
        ],
        [
            'reason' => 'Insufficient documentation',
            'message' => 'Provider note lacks sufficient medical necessity documentation.',
        ],
        [
            'reason' => 'Duplicate claim',
            'message' => 'A similar claim was already submitted for this date of service.',
        ],
        [
            'reason' => 'Invalid place of service',
            'message' => 'POS code is not accepted for this payer type.',
        ],
        [
            'reason' => 'Coverage inactive',
            'message' => 'Patient insurance coverage inactive on service date.',
        ],
    ];

    // 🔥 smarter approval logic
    $score = 0;

    if (!empty($claim->icd_codes)) {
        $score += 1;
    }

    if (!empty($claim->cpt_code)) {
        $score += 1;
    }

    if (!empty($claim->billing_notes)) {
        $score += 1;
    }

    // good claims more likely to pass
    if ($score >= 3 && rand(1, 100) <= 85) {
        return [
            'status' => 'paid',
            'message' => 'Claim accepted and reimbursed by payer.',
        ];
    }

    $denial = $denials[array_rand($denials)];

    return [
        'status' => 'denied',
        'reason' => $denial['reason'],
        'message' => $denial['message'],
    ];
}
