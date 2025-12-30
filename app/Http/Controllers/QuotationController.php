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
        $quotations = Quotation::with('customer')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();
        
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
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
            'pdf_options' => 'nullable|array',
            'pdf_options.show_discount' => 'nullable|boolean',
            'pdf_options.show_list_price' => 'nullable|boolean',
            'pdf_options.show_hsn' => 'nullable|boolean',
            'pdf_options.show_tax_breakdown' => 'nullable|boolean',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
            'items.*.list_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'required|numeric|min:0|max=100',
            'items.*.tax_rate' => 'required|numeric|min:0|max=100',
        ]);

        DB::transaction(function () use ($validated) {
            $business = auth()->user()->business;
            $customer = Customer::find($validated['customer_id']);
            $isIntraState = $business->state === $customer->state;

            $subtotal = 0;
            $totalTax = 0;

            foreach ($validated['items'] as $item) {
                $netPrice = $item['list_price'] * (1 - $item['discount_percentage'] / 100);
                $taxableValue = $netPrice * $item['quantity'];

                if ($isIntraState) {
                    $cgstAmount = $taxableValue * ($item['tax_rate'] / 2) / 100;
                    $sgstAmount = $cgstAmount;
                    $igstAmount = 0;
                } else {
                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = $taxableValue * $item['tax_rate'] / 100;
                }

                $itemTax = $cgstAmount + $sgstAmount + $igstAmount;
                $itemTotal = $taxableValue + $itemTax;

                $subtotal += $taxableValue;
                $totalTax += $itemTax;
            }

            $quotation = Quotation::create([
                'business_id' => auth()->user()->business_id,
                'customer_id' => $validated['customer_id'],
                'number' => $this->generateBusinessScopedNumber('QUO'),
                'status' => 'draft',
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'],
                'pdf_options' => $validated['pdf_options'] ?? [],
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'total' => $subtotal + $totalTax,
            ]);

            foreach ($validated['items'] as $item) {
                $netPrice = $item['list_price'] * (1 - $item['discount_percentage'] / 100);
                $taxableValue = $netPrice * $item['quantity'];

                if ($isIntraState) {
                    $cgstAmount = $taxableValue * ($item['tax_rate'] / 2) / 100;
                    $sgstAmount = $cgstAmount;
                    $igstAmount = 0;
                } else {
                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = $taxableValue * $item['tax_rate'] / 100;
                }

                $itemTax = $cgstAmount + $sgstAmount + $igstAmount;
                $itemTotal = $taxableValue + $itemTax;

                $quotation->items()->create([
                    'material_id' => $item['material_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'unit_price' => $netPrice, // For backward compatibility
                    'list_price' => $item['list_price'],
                    'discount_percentage' => $item['discount_percentage'],
                    'net_price' => $netPrice,
                    'hsn_code' => $item['hsn_code'] ?? null,
                    'taxable_value' => $taxableValue,
                    'cgst_amount' => $cgstAmount,
                    'sgst_amount' => $sgstAmount,
                    'igst_amount' => $igstAmount,
                    'tax_rate' => $item['tax_rate'],
                    'tax_amount' => $itemTax,
                    'total' => $itemTotal,
                ]);
            }
        });

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $quotation->load(['customer', 'items.material']);
        return view('quotations.show', compact('quotation'));
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
            ];
            
            $pdf = (new PdfService())->generateDocumentPdf(
                $business,
                $quotation->items,
                $quotation->customer,
                false,
                'quotation',
                $documentData,
                $quotation
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

        if (!in_array($quotation->status, ['draft', 'sent'])) {
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
            'pdf_options' => 'nullable|array',
            'pdf_options.show_discount' => 'nullable|boolean',
            'pdf_options.show_list_price' => 'nullable|boolean',
            'pdf_options.show_hsn' => 'nullable|boolean',
            'pdf_options.show_tax_breakdown' => 'nullable|boolean',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
            'items.*.list_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'required|numeric|min:0|max=100',
            'items.*.tax_rate' => 'required|numeric|min:0|max=100',
        ]);

        DB::transaction(function () use ($validated, $quotation) {
            $business = auth()->user()->business;
            $customer = Customer::find($validated['customer_id']);
            $isIntraState = $business->state === $customer->state;

            $subtotal = 0;
            $totalTax = 0;

            foreach ($validated['items'] as $item) {
                $netPrice = $item['list_price'] * (1 - $item['discount_percentage'] / 100);
                $taxableValue = $netPrice * $item['quantity'];

                if ($isIntraState) {
                    $cgstAmount = $taxableValue * ($item['tax_rate'] / 2) / 100;
                    $sgstAmount = $cgstAmount;
                    $igstAmount = 0;
                } else {
                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = $taxableValue * $item['tax_rate'] / 100;
                }

                $itemTax = $cgstAmount + $sgstAmount + $igstAmount;
                $itemTotal = $taxableValue + $itemTax;

                $subtotal += $taxableValue;
                $totalTax += $itemTax;
            }

            $updateData = [
                'customer_id' => $validated['customer_id'],
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'],
                'pdf_options' => $validated['pdf_options'] ?? [],
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
                $netPrice = $item['list_price'] * (1 - $item['discount_percentage'] / 100);
                $taxableValue = $netPrice * $item['quantity'];

                if ($isIntraState) {
                    $cgstAmount = $taxableValue * ($item['tax_rate'] / 2) / 100;
                    $sgstAmount = $cgstAmount;
                    $igstAmount = 0;
                } else {
                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = $taxableValue * $item['tax_rate'] / 100;
                }

                $itemTax = $cgstAmount + $sgstAmount + $igstAmount;
                $itemTotal = $taxableValue + $itemTax;

                $quotation->items()->create([
                    'material_id' => $item['material_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'unit_price' => $netPrice, // For backward compatibility
                    'list_price' => $item['list_price'],
                    'discount_percentage' => $item['discount_percentage'],
                    'net_price' => $netPrice,
                    'hsn_code' => $item['hsn_code'] ?? null,
                    'taxable_value' => $taxableValue,
                    'cgst_amount' => $cgstAmount,
                    'sgst_amount' => $sgstAmount,
                    'igst_amount' => $igstAmount,
                    'tax_rate' => $item['tax_rate'],
                    'tax_amount' => $itemTax,
                    'total' => $itemTotal,
                ]);
            }
        });

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation updated successfully!');
    }

    public function convertToInvoice(Quotation $quotation)
    {
        if ($quotation->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        if ($quotation->status === 'converted') {
            return back()->with('error', 'Quotation already converted to invoice.');
        }
        
        $business = auth()->user()->business;
        
        // Check Free Plan limits
        if (!$business->canCreateInvoice()) {
            \Log::info('Free user hit invoice limit on conversion', ['business_id' => $business->id]);
            return back()->with('error', 'You\'ve reached your Free Plan limit of 50 invoices per month. Please upgrade to convert more quotations.');
        }

        try {
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
                    'pdf_options' => $quotation->pdf_options,
                ]);

                foreach ($quotation->items as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $item->description,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'unit_price' => $item->unit_price,
                        'list_price' => $item->list_price,
                        'discount_percentage' => $item->discount_percentage,
                        'net_price' => $item->net_price,
                        'hsn_code' => $item->hsn_code,
                        'taxable_value' => $item->taxable_value,
                        'cgst_amount' => $item->cgst_amount,
                        'sgst_amount' => $item->sgst_amount,
                        'igst_amount' => $item->igst_amount,
                        'tax_rate' => $item->tax_rate,
                        'tax_amount' => $item->tax_amount,
                        'total_price' => $item->total,
                    ]);
                }

                $quotation->update(['status' => 'converted']);
            });
            
            // Clear cache after creating invoice
            \Cache::forget("business_{$business->id}_invoice_count");

            return redirect()->route('quotations.show', $quotation)
                ->with('success', 'Quotation converted to invoice successfully!');
        } catch (\Exception $e) {
            \Log::error('Convert to invoice error: ' . $e->getMessage());
            return back()->with('error', 'Failed to convert quotation to invoice.');
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