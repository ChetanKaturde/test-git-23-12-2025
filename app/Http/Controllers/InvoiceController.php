<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ActivityLog;
use App\Models\Quotation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Services\PdfService;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $businessId = auth()->user()->business_id;
            $invoices = Invoice::where('business_id', $businessId)->latest()->get();
            return view('invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            \Log::error('Invoice index error: ' . $e->getMessage());
            $invoices = collect();
            return view('invoices.index', compact('invoices'));
        }
    }

    public function create()
    {
        $business = auth()->user()->business;

        // Check Free Plan limits
        if (!$business->canCreateInvoice()) {
            \Log::info('Free user hit invoice limit', ['business_id' => $business->id]);
            return redirect()->route('invoices.index')
                ->with('error', 'You\'ve reached your Free Plan limit of 50 invoices per month. Please upgrade to create more invoices.');
        }

        // Get quotations that can be converted to invoices (draft and sent status)
        $quotations = Quotation::where('business_id', auth()->user()->business_id)
            ->whereIn('status', ['draft', 'sent'])
            ->whereNull('converted_at')
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invoices.create', compact('quotations'));
    }

    public function store(Request $request)
    {
        try {
            $business = auth()->user()->business;

            // Check Free Plan limits
            if (!$business->canCreateInvoice()) {
                \Log::info('Free user hit invoice limit', ['business_id' => $business->id]);
                return back()->withInput()
                    ->with('error', 'You\'ve reached your Free Plan limit of 50 invoices per month. Please upgrade to create more invoices.');
            }

            $validated = $request->validate([
                'quotation_id' => 'required|exists:quotations,id',
                'due_date' => 'required|date|after:today',
            ]);

            // Get the quotation
            $quotation = Quotation::where('business_id', auth()->user()->business_id)
                ->where('id', $validated['quotation_id'])
                ->whereIn('status', ['draft', 'sent'])
                ->whereNull('converted_at')
                ->with('items', 'customer')
                ->first();

            if (!$quotation) {
                return back()->withInput()
                    ->with('error', 'Selected quotation is not available for conversion to invoice.');
            }

            // Check if quotation already has an invoice
            if ($quotation->invoice) {
                return back()->withInput()
                    ->with('error', 'This quotation has already been converted to an invoice.');
            }

            // Create invoice data from quotation
            $invoiceData = [
                'business_id' => auth()->user()->business_id,
                'quotation_id' => $quotation->id,
                'customer_name' => $quotation->customer->name,
                'customer_email' => $quotation->customer->email,
                'customer_phone' => $quotation->customer->phone,
                'customer_address' => $quotation->customer->address,
                'customer_gstin' => $quotation->customer->gstin,
                'status' => 'draft',
                'issue_date' => now(),
                'due_date' => $validated['due_date'],
                'invoice_number' => $this->generateInvoiceNumber(),
                'subtotal' => $quotation->subtotal,
                'tax_amount' => $quotation->tax_amount,
                'total_amount' => $quotation->total,
            ];

            $invoice = Invoice::create($invoiceData);

            // Create invoice items from quotation items
            foreach ($quotation->items as $quotationItem) {
                $invoice->items()->create([
                    'description' => $quotationItem->description,
                    'quantity' => $quotationItem->quantity,
                    'unit_price' => $quotationItem->unit_price,
                    'tax_rate' => $quotationItem->tax_rate,
                    'tax_amount' => $quotationItem->tax_amount,
                    'total_amount' => $quotationItem->total,
                ]);
            }

            // Update quotation status to converted
            $quotation->status = 'converted';
            $quotation->converted_at = now();
            $quotation->save();

            // Clear cache after creating invoice
            \Cache::forget("business_{$business->id}_invoice_count");

            // Log activity
            $this->logActivity('Invoice created', "Invoice {$invoice->invoice_number} created from quotation {$quotation->number}", $invoice);

            return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully from quotation!');
        } catch (\Exception $e) {
            \Log::error('Invoice creation error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create invoice.');
        }
    }

    public function show(Invoice $invoice)
    {
        // Ensure the invoice belongs to the current user's business
        if ($invoice->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        $invoice->load(['payments.createdBy', 'quotation']);
        
        return view('invoices.show', compact('invoice'));
    }

    public function markAsPaid(Invoice $invoice)
    {
        try {
            if ($invoice->business_id !== auth()->user()->business_id) {
                abort(404);
            }
            
            $invoice->update(['status' => 'paid']);
            
            // Log activity
            $this->logActivity('Invoice paid', "Invoice {$invoice->invoice_number} marked as paid", $invoice);
            
            return redirect()->back()->with('success', 'Invoice marked as paid!');
        } catch (\Exception $e) {
            \Log::error('Invoice update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update invoice status.');
        }
    }

    public function markAsSent(Invoice $invoice)
    {
        if ($invoice->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $invoice->markAsSent();
        
        // Log activity
        $this->logActivity('Invoice sent', "Invoice {$invoice->invoice_number} marked as sent", $invoice);
        
        return back()->with('success', 'Invoice marked as sent.');
    }

    public function pdf(Invoice $invoice)
    {
        if ($invoice->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $business = auth()->user()->business;
        
        try {
            $customer = (object) [
                'name' => $invoice->customer_name,
                'email' => $invoice->customer_email,
                'phone' => $invoice->customer_phone ?? '',
                'address' => $invoice->customer_address ?? '',
                'gstin' => $invoice->customer_gstin ?? '',
            ];
            
            $documentData = [
                'document_number' => $invoice->invoice_number,
                'document_date' => $invoice->issue_date ? $invoice->issue_date->format('d M Y') : now()->format('d M Y'),
                'due_date' => $invoice->due_date ? $invoice->due_date->format('d M Y') : null,
                'subtotal' => $invoice->subtotal,
                'tax_amount' => $invoice->tax_amount,
                'total_amount' => $invoice->total_amount,
            ];
            
            $pdf = (new PdfService())->generateDocumentPdf(
                $business,
                $invoice->items ?? [],
                $customer,
                false,
                'invoice',
                $documentData
            );
            
            $filename = 'invoice-' . $invoice->invoice_number . '.pdf';
            
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function paymentReceipt(Payment $payment)
    {
        if ($payment->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $invoice = $payment->invoice;
        $business = auth()->user()->business;

        $data = [
            'business' => $business,
            'invoice' => $invoice,
            'payment' => $payment,
            'customer' => (object) [
                'name' => $invoice->customer_name,
                'email' => $invoice->customer_email,
                'phone' => $invoice->customer_phone,
                'address' => $invoice->customer_address,
                'gstin' => $invoice->customer_gstin,
            ],
        ];

        $pdf = PDF::loadView('pdfs.receipt', $data);
        $filename = 'receipt-' . $invoice->invoice_number . '-' . $payment->id . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Generate business-scoped invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $business = auth()->user()->business;
        return Invoice::generateFinancialYearNumber($business, 'INV');
    }

    /**
     * Log activity for audit trail
     */
    private function logActivity($event, $description, $subject = null, $additionalProperties = [])
    {
        try {
            ActivityLog::create([
                'log_name' => 'invoice',
                'description' => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject ? $subject->id : null,
                'causer_type' => 'App\Models\User',
                'causer_id' => auth()->id(),
                'event' => $event,
                'properties' => array_merge([
                    'business_id' => auth()->user()->business_id,
                    'timestamp' => now()->toISOString()
                ], $additionalProperties)
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity logging failed: ' . $e->getMessage());
        }
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        // Only allow editing of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }
        
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        // Only allow editing of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }
        
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'edit_reason' => 'required|string|max:500',
        ]);
        
        // Store original values for audit
        $originalData = [
            'customer_name' => $invoice->customer_name,
            'customer_email' => $invoice->customer_email,
            'total_amount' => $invoice->total_amount,
            'due_date' => $invoice->due_date->format('Y-m-d'),
        ];
        
        // Update invoice
        $invoice->update([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'subtotal' => $validated['amount'],
            'total_amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
        ]);
        
        // Log the edit with reason
        $this->logActivity(
            'Invoice edited', 
            "Invoice {$invoice->invoice_number} edited. Reason: {$validated['edit_reason']}", 
            $invoice,
            ['original' => $originalData, 'new' => $validated, 'reason' => $validated['edit_reason']]
        );
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }
}