<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\InventoryBatch;
use App\Models\PurchaseOrder;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BarcodeController extends Controller
{
    // Cache TTL constants
    private const CACHE_TTL = 3600; // 1 hour
    private const STATS_CACHE_KEY = 'barcode_dashboard_stats';
    private const MATERIALS_CACHE_KEY = 'materials_list';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Barcode::query();

        // Optimize relationships loading - only load what's needed
        $query->with(['batch:id,material_id,purchase_order_id,batch_number', 
                     'batch.material:id,name,code', 
                     'batch.purchaseOrder:id,vendor_id', 
                     'batch.purchaseOrder.vendor:id,name']);

        // Apply filters with optimized queries
        $this->applyFilters($query, $request);

        $barcodes = $query->select([
            'id', 'barcode_number', 'material_name', 'supplier_name', 
            'status', 'expiry_date', 'created_at', 'batch_id', 
            'material_id', 'purchase_order_id'
        ])->orderBy('created_at', 'desc')
          ->paginate(20);

        // Cache materials list
        $materials = Cache::remember(self::MATERIALS_CACHE_KEY, self::CACHE_TTL, function() {
            return Material::select('id', 'name')->orderBy('name')->get();
        });

        return view('barcode.index', compact('barcodes', 'materials'));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('barcode_number', 'like', "%{$search}%")
                  ->orWhere('material_name', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', '=', $request->status);
        }

        if ($request->filled('material_id')) {
            $query->where('material_id', '=', $request->material_id);
        }

        if ($request->filled('expiry_filter')) {
            switch ($request->expiry_filter) {
                case 'expired':
                    $query->where('expiry_date', '<', now());
                    break;
                case 'expiring_soon':
                    $query->whereBetween('expiry_date', [now(), now()->addDays(7)]);
                    break;
            }
        }
    }

    public function create()
    {
        $batches = InventoryBatch::with(['material:id,name,code', 'purchaseOrder.vendor:id,name'])
                                ->where('status', '=', 'active')
                                ->select(['id', 'material_id', 'purchase_order_id', 'batch_number', 'created_at'])
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        return view('barcode.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'batch_id' => 'required|exists:inventory_batches,id',
            'barcode_type' => 'required|in:standard,qr,both',
            'print_quantity' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function() use ($validatedData) {
            $batch = InventoryBatch::with(['material:id,name,code', 'purchaseOrder.vendor:id,name', 'supplier:id,name'])
                                  ->findOrFail($validatedData['batch_id']);

            $barcodes = collect();
            $baseData = $this->prepareBarcodeBaseData($batch, $validatedData);

            for ($i = 0; $i < $validatedData['print_quantity']; $i++) {
                $barcodeNumber = Barcode::generateBarcodeNumber();
                
                if (empty($barcodeNumber) || strlen($barcodeNumber) < 3) {
                    throw new \Exception("Invalid barcode number: $barcodeNumber");
                }

                $barcodeData = array_merge($baseData, [
                    'barcode_number' => $barcodeNumber,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $barcode = Barcode::create($barcodeData);
                $barcode->qr_code_data = $barcode->generateQRData();
                $barcode->save();

                $barcodes->push($barcode);
            }

            // Clear relevant caches
            $this->clearCaches();

            return redirect()->route('barcode.index')
                           ->with('success', "Successfully generated {$barcodes->count()} barcodes.");
        });
    }

    private function prepareBarcodeBaseData($batch, $validatedData)
    {
        return [
            'batch_id' => $batch->id,
            'purchase_order_id' => $batch->purchase_order_id,
            'material_id' => $batch->material_id,
            'material_name' => optional($batch->material)->name,
            'material_code' => optional($batch->material)->code,
            'supplier_name' => $this->getSupplierName($batch),
            'quantity' => $batch->current_quantity,
            'weight' => $batch->current_weight,
            'unit_price' => $batch->unit_price,
            'expiry_date' => $batch->expiry_date,
            'storage_location' => $batch->storage_location,
            'quality_grade' => $batch->quality_grade,
            'barcode_type' => $validatedData['barcode_type'],
            'status' => 'active',
            'notes' => $validatedData['notes'],
        ];
    }

    private function getSupplierName($batch)
    {
        return optional($batch->purchaseOrder->vendor ?? $batch->supplier)->name ?? 'No Supplier';
    }

    public function show(Barcode $barcode)
    {
        $barcode->load(['batch.material:id,name,code', 'batch.purchaseOrder:id,vendor_id', 
                       'purchaseOrder:id,vendor_id', 'material:id,name,code']);
        return view('barcode.show', compact('barcode'));
    }

    public function edit(Barcode $barcode)
    {
        $barcode->load(['batch.material:id,name,code', 'batch.purchaseOrder:id', 
                       'purchaseOrder:id', 'material:id,name,code']);

        $materials = Cache::remember(self::MATERIALS_CACHE_KEY, self::CACHE_TTL, function() {
            return Material::select('id', 'name')->orderBy('name')->get();
        });

        $batches = InventoryBatch::with(['material:id,name', 'purchaseOrder:id'])
                    ->where('status', '=', 'active')
                    ->select(['id', 'material_id', 'purchase_order_id', 'batch_number', 'created_at'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('barcode.edit', compact('barcode', 'materials', 'batches'));
    }

    public function update(Request $request, Barcode $barcode)
    {
        $validatedData = $request->validate([
            'material_name' => 'nullable|string|max:255',
            'material_code' => 'nullable|string|max:100',
            'supplier_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'storage_location' => 'required|string|max:100',
            'quality_grade' => 'required|in:A,B,C,D',
            'status' => 'required|in:active,inactive,damaged,expired',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $updateData = array_filter($validatedData + [
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ]);

            $barcode->update($updateData);
            
            // Regenerate QR code if material info changed
            if (array_intersect_key($validatedData, array_flip(['material_name', 'material_code', 'supplier_name']))) {
                $barcode->qr_code_data = $barcode->generateQRData();
                $barcode->save();
            }

            $this->clearCaches();

            return redirect()->route('barcode.show', $barcode)
                            ->with('success', 'Barcode updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Barcode update failed: ' . $e->getMessage());
            return back()->withInput()
                        ->withErrors(['error' => 'Failed to update barcode.']);
        }
    }

    public function destroy(Barcode $barcode)
    {
        try {
            $barcode->update([
                'status' => 'inactive',
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ]);
            
            $this->clearCaches();
            
            return redirect()->route('barcode.index')
                            ->with('success', 'Barcode deactivated successfully!');
        } catch (\Exception $e) {
            \Log::error('Barcode deactivation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to deactivate barcode.']);
        }
    }

    public function batchPrint(Request $request)
    {
        $ids = $this->validateAndParseIds($request->input('ids'));
        
        if (empty($ids)) {
            return redirect()->route('barcode.index')
                           ->with('error', 'No valid barcodes selected for printing.');
        }
        
        $barcodes = Barcode::with(['batch.material:id,name', 'batch.purchaseOrder.vendor:id,name', 
                                  'material:id,name', 'purchaseOrder.vendor:id,name'])
                          ->whereIn('id', $ids)
                          ->get();
        
        if ($barcodes->isEmpty()) {
            return redirect()->route('barcode.index')
                           ->with('error', 'No valid barcodes found for printing.');
        }
        
        return view('barcode.batch-print', compact('barcodes'));
    }

    private function validateAndParseIds($ids)
    {
        if (empty($ids)) return [];
        
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        
        return array_filter(array_map('intval', $ids));
    }

    public function generateBarcode($number)
    {
        $cacheKey = "barcode_image_{$number}";
        
        $barcode = Cache::remember($cacheKey, self::CACHE_TTL, function() use ($number) {
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            return $generator->getBarcode($number, $generator::TYPE_CODE_128);
        });
        
        return response($barcode)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function generateQR($data)
    {
        try {
            $qrData = base64_decode($data);
            
            if (empty($qrData)) {
                throw new \Exception('Invalid QR data');
            }

            $cacheKey = "qr_code_" . md5($qrData);
            
            $qrCode = Cache::remember($cacheKey, self::CACHE_TTL, function() use ($qrData) {
                return QrCode::format('png')->size(200)->generate($qrData);
            });

            return response($qrCode)
                ->header('Content-Type', 'image/png')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            $qrCode = QrCode::format('png')->size(200)->generate('QR Code Error');
            return response($qrCode)->header('Content-Type', 'image/png');
        }
    }

/**
     * Scan a barcode and retrieve its information
     * 
     * @param Request $request Contains barcode_number to scan
     * @return \Illuminate\Http\JsonResponse Barcode data or error message
     */
    public function scan(Request $request)
{
    // Add detailed logging for debugging
    \Log::info('Scan request received', ['request_data' => $request->all()]);
    
    $validatedData = $request->validate([
        'barcode_number' => 'required|string|max:255'
    ]);

    try {
        $barcode = Barcode::with([
            'batch.material:id,name,code', 
            'batch.purchaseOrder.vendor:id,name',
            'material:id,name,code', 
            'purchaseOrder.vendor:id,name'
        ])
        ->where('barcode_number', '=', $validatedData['barcode_number'])
        ->first();

        if (!$barcode) {
            \Log::info('Barcode not found', ['barcode_number' => $validatedData['barcode_number']]);
            
            return response()->json([
                'success' => false,
                'message' => 'Barcode not found in our database.'
            ], 404);
        }

        // Increment scan count atomically and update last scanned time
        DB::transaction(function() use ($barcode) {
            $barcode->increment('scan_count');
            $barcode->update(['last_scanned_at' => now()]);
        });

        \Log::info('Barcode found and updated', ['barcode_id' => $barcode->id]);

        return response()->json([
            'success' => true,
            'data' => $this->formatBarcodeData($barcode),
            'message' => 'Barcode found successfully!'
        ]);

    } catch (\Exception $e) {
        \Log::error('Scan error: ' . $e->getMessage(), [
            'barcode_number' => $validatedData['barcode_number'],
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing the scan. Please try again.'
        ], 500);
    }
}

// Fixed formatBarcodeData method
private function formatBarcodeData($barcode)
{
    return [
        'id' => $barcode->id,
        'barcode_number' => $barcode->barcode_number,
        'material_name' => $barcode->material_name ?? optional($barcode->material)->name ?? optional($barcode->batch->material)->name ?? 'N/A',
        'material_code' => $barcode->material_code ?? optional($barcode->material)->code ?? optional($barcode->batch->material)->code ?? 'N/A',
        'batch_number' => optional($barcode->batch)->batch_number ?? 'N/A',
        'supplier' => $barcode->supplier_name ?? 
                     optional($barcode->purchaseOrder->vendor)->name ?? 
                     optional($barcode->batch->purchaseOrder->vendor)->name ?? 
                     'N/A',
        'quantity' => $barcode->quantity ?? 0,
        'weight' => $barcode->weight ?? 0,
        'unit_price' => number_format($barcode->unit_price ?? 0, 2),
        'expiry_date' => $barcode->expiry_date ? $barcode->expiry_date->format('d M, Y') : null,
        'raw_expiry_date' => $barcode->expiry_date ? $barcode->expiry_date->format('Y-m-d') : null,
        'created_at' => $barcode->created_at ? $barcode->created_at->format('d M, Y') : null,
        'storage_location' => $barcode->storage_location ?? 'N/A',
        'quality_grade' => $barcode->quality_grade ?? 'N/A',
        'status' => $barcode->status ?? 'active',
        'last_scanned_at' => $barcode->last_scanned_at ? $barcode->last_scanned_at->format('d M, Y H:i') : null,
        'scan_count' => $barcode->scan_count ?? 0,
        'view_url' => route('barcode.show', $barcode->id), // Make sure this route exists
        'notes' => $barcode->notes ?? null
    ];
}
    public function dashboard()
    {
        $stats = Cache::remember(self::STATS_CACHE_KEY, 300, function() { // 5 minutes cache
            return [
                'total_barcodes' => Barcode::count(),
                'active_barcodes' => Barcode::where('status', '=', 'active')->count(),
                'expired_items' => Barcode::where('expiry_date', '<', now())->count(),
                'expiring_soon' => Barcode::whereBetween('expiry_date', [now(), now()->addDays(7)])->count(),
                'total_scans' => Barcode::whereNotNull('last_scanned_at')->count(),
                'printed_today' => Barcode::whereDate('created_at', '=', now())->count(),
                'damaged_items' => Barcode::where('status', '=', 'damaged')->count(),
                'inactive_items' => Barcode::where('status', '=', 'inactive')->count()
            ];
        });

        $recentBarcodes = Barcode::with(['material:id,name', 'batch.material:id,name'])
                                ->select(['id', 'barcode_number', 'material_name', 'status', 'created_at', 'material_id', 'batch_id'])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        $expiringItems = Barcode::with(['material:id,name', 'batch.material:id,name'])
                               ->select(['id', 'barcode_number', 'material_name', 'expiry_date', 'material_id', 'batch_id'])
                               ->where('expiry_date', '>=', now())
                               ->where('expiry_date', '<=', now()->addDays(7))
                               ->orderBy('expiry_date')
                               ->limit(10)
                               ->get();

        $recentScans = Barcode::with(['material:id,name', 'batch.material:id,name'])
                             ->select(['id', 'barcode_number', 'material_name', 'last_scanned_at', 'material_id', 'batch_id'])
                             ->whereNotNull('last_scanned_at')
                             ->orderBy('last_scanned_at', 'desc')
                             ->limit(10)
                             ->get();

        return view('barcode.dashboard', compact('stats', 'recentBarcodes', 'expiringItems', 'recentScans'));
    }

    public function bulkAction(Request $request)
    {
        $validatedData = $request->validate([
            'action' => 'required|in:activate,deactivate,mark_damaged,delete',
            'barcode_ids' => 'required|array|min:1',
            'barcode_ids.*' => 'exists:barcodes,id'
        ]);

        try {
            $count = DB::transaction(function() use ($validatedData) {
                $updateData = ['updated_by' => Auth::id(), 'updated_at' => now()];
                
                switch ($validatedData['action']) {
                    case 'activate':
                        $updateData['status'] = 'active';
                        break;
                    case 'deactivate':
                        $updateData['status'] = 'inactive';
                        break;
                    case 'mark_damaged':
                        $updateData['status'] = 'damaged';
                        break;
                    case 'delete':
                        return Barcode::whereIn('id', $validatedData['barcode_ids'])->delete();
                }
                
                return Barcode::whereIn('id', $validatedData['barcode_ids'])->update($updateData);
            });

            $this->clearCaches();
            
            return back()->with('success', "Bulk action completed successfully on {$count} barcodes.");
        } catch (\Exception $e) {
            \Log::error('Bulk action failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Bulk action failed. Please try again.']);
        }
    }

    public function duplicate(Barcode $barcode)
    {
        try {
            $newBarcode = DB::transaction(function() use ($barcode) {
                $newBarcode = $barcode->replicate();
                $newBarcode->barcode_number = Barcode::generateBarcodeNumber();
                $newBarcode->created_by = Auth::id();
                $newBarcode->created_at = now();
                $newBarcode->updated_at = now();
                $newBarcode->save();
                
                $newBarcode->qr_code_data = $newBarcode->generateQRData();
                $newBarcode->save();
                
                return $newBarcode;
            });

            $this->clearCaches();

            return redirect()->route('barcode.show', $newBarcode)
                           ->with('success', 'Barcode duplicated successfully!');
        } catch (\Exception $e) {
            \Log::error('Barcode duplication failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to duplicate barcode.']);
        }
    }

    public function export(Request $request)
    {
        $query = Barcode::with(['batch.material:id,name', 'material:id,name', 'purchaseOrder.vendor:id,name']);
        
        // Apply filters
        $this->applyFilters($query, $request);

        $barcodes = $query->select([
            'barcode_number', 'material_name', 'material_code', 'supplier_name',
            'quantity', 'weight', 'unit_price', 'expiry_date', 'storage_location',
            'quality_grade', 'status', 'created_at'
        ])->get();

        $filename = 'barcodes_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        return response()->streamDownload(function() use ($barcodes) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Barcode Number', 'Material Name', 'Material Code', 'Supplier', 
                'Quantity', 'Weight', 'Unit Price', 'Expiry Date', 
                'Storage Location', 'Quality Grade', 'Status', 'Created At'
            ]);

            foreach ($barcodes as $barcode) {
                fputcsv($file, [
                    $barcode->barcode_number,
                    $barcode->material_name,
                    $barcode->material_code,
                    $barcode->supplier_name,
                    $barcode->quantity,
                    $barcode->weight,
                    $barcode->unit_price,
                    $barcode->expiry_date?->format('Y-m-d') ?? '',
                    $barcode->storage_location,
                    $barcode->quality_grade,
                    $barcode->status,
                    $barcode->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function clearCaches()
    {
        Cache::forget(self::STATS_CACHE_KEY);
        Cache::forget(self::MATERIALS_CACHE_KEY);
        // Clear other related caches as needed
    }
}