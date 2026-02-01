<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Services\PdfService;

class QuotationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyQuotationPermission()) {
            abort(403, 'You do not have permission to view quotations.');
        }

        $quotations = Quotation::with('customer')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        if (!auth()->user()->canAccessFeatureAction('quotation_management', 'create_quotation')) {
            abort(403, 'You do not have permission to create quotations.');
        }

        // Check feature limits
        if (auth()->user()->currentSubscription() && !auth()->user()->currentSubscription()->canUseFeature('quotation_management', 1)) {
            return redirect()->route('quotations.index')->with('error', 'You have reached your quotation limit. Please upgrade your plan to create more quotations.');
        }

        $customers = Customer::where('business_id', auth()->user()->business_id)
            ->where('is_active', true)
            ->get();

        $materials = Material::where('business_id', auth()->user()->business_id)
            ->where('is_active', true)
            ->get();

        return view('quotations.create', compact('customers', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'valid_until' => 'required|date|after:today',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'required|numeric|min:0|max:100',
            'items.*.tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $totalDiscount = 0;
            $totalTax = 0;

            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemDiscount = ($itemSubtotal * $item['discount_percentage']) / 100;
                $taxableAmount = $itemSubtotal - $itemDiscount;
                $itemTax = ($taxableAmount * $item['tax_rate']) / 100;
                
                $subtotal += $itemSubtotal;
                $totalDiscount += $itemDiscount;
                $totalTax += $itemTax;
            }

            $quotation = Quotation::create([
                'business_id' => auth()->user()->business_id,
                'customer_id' => $validated['customer_id'],
                'number' => $this->generateBusinessScopedNumber('QUO'),
                'status' => 'draft',
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'],
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'tax_amount' => $totalTax,
                'total' => $subtotal - $totalDiscount + $totalTax,
            ]);

            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemDiscount = ($itemSubtotal * $item['discount_percentage']) / 100;
                $taxableAmount = $itemSubtotal - $itemDiscount;
                $itemTax = ($taxableAmount * $item['tax_rate']) / 100;
                
                $quotation->items()->create([
                    'material_id' => $item['material_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'],
                    'discount_amount' => $itemDiscount,
                    'tax_rate' => $item['tax_rate'],
                    'tax_amount' => $itemTax,
                    'total' => $taxableAmount + $itemTax,
                ]);
            }

            // Increment feature usage
            if (auth()->user()->currentSubscription()) {
                auth()->user()->currentSubscription()->incrementFeatureUsage('quotation_management');
            }
        });

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        if (!auth()->user()->hasAnyQuotationPermission()) {
            abort(403, 'You do not have permission to view this quotation.');
        }

        // Add missing variable for view
        $canCreateInvoice = auth()->user()->business->canCreateInvoice();
        
        $quotation->load(['customer', 'items.material']);
        return view('quotations.show', compact('quotation', 'canCreateInvoice'));
    }

    public function markAsSent(Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $quotation->markAsSent();
        return back()->with('success', 'Quotation marked as sent.');
    }

    public function pdf(Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $quotation->load(['customer', 'items']);
        $business = auth()->user()->business;
        
        try {
            $documentData = [
                'document_number' => $quotation->number,
                'document_date' => $quotation->created_at->format('d M Y'),
                'valid_until' => $quotation->valid_until->format('d M Y'),
                'subtotal' => $quotation->subtotal,
                'discount_amount' => $quotation->discount_amount ?? 0,
                'tax_amount' => $quotation->tax_amount,
                'total_amount' => $quotation->total,
                'status' => $quotation->status ?? 'draft',
            ];
            
            $pdf = (new PdfService())->generateDocumentPdf(
                $business, 
                $quotation->items, 
                $quotation->customer, 
                false, 
                'quotation', 
                $documentData
            );
            
            $filename = 'quotation-' . $quotation->number . '.pdf';
            
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate PDF. Please try again.');
        }
    }

    public function edit(Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        if (!auth()->user()->canAccessFeatureAction('quotation_management', 'edit_quotation')) {
            abort(403, 'You do not have permission to edit quotations.');
        }

        if ($quotation->status === 'converted') {
            return redirect()->route('quotations.show', $quotation)
                ->with('error', 'Cannot edit converted quotations.');
        }

        $customers = Customer::where('business_id', auth()->user()->business_id)
            ->where('is_active', true)
            ->get();

        $materials = Material::where('business_id', auth()->user()->business_id)
            ->where('is_active', true)
            ->get();

        $quotation->load('items');

        return view('quotations.edit', compact('quotation', 'customers', 'materials'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'valid_until' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($validated, $quotation) {
            $subtotal = 0;
            $totalTax = 0;

            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemTax = ($itemSubtotal * $item['tax_rate']) / 100;
                $subtotal += $itemSubtotal;
                $totalTax += $itemTax;
            }

            $updateData = [
                'customer_id' => $validated['customer_id'],
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'],
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'total' => $subtotal + $totalTax,
            ];

            if ($quotation->isSent()) {
                $updateData['sent_at'] = null;
                $updateData['status'] = 'draft';
            }

            $quotation->update($updateData);

            $quotation->items()->delete();

            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemTax = ($itemSubtotal * $item['tax_rate']) / 100;
                
                $quotation->items()->create([
                    'material_id' => $item['material_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'],
                    'tax_amount' => $itemTax,
                    'total' => $itemSubtotal + $itemTax,
                ]);
            }
        });

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation updated successfully!');
    }

    

    public function convertToInvoice(Quotation $quotation)
    {
        try {
            // Verify business ownership
            if ($quotation->business_id !== auth()->user()->business_id) {
                abort(404);
            }

            // Load relationships
            $quotation->load(['customer', 'items']);

            // DETAILED ERROR LOGGING - Step 1: Permission Check
            \Log::info('Conversion Step 1: Permission Check', [
                'user_email' => auth()->user()->email,
                'user_role' => auth()->user()->role,
                'business_id' => auth()->user()->business_id,
                'quotation_id' => $quotation->id,
            ]);

            // Check permission
            if (!auth()->user()->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice')) {
                \Log::error('Conversion FAILED: Permission Check', [
                    'user_email' => auth()->user()->email,
                    'is_admin' => auth()->user()->isAdmin(),
                    'business_has_feature' => auth()->user()->businessHasFeature('invoice_management'),
                    'has_subscription' => auth()->user()->currentSubscription() ? 'YES' : 'NO',
                ]);
                return back()->with('error', 'PERMISSION DENIED: You do not have permission to convert quotations to invoices. Contact admin.');
            }

            // DETAILED ERROR LOGGING - Step 2: Already Converted Check
            \Log::info('Conversion Step 2: Already Converted Check', [
                'quotation_status' => $quotation->status,
                'existing_invoice' => Invoice::where('quotation_id', $quotation->id)->exists(),
            ]);

            // Check if already converted
            if ($quotation->status === 'converted' || Invoice::where('quotation_id', $quotation->id)->exists()) {
                return back()->with('error', 'ALREADY CONVERTED: This quotation has already been converted to an invoice.');
            }

            // DETAILED ERROR LOGGING - Step 3: Subscription Check
            $subscription = auth()->user()->currentSubscription();
            \Log::info('Conversion Step 3: Subscription Check', [
                'has_subscription' => $subscription ? 'YES' : 'NO',
                'subscription_id' => $subscription ? $subscription->id : null,
                'subscription_status' => $subscription ? $subscription->status : null,
            ]);

            // Check subscription
            if (!$subscription || !$subscription->isFeatureEnabled('invoice_management')) {
                \Log::error('Conversion FAILED: Subscription Check', [
                    'has_subscription' => $subscription ? 'YES' : 'NO',
                    'feature_enabled' => $subscription ? $subscription->isFeatureEnabled('invoice_management') : 'N/A',
                    'plan_features' => $subscription ? array_keys($subscription->plan_snapshot['features'] ?? []) : 'N/A',
                ]);
                return back()->with('error', 'SUBSCRIPTION ISSUE: Your plan does not support invoice creation. Please upgrade your plan.');
            }

            // DETAILED ERROR LOGGING - Step 4: Limits Check
            \Log::info('Conversion Step 4: Limits Check', [
                'can_use_feature' => $subscription->canUseFeature('invoice_management', 1),
                'feature_limit' => $subscription->getFeatureLimit('invoice_management'),
                'current_usage' => $subscription->getFeatureUsage('invoice_management'),
            ]);

            // Check limits
            if (!$subscription->canUseFeature('invoice_management', 1)) {
                return back()->with('error', 'LIMIT REACHED: Invoice limit reached. Please upgrade your plan.');
            }

            // DETAILED ERROR LOGGING - Step 5: Invoice Creation
            \Log::info('Conversion Step 5: Starting Invoice Creation', [
                'quotation_total' => $quotation->total,
                'items_count' => $quotation->items->count(),
            ]);

            // Create invoice in transaction
            DB::transaction(function () use ($quotation, $subscription) {
                $invoice = Invoice::create([
                    'business_id' => $quotation->business_id,
                    'invoice_number' => $this->generateBusinessScopedNumber('INV'),
                    'quotation_id' => $quotation->id,
                    'customer_name' => $quotation->customer->name,
                    'customer_email' => $quotation->customer->email ?? '',
                    'customer_phone' => $quotation->customer->phone ?? '',
                    'customer_address' => $quotation->customer->address ?? '',
                    'customer_gstin' => $quotation->customer->gstin ?? '',
                    'subtotal' => $quotation->subtotal,
                    'tax_amount' => $quotation->tax_amount,
                    'total_amount' => $quotation->total,
                    'status' => 'draft',
                    'issue_date' => now(),
                    'due_date' => now()->addDays(30),
                ]);

                \Log::info('Invoice Created Successfully', [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                ]);

                // Copy items
                foreach ($quotation->items as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $item->description,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit ?? 'pcs',
                        'unit_price' => $item->unit_price,
                        'tax_rate' => $item->tax_rate,
                        'tax_amount' => $item->tax_amount,
                        'total_price' => $item->total,
                    ]);
                }

                // Mark quotation as converted
                $quotation->update(['status' => 'converted', 'converted_at' => now()]);

                // Increment usage
                $subscription->incrementFeatureUsage('invoice_management');

                \Log::info('Conversion Completed Successfully', [
                    'quotation_id' => $quotation->id,
                    'invoice_id' => $invoice->id,
                ]);
            });

            // Clear cache
            \Cache::forget("business_" . auth()->user()->business_id . "_invoice_count");

            return redirect()->route('invoices.index')
                ->with('success', 'Quotation converted to invoice successfully!');

        } catch (\Throwable $e) {
            \Log::error('Quotation to Invoice conversion EXCEPTION', [
                'quotation_id' => $quotation->id ?? null,
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email ?? null,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'SYSTEM ERROR: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')');
        }
    }




    public function getItems(Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $items = $quotation->items()->with('material')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'tax_rate' => $item->tax_rate,
                'tax_amount' => $item->tax_amount,
                'total_amount' => $item->total,
                'material' => $item->material,
            ];
        });

        return response()->json(['items' => $items]);
    }

    private function generateBusinessScopedNumber($prefix)
    {
        $business = auth()->user()->business;

        if ($prefix === 'INV') {
            return Invoice::generateFinancialYearNumber($business, 'INV');
        } else {
            return Quotation::generateFinancialYearNumber($business, 'QUO');
        }
    }
}