<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBusiness;
use App\Traits\HasFinancialYearNumbering;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Invoice extends Model
{
    use LogsActivity, HasFinancialYearNumbering, BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'invoice_number',
        'work_order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_gstin',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
        'notes',
        'quotation_id',
        'sent_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'sent_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['invoice_number', 'customer_name', 'total_amount', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Invoice {$this->invoice_number} {$eventName}");
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function totalPaid()
    {
        try {
            return $this->payments()->sum('amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function balance()
    {
        return $this->total_amount - $this->totalPaid();
    }

    public function isFullyPaid()
    {
        return $this->balance() <= 0;
    }

    public function markAsSent()
    {
        $this->update([
            'sent_at' => now(),
            'status' => 'sent'
        ]);
    }

    public function isSent()
    {
        return $this->sent_at !== null;
    }

    /**
     * ✅ CRITICAL: Tell the trait to use 'invoice_number' column
     */
    protected static function getNumberColumn()
    {
        return 'invoice_number';
    }
}