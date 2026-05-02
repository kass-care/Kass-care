<?php

namespace App\Services;

class ClinicalCodingService
{
    public static function suggestCodes($note, $bmi, $screenings = [])
    {
        $icd = [];
        $cpt = [];

        $note = strtolower($note);

        // ICD rules
        if (str_contains($note, 'shortness of breath')) {
            $icd[] = ['code' => 'R06.02', 'label' => 'Shortness of breath'];
        }

        if ($bmi) {
            if ($bmi >= 30) {
                $icd[] = ['code' => 'E66.9', 'label' => 'Obesity'];
            } elseif ($bmi < 18.5) {
                $icd[] = ['code' => 'R63.6', 'label' => 'Underweight'];
            }
        }

        // CPT rules (based on screening)
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

        return [
            'icd' => $icd,
            'cpt' => $cpt,
        ];
    }
}
