<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToBusiness;

class Expense extends Model
{
    use HasFactory, SoftDeletes, BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'category',
        'amount',
        'expense_date',
        'description',
        'payment_mode',
        'proof_file_path',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function getCategoryDisplayName()
    {
        $categories = [
            'staff_payroll' => 'Staff Payroll',
            'rent_property_expense' => 'Rent / Property Expense',
            'electricity_utilities' => 'Electricity / Utilities',
            'equipment_assets' => 'Equipment / Assets',
            'miscellaneous' => 'Miscellaneous',
        ];

        return $categories[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getPaymentModeDisplayName()
    {
        $modes = [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'upi' => 'UPI',
            'card' => 'Card',
            'cheque' => 'Cheque',
            'other' => 'Other',
        ];

        return $modes[$this->payment_mode] ?? ucfirst(str_replace('_', ' ', $this->payment_mode));
    }

    // Scope for filtering
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, $fromDate, $toDate)
    {
        return $query->whereBetween('expense_date', [$fromDate, $toDate]);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('expense_date', $year)
                    ->whereMonth('expense_date', $month);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('expense_date', $year);
    }
}
