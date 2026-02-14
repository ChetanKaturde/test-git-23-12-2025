<?php

namespace App\Services;

class GstCalculationService
{
    public static function calculateGst($taxableAmount, $gstRate, $businessState, $customerState)
    {
        $gstAmount = ($taxableAmount * $gstRate) / 100;
        
        if (!$businessState || !$customerState) {
            return [
                'type' => 'unknown',
                'cgst_rate' => 0,
                'sgst_rate' => 0,
                'igst_rate' => 0,
                'cgst_amount' => 0,
                'sgst_amount' => 0,
                'igst_amount' => 0,
                'total_gst' => $gstAmount
            ];
        }
        
        $isSameState = strtolower(trim($businessState)) === strtolower(trim($customerState));
        
        if ($isSameState) {
            // Intra-state: Split into CGST + SGST
            $cgstRate = $gstRate / 2;
            $sgstRate = $gstRate / 2;
            $cgstAmount = $gstAmount / 2;
            $sgstAmount = $gstAmount / 2;
            
            return [
                'type' => 'intra_state',
                'cgst_rate' => $cgstRate,
                'sgst_rate' => $sgstRate,
                'igst_rate' => 0,
                'cgst_amount' => $cgstAmount,
                'sgst_amount' => $sgstAmount,
                'igst_amount' => 0,
                'total_gst' => $gstAmount
            ];
        } else {
            // Inter-state: IGST only
            return [
                'type' => 'inter_state',
                'cgst_rate' => 0,
                'sgst_rate' => 0,
                'igst_rate' => $gstRate,
                'cgst_amount' => 0,
                'sgst_amount' => 0,
                'igst_amount' => $gstAmount,
                'total_gst' => $gstAmount
            ];
        }
    }
}
