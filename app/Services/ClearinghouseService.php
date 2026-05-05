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
    public function simulateDecision(): array
    {
        if (rand(1, 100) <= 80) {
            return [
                'status' => 'paid',
                'message' => 'Mock clearinghouse accepted and paid the claim.',
            ];
        }

        return [
            'status' => 'denied',
            'message' => 'Mock clearinghouse denied the claim for review.',
        ];
    }
}
