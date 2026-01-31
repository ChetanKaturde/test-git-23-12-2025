<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasFinancialYearNumbering
{
    /**
     * Generate financial year-aware number for invoices, quotations, etc.
     */
    public static function generateFinancialYearNumber($business, $prefix = 'INV')
    {
        $fyStart = $business->financial_year_start ?? Carbon::create(null, 4, 1); // Default April 1
        $currentDate = Carbon::now();

        // Determine current financial year
        if ($currentDate->month >= $fyStart->month) {
            $fyYear = $currentDate->year . ($currentDate->year + 1);
        } else {
            $fyYear = ($currentDate->year - 1) . $currentDate->year;
        }

        // Format: INV-2526-0001
        $fyShort = substr($fyYear, 2, 2) . substr($fyYear, 6, 2);

        // Get next number for this business and financial year
        $pattern = "{$prefix}-{$fyShort}-%";
        $lastNumber = static::where('business_id', $business->id)
            ->where(static::getNumberColumn(), 'LIKE', $pattern)
            ->orderBy('id', 'desc')
            ->first();

        $nextSequence = 1;
        if ($lastNumber) {
            $lastSequence = (int) substr($lastNumber->{static::getNumberColumn()}, -4);
            $nextSequence = $lastSequence + 1;
        }

        // Ensure uniqueness by checking if the generated number already exists globally
        $column = static::getNumberColumn();
        $generatedNumber = sprintf('%s-%s-%04d', $prefix, $fyShort, $nextSequence);
        while (static::where($column, $generatedNumber)->exists()) {
            $nextSequence++;
            $generatedNumber = sprintf('%s-%s-%04d', $prefix, $fyShort, $nextSequence);
        }

        return $generatedNumber;
    }
    
    /**
     * Get the column name that stores the number
     */
    protected static function getNumberColumn()
    {
        return 'number';
    }
}