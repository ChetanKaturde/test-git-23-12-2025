<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ActivityLog;
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
        // Only business owner can create invoices manually
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('invoices.index')
                ->with('error', 'Only business owners can create invoices directly. Convert a quotation to an invoice instead.');
        }
        
        $business = auth()->user()->business;
        
        // Check Free Plan limits
        if (!$business->canCreateInvoice()) {
            \Log::info('Free user hit invoice limit', ['business_id' => $business->id]);
            return redirect()->route('invoices.index')
                ->with('error', 'You\'ve reached your Free Plan limit of 50 invoices per month. Please upgrade to create more invoices.');
        }
        
        $workOrders = \App\Models\WorkOrder::where('business_id', auth()->user()->business_id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('invoices.create', compact('workOrders'));
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
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email',
                'amount' => 'required|numeric|min:0',
                'due_date' => 'required|date',
            ]);

            $validated['business_id'] = auth()->user()->business_id;
            $validated['status'] = 'draft';
            $validated['issue_date'] = now();
            $validated['invoice_number'] = $this->generateInvoiceNumber();
            $validated['subtotal'] = $validated['amount'];
            $validated['total_amount'] = $validated['amount'];
            $validated['tax_amount'] = 0;
            unset($validated['amount']);

            $invoice = Invoice::create($validated);
            
            // Clear cache after creating invoice
            \Cache::forget("business_{$business->id}_invoice_count");

            // Log activity
            $this->logActivity('Invoice created', "Invoice {$invoice->invoice_number} created for {$invoice->customer_name}", $invoice);

            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
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
        
        $invoice->load(['payments.createdBy']);
        
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
            \Log::error('Invoice PDF Generation Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate PDF. Please try again.');
        }
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