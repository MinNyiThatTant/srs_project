<?php

/**
 * Convert year name to number
 */
if (!function_exists('getYearNumber')) {
    function getYearNumber($yearName)
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
}

/**
 * Convert year number to name
 */
if (!function_exists('getYearNameFromNumber')) {
    function getYearNameFromNumber($yearNumber)
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
}

/**
 * Get current academic year
 */
if (!function_exists('getCurrentAcademicYear')) {
    function getCurrentAcademicYear()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }
}

/**
 * Calculate application fee
 */
if (!function_exists('calculateApplicationFee')) {
    function calculateApplicationFee($purpose, $year)
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

/**
 * Get application status text
 */
if (!function_exists('getApplicationStatusText')) {
    function getApplicationStatusText($status)
    {
        $statusMap = [
            'pending' => 'pending review',
            'payment_pending' => 'waiting for payment',
            'payment_verified' => 'payment verified',
            'academic_approved' => 'academically approved',
            'approved' => 'approved',
            'rejected' => 'rejected',
        ];
        return $statusMap[$status] ?? $status;
    }
}