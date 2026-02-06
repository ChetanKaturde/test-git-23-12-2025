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
        // Check if user has ANY quotation permission (OR logic)
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAnyQuotationPermission()) {
            \Log::warning('Quotation index access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'permissions' => auth()->user()->getPermissions(),
            ]);
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
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('create_quote')) {
            \Log::warning('Quotation create access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'permissions' => auth()->user()->getPermissions(),
            ]);
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

        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('edit_quote')) {
            \Log::warning('Quotation edit access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'permissions' => auth()->user()->getPermissions(),
            ]);
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
        // Verify business ownership
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        // Check permission FIRST
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('convert_quote_to_invoice')) {
            \Log::error('Conversion FAILED: Permission denied', [
                'user_email' => auth()->user()->email,
                'permissions' => auth()->user()->getPermissions(),
            ]);
            return back()->with('error', 'You do not have permission to convert quotations to invoices.');
        }

        // Check if already converted
        if ($quotation->status === 'converted' || Invoice::where('quotation_id', $quotation->id)->exists()) {
            return back()->with('error', 'This quotation has already been converted to an invoice.');
        }

        try {
            // Load relationships
            $quotation->load(['customer', 'items']);

            // Create invoice in transaction
            DB::transaction(function () use ($quotation) {
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
            });

            return redirect()->route('invoices.index')
                ->with('success', 'Quotation converted to invoice successfully!');

        } catch (\Throwable $e) {
            \Log::error('Quotation conversion error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return back()->with('error', 'Failed to convert quotation: ' . $e->getMessage());
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