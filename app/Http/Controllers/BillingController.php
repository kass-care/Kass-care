<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Client;
use App\Models\Caregiver;

class BillingController extends Controller
{

    public function index()
    {
        $visits = Visit::with(['client','caregiver'])
            ->where('status','completed')
            ->get();

        $invoices = [];

        foreach ($visits as $visit) {

            $start = strtotime($visit->start_time);
            $end = strtotime($visit->end_time);

            $hours = round(($end - $start) / 3600,2);

            $rate = 25; // hourly rate

            $amount = $hours * $rate;

            $invoices[] = [
                'client' => $visit->client->name ?? 'Unknown',
                'caregiver' => $visit->caregiver->name ?? 'Unknown',
                'date' => $visit->visit_date,
                'hours' => $hours,
                'rate' => $rate,
                'amount' => $amount
            ];
        }

        return view('billing.index', compact('invoices'));
    }

}
