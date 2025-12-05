<?php

namespace App\Helpers;

class ApplicationHelpers
{
    /**
     * Convert year name to number
     */
    public static function getYearNumber($yearName)
    {
        $yearMap = [
            'first_year' => 1,
            'second_year' => 2,
            'third_year' => 3,
            'fourth_year' => 4,
            'fifth_year' => 5,
        ];
        return $yearMap[$yearName] ?? 1;
    }

    /**
     * Convert year number to name
     */
    public static function getYearNameFromNumber($yearNumber)
    {
        $yearNames = [
            1 => 'First Year',
            2 => 'Second Year',
            3 => 'Third Year',
            4 => 'Fourth Year',
            5 => 'Fifth Year',
        ];
        return $yearNames[$yearNumber] ?? 'Unknown Year';
    }

    /**
     * Get current academic year
     */
    public static function getCurrentAcademicYear()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }

    /**
     * Calculate application fee
     */
    public static function calculateApplicationFee($purpose, $year)
    {
        $baseFee = 30000;
        $additionalFees = [
            'course_registration' => 0,
            're_examination' => 10000,
            'transfer' => 20000,
            'other' => 5000,
        ];
        $yearMultipliers = [
            1 => 1.0, 2 => 1.0, 3 => 1.1, 4 => 1.2, 5 => 1.3,
        ];
        $additionalFee = $additionalFees[$purpose] ?? 0;
        $multiplier = $yearMultipliers[$year] ?? 1.0;
        return intval(($baseFee + $additionalFee) * $multiplier);
    }
}