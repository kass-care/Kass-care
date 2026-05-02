<?php

namespace App\Services;

class ClinicalCodingService
{
    public static function suggestCodes($note, $bmi, $screenings = [])
    {
        $icd = [];
        $cpt = [];

        $note = strtolower($note ?? '');

        /*
        |--------------------------------------------------------------------------
        | ICD LOGIC (Diagnosis)
        |--------------------------------------------------------------------------
        */

        // Symptom-based
        if (str_contains($note, 'shortness of breath')) {
            $icd[] = ['code' => 'R06.02', 'label' => 'Shortness of breath'];
        }

        if (str_contains($note, 'chest pain')) {
            $icd[] = ['code' => 'R07.9', 'label' => 'Chest pain'];
        }

        if (str_contains($note, 'fatigue')) {
            $icd[] = ['code' => 'R53.83', 'label' => 'Fatigue'];
        }

        /*
        |--------------------------------------------------------------------------
        | BMI → ICD
        |--------------------------------------------------------------------------
        */

        if ($bmi) {
            if ($bmi >= 30) {
                $icd[] = ['code' => 'E66.9', 'label' => 'Obesity'];
            } elseif ($bmi < 18.5) {
                $icd[] = ['code' => 'R63.6', 'label' => 'Underweight'];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SCREENINGS → ICD (Preventive)
        |--------------------------------------------------------------------------
        */

        foreach ($screenings as $item) {
            $item = strtolower($item);

            if (str_contains($item, 'depression')) {
                $icd[] = ['code' => 'Z13.31', 'label' => 'Depression screening'];
            }

            if (str_contains($item, 'cholesterol')) {
                $icd[] = ['code' => 'Z13.220', 'label' => 'Lipid disorder screening'];
            }

            if (str_contains($item, 'diabetes')) {
                $icd[] = ['code' => 'Z13.1', 'label' => 'Diabetes screening'];
            }

            if (str_contains($item, 'cancer')) {
                $icd[] = ['code' => 'Z12.9', 'label' => 'Cancer screening'];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CPT LOGIC (Billing)
        |--------------------------------------------------------------------------
        */

        foreach ($screenings as $item) {
            $item = strtolower($item);

            if (str_contains($item, 'depression')) {
                $cpt[] = ['code' => '96127', 'label' => 'Depression screening'];
            }

            if (str_contains($item, 'cholesterol')) {
                $cpt[] = ['code' => '80061', 'label' => 'Lipid panel'];
            }

            if (str_contains($item, 'vaccine')) {
                $cpt[] = ['code' => '90471', 'label' => 'Immunization admin'];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | REMOVE DUPLICATES
        |--------------------------------------------------------------------------
        */

        $icd = array_unique($icd, SORT_REGULAR);
        $cpt = array_unique($cpt, SORT_REGULAR);

        return [
            'icd' => array_values($icd),
            'cpt' => array_values($cpt),
        ];
    }
}
