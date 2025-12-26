<?php

namespace App\Services;

class PriceCalculationService
{
    public static function calculateItemTotal($weight, $quantity, $rate, $gstRate)
    {
        $subtotal = $weight * $quantity * $rate;
        $gstAmount = ($subtotal * $gstRate) / 100;
        $total = $subtotal + $gstAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'gst_amount' => round($gstAmount, 2),
            'total' => round($total, 2)
        ];
    }

    public static function calculateOrderTotal($items)
    {
        $totalAmount = 0;
        $totalGst = 0;

        foreach ($items as $item) {
            $totalAmount += $item['subtotal'];
            $totalGst += $item['gst_amount'];
        }

        return [
            'total_amount' => round($totalAmount, 2),
            'gst_amount' => round($totalGst, 2),
            'final_amount' => round($totalAmount + $totalGst, 2)
        ];
    }
}
