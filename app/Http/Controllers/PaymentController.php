<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $invoice = Invoice::where('business_id', auth()->user()->business_id)
                         ->findOrFail($request->invoice_id);

        if ($request->amount > $invoice->balance()) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed invoice balance.']);
        }

        Payment::create([
            'invoice_id' => $invoice->id,
            'business_id' => auth()->user()->business_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'reference_no' => $request->reference_no,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        // Update invoice status if fully paid
        if ($invoice->isFullyPaid()) {
            $invoice->update([
                'status' => 'paid',
                'paid_date' => now()
            ]);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }
}