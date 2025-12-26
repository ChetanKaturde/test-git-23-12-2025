<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Material;
use App\Models\MaterialVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\State;
use App\Models\City;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        $vendors = Vendor::where('business_id', auth()->user()->business_id)
            ->orderByDesc('created_at')
            ->paginate(10);
            
        return view('vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate and sanitize vendor data
            $validatedData = $this->validateVendor($request);
            
            // Add business_id from authenticated user
            $validatedData['business_id'] = Auth::user()->business_id;

            // Create the vendor record
            $vendor = Vendor::create($validatedData);

            // Store materials if provided
            $materials = $request->input('materials');
            if (is_array($materials)) {
                $this->storeMaterials($vendor, $materials);
            }

            DB::commit();

            return redirect()->route('vendors.index')
                ->with('success', 'Vendor "' . $vendor->name . '" created successfully!');
        

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating vendor: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create vendor. Please try again.']);
        }
    }

    /**
     * Show the form for editing the specified vendor.
     */
public function edit($id)
{
    $vendor = Vendor::with('materials')->findOrFail($id);
    $materials = Material::all();

    // Pass states as name => name pairs for the dropdown
    $states = State::orderBy('name')->pluck('name', 'name');
    $cities = City::orderBy('name')->pluck('name', 'name');

    return view('vendors.edit', compact('vendor', 'materials', 'states', 'cities'));
}

    public function update(Request $request, Vendor $vendor)
    {
        try {
            DB::beginTransaction();

            // 1. Validate vendor data
            $validatedData = $this->validateVendor($request);
            $vendor->update($validatedData);

            // 2. Update pivot table with materials
            if ($request->has('materials') && is_array($request->materials)) {
                $syncData = [];

                foreach ($request->materials as $item) {
                    $materialId = $item['name'] ?? null;
                    $unitPrice = $item['price'] ?? 0;
                    $quantity = $item['quantity'] ?? 0;

                    if ($materialId) {
                        $syncData[$materialId] = [
                            'price_per_unit' => $unitPrice,
                            'min_order_qty' => $quantity,
                            'business_id' => auth()->user()->business_id
                        ];
                    }
                }

                // Sync the materials with pivot values
                $vendor->materials()->sync($syncData);
            }

            DB::commit();

            return redirect()->route('vendors.index')
                ->with('success', 'Vendor updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating vendor: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update vendor.']);
        }
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(Vendor $vendor)
    {
        try {
            DB::beginTransaction();

            // Delete related purchase orders and their batches/transactions
            foreach ($vendor->purchaseOrders as $purchaseOrder) {
                foreach ($purchaseOrder->inventoryBatches as $batch) {
                    $batch->transactions()->delete();
                }
                $purchaseOrder->inventoryBatches()->delete();
            }
            
            // Delete purchase orders
            $vendor->purchaseOrders()->delete();
            
            // Detach materials (if using pivot table)
            $vendor->materials()->detach();
            
            // Delete the vendor
            $vendor->delete();

            DB::commit();

            return redirect()->route('vendors.index')
                ->with('success', 'Vendor and related data deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting vendor: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Failed to delete vendor. Please try again.']);
        }
    }

    /**
     * Display the specified vendor.
     */
   public function show(Vendor $vendor)
{
    $vendor->load('materials', 'purchaseOrders');

    $companyState = State::find($vendor->company_state);
    $companyCity = City::find($vendor->company_city);
    $warehouseState = State::find($vendor->warehouse_state);
    $warehouseCity = City::find($vendor->warehouse_city);

    return view('vendors.show', compact('vendor', 'companyState', 'companyCity', 'warehouseState', 'warehouseCity'));
}


    /**
     * Check if a material exists in the system.
     * This is called via AJAX from the form.
     */
    public function checkMaterial(Request $request)
    {
        $request->validate([
            'material_name' => 'required|string|max:255'
        ]);

        $materialName = trim($request->material_name);
        
        // Search for the material (case-insensitive)
        $material = Material::whereRaw('LOWER(name) = ?', [strtolower($materialName)])->first();

        if ($material) {
            return response()->json([
                'status' => 'found',
                'material' => [
                    'id' => $material->id,
                    'name' => $material->name,
                    'unit' => $material->unit,
                    'gst_rate' => $material->gst_rate,
                    'sku' => $material->sku,
                    'barcode' => $material->barcode
                ]
            ]);
        } else {
            // Log the material request for admin review
            Log::info('New material requested', [
                'material_name' => $materialName,
                'requested_at' => now(),
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'status' => 'not_found',
                'message' => 'Material not found. Admin has been notified.'
            ], 404);
        }
    }

    /**
     * Store materials for a vendor - Enhanced version with material_name and gst_rate in pivot
     *
     * @param Vendor $vendor
     * @param array $materials
     */
    private function storeMaterials(Vendor $vendor, array $materials): void
    {
        $processedMaterials = [];
        $errors = [];

        foreach ($materials as $index => $materialData) {
            try {
                // Validation
                if (empty($materialData['name'])) {
                    $errors[] = "Material #{$index}: Name is required";
                    continue;
                }

                if (empty($materialData['price']) || !is_numeric($materialData['price']) || $materialData['price'] <= 0) {
                    $errors[] = "Material #{$index}: Valid price is required";
                    continue;
                }

                if (empty($materialData['quantity']) || !is_numeric($materialData['quantity']) || $materialData['quantity'] <= 0) {
                    $errors[] = "Material #{$index}: Valid quantity is required";
                    continue;
                }

                $materialName = trim($materialData['name']);
                $unitPrice = (float) $materialData['price'];
                $quantity = (int) $materialData['quantity'];

                // Find material
                $material = Material::whereRaw('LOWER(name) = ?', [strtolower($materialName)])->first();

                if ($material) {
                    // Prevent duplicate
                    if (isset($processedMaterials[$material->id])) {
                        Log::warning('Duplicate material detected for vendor', [
                            'vendor_id' => $vendor->id,
                            'material_id' => $material->id,
                            'material_name' => $materialName
                        ]);
                        continue;
                    }

                    // Store price_per_unit and min_order_qty in pivot table
                    $processedMaterials[$material->id] = [
                        'price_per_unit' => $unitPrice,
                        'min_order_qty' => $quantity,
                        'business_id' => auth()->user()->business_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    Log::info('Material processed for vendor', [
                        'vendor_id' => $vendor->id,
                        'material_id' => $material->id,
                        'material_name' => $material->name,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity
                    ]);

                } else {
                    Log::warning('Material not found during vendor creation', [
                        'vendor_id' => $vendor->id,
                        'material_name' => $materialName,
                        'price' => $unitPrice,
                        'quantity' => $quantity,
                        'index' => $index
                    ]);
                    $errors[] = "Material '{$materialName}' not found in system";
                }

            } catch (\Exception $e) {
                Log::error('Error processing material during vendor creation', [
                    'vendor_id' => $vendor->id,
                    'material_index' => $index,
                    'material_data' => $materialData,
                    'error' => $e->getMessage()
                ]);
                $errors[] = "Error processing material #{$index}: " . $e->getMessage();
            }
        }

        // Sync pivot table
        if (!empty($processedMaterials)) {
            try {
                $vendor->materials()->sync($processedMaterials);

                Log::info('Materials synced successfully for vendor', [
                    'vendor_id' => $vendor->id,
                    'materials_count' => count($processedMaterials),
                    'material_ids' => array_keys($processedMaterials)
                ]);

            } catch (\Exception $e) {
                Log::error('Error syncing materials to vendor', [
                    'vendor_id' => $vendor->id,
                    'processed_materials' => $processedMaterials,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }

        if (!empty($errors)) {
            Log::warning('Material processing errors during vendor creation', [
                'vendor_id' => $vendor->id,
                'errors' => $errors
            ]);
        }
    }

    /**
     * Validate vendor data with enhanced error messages
     */
    private function validateVendor(Request $request): array
    {
        $businessId = Auth::user()->business_id;
        $vendorId = $request->route('vendor') ? $request->route('vendor')->id : null;
        
        return $request->validate([
            // Basic Information
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'business_name' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => [
                'required', 
                'string', 
                'regex:/^[6-9]\d{9}$/', 
                'size:10',
                function ($attribute, $value, $fail) use ($businessId, $vendorId) {
                    $query = Vendor::where('phone', $value)->where('business_id', $businessId);
                    if ($vendorId) $query->where('id', '!=', $vendorId);
                    if ($query->exists()) {
                        $fail('This phone number is already used by another vendor. Please use a different number.');
                    }
                }
            ],
            
            // Company Address
            'company_address' => ['required', 'string', 'min:10', 'max:500'],
            'company_state' => ['required', 'string', 'max:100'],
            'company_city' => ['required', 'string', 'max:100'],
            'company_pincode' => ['required', 'string', 'regex:/^[0-9]{6}$/', 'size:6'],
            'company_country' => ['required', 'string', 'max:100'],
            
            // Warehouse Address
            'warehouse_address' => ['nullable', 'string', 'min:10', 'max:500'],
            'warehouse_state' => ['nullable', 'string', 'max:100'],
            'warehouse_city' => ['nullable', 'string', 'max:100'],
            'warehouse_pincode' => ['nullable', 'string', 'regex:/^[0-9]{6}$/', 'size:6'],
            'warehouse_country' => ['nullable', 'string', 'max:100'],
            
            // Bank Details
            'bank_holder_name' => ['required', 'string', 'min:2', 'max:100'],
            'branch_name' => ['required', 'string', 'min:2', 'max:100'],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => [
                'required', 
                'string', 
                'regex:/^\d{9,18}$/',
                function ($attribute, $value, $fail) use ($businessId, $vendorId) {
                    $query = Vendor::where('account_number', $value)->where('business_id', $businessId);
                    if ($vendorId) $query->where('id', '!=', $vendorId);
                    if ($query->exists()) {
                        $fail('This account number is already used by another vendor. Please use a different account number.');
                    }
                }
            ],
            'ifsc_code' => ['required', 'string', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i'],
            
            // Materials (Optional array validation)
            'materials' => ['nullable', 'array'],
            'materials.*.name' => ['nullable', 'string', 'max:255'],
            'materials.*.price' => ['nullable', 'numeric', 'min:0.01'],
            'materials.*.quantity' => ['nullable', 'integer', 'min:1'],
        ], [
            // Basic Information Messages
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters long.',
            'name.max' => 'Name must not exceed 100 characters.',
            'business_name.max' => 'Business name must not exceed 150 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email must not exceed 100 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must start with 6-9 and be exactly 10 digits.',
            'phone.size' => 'Phone number must be exactly 10 digits.',
            
            // Company Address Messages
            'company_address.required' => 'Company address is required.',
            'company_address.min' => 'Company address must be at least 10 characters long.',
            'company_address.max' => 'Company address must not exceed 500 characters.',
            'company_state.required' => 'Company state is required.',
            'company_state.max' => 'Company state must not exceed 100 characters.',
            'company_city.required' => 'Company city is required.',
            'company_city.max' => 'Company city must not exceed 100 characters.',
            'company_pincode.required' => 'Company pincode is required.',
            'company_pincode.regex' => 'Company pincode must be exactly 6 digits.',
            'company_pincode.size' => 'Company pincode must be exactly 6 digits.',
            'company_country.required' => 'Company country is required.',
            'company_country.max' => 'Company country must not exceed 100 characters.',
            
            // Warehouse Address Messages
            'warehouse_address.min' => 'Warehouse address must be at least 10 characters long.',
            'warehouse_address.max' => 'Warehouse address must not exceed 500 characters.',
            'warehouse_state.max' => 'Warehouse state must not exceed 100 characters.',
            'warehouse_city.max' => 'Warehouse city must not exceed 100 characters.',
            'warehouse_pincode.regex' => 'Warehouse pincode must be exactly 6 digits.',
            'warehouse_pincode.size' => 'Warehouse pincode must be exactly 6 digits.',
            'warehouse_country.max' => 'Warehouse country must not exceed 100 characters.',
            
            // Bank Details Messages
            'bank_holder_name.required' => 'Bank holder name is required.',
            'bank_holder_name.min' => 'Bank holder name must be at least 2 characters long.',
            'bank_holder_name.max' => 'Bank holder name must not exceed 100 characters.',
            'branch_name.required' => 'Branch name is required.',
            'branch_name.min' => 'Branch name must be at least 2 characters long.',
            'branch_name.max' => 'Branch name must not exceed 100 characters.',
            'bank_name.required' => 'Bank name is required.',
            'bank_name.max' => 'Bank name must not exceed 255 characters.',
            'account_number.required' => 'Account number is required.',
            'account_number.regex' => 'Account number must be 9 to 18 digits.',
            'ifsc_code.required' => 'IFSC code is required.',
            'ifsc_code.regex' => 'IFSC code must be 4 letters, a 0, then 6 alphanumeric characters (e.g., SBIN0001234).',
            
            // Material Messages
            'materials.*.name.max' => 'Material name must not exceed 255 characters.',
            'materials.*.price.numeric' => 'Material price must be a valid number.',
            'materials.*.price.min' => 'Material price must be greater than 0.',
            'materials.*.quantity.integer' => 'Material quantity must be a valid integer.',
            'materials.*.quantity.min' => 'Material quantity must be at least 1.',
        ]);
    }

    /**
     * Debug method to check material-vendor relationships
     */
    public function debugVendorMaterials($vendorId)
    {
        $vendor = Vendor::with('materials')->findOrFail($vendorId);
        
        $debug = [
            'vendor_id' => $vendor->id,
            'vendor_name' => $vendor->name,
            'materials_count' => $vendor->materials->count(),
            'materials' => $vendor->materials->map(function($material) {
                return [
                    'id' => $material->id,
                    'name' => $material->name,
                    'unit_price' => $material->pivot->price_per_unit ?? null,
                    'min_order_qty' => $material->pivot->min_order_qty ?? null,
                    'pivot_created_at' => $material->pivot->created_at ?? null,
                    'pivot_updated_at' => $material->pivot->updated_at ?? null,
                ];
            })
        ];

        // Check raw pivot table data
        $pivotData = DB::table('material_vendor')
            ->where('vendor_id', '=', $vendorId)
            ->get();

        $debug['raw_pivot_data'] = $pivotData;

        return response()->json($debug);
    }

    /**
     * Remove material from vendor.
     */
    public function removeMaterial(Request $request, Vendor $vendor)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id'
        ]);

        $vendor->materials()->detach($request->material_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Material removed from vendor successfully.'
        ]);
    }

    public function getMaterials($vendorId)
    {
        try {
            // Get materials directly from the pivot table
            $materials = DB::table('material_vendor')
                ->join('materials', 'material_vendor.material_id', '=', 'materials.id')
                ->where('material_vendor.vendor_id', $vendorId)
                ->where('material_vendor.business_id', auth()->user()->business_id)
                ->select(
                    'materials.id',
                    'materials.name',
                    'materials.unit',
                    'materials.gst_rate',
                    'materials.sku',
                    'material_vendor.price_per_unit as unit_price',
                    'material_vendor.min_order_qty',
                    'material_vendor.price_per_unit as quantity' // Using price as quantity for now
                )
                ->get();

            $result = $materials->map(function ($material) {
                return [
                    'id' => $material->id,
                    'name' => $material->name,
                    'unit' => $material->unit ?? 'pcs',
                    'sku' => $material->sku,
                    'gst_rate' => $material->gst_rate ?? 18,
                    'unit_price' => $material->unit_price ?? 0,
                    'min_order_qty' => $material->min_order_qty ?? 1,
                    'quantity' => 1000, // Mock available quantity for testing
                ];
            });

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Error fetching vendor materials', [
                'vendorId' => $vendorId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch materials'], 500);
        }
    }

    /**
     * Link materials to vendor with pricing and MOQ
     */
    public function linkMaterials(Request $request, Vendor $vendor)
    {
        $request->validate([
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.price_per_unit' => 'required|numeric|min:0.01',
            'materials.*.min_order_qty' => 'required|integer|min:1',
            'materials.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->materials as $materialData) {
                MaterialVendor::updateOrCreate(
                    [
                        'vendor_id' => $vendor->id,
                        'material_id' => $materialData['material_id'],
                        'business_id' => auth()->user()->business_id,
                    ],
                    [
                        'price_per_unit' => $materialData['price_per_unit'],
                        'min_order_qty' => $materialData['min_order_qty'],
                        'notes' => $materialData['notes'] ?? null,
                    ]
                );
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Materials linked successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error linking materials to vendor: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to link materials'], 500);
        }
    }

    /**
     * Get vendors for a specific material (for PO creation)
     */
    public function getVendorsForMaterial($materialId)
    {
        try {
            $vendors = MaterialVendor::where('material_id', $materialId)
                ->where('business_id', auth()->user()->business_id)
                ->with('vendor:id,name,phone,email')
                ->get()
                ->map(function ($mv) {
                    return [
                        'vendor_id' => $mv->vendor->id,
                        'vendor_name' => $mv->vendor->name,
                        'vendor_phone' => $mv->vendor->phone,
                        'vendor_email' => $mv->vendor->email,
                        'price_per_unit' => $mv->price_per_unit,
                        'min_order_qty' => $mv->min_order_qty,
                        'notes' => $mv->notes,
                    ];
                });

            return response()->json($vendors);

        } catch (\Exception $e) {
            Log::error('Error fetching vendors for material: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch vendors'], 500);
        }
    }

    public function debugTableStructure()
    {
        try {
            $hasMaterialName = Schema::hasColumn('material_vendor', 'material_name');
            $hasGstRate = Schema::hasColumn('material_vendor', 'gst_rate');

            $result = [
                'table_name' => 'material_vendor',
                'columns' => Schema::getColumnListing('material_vendor'),
                'has_material_name_column' => $hasMaterialName,
                'has_gst_rate_column' => $hasGstRate,
                'status' => (!$hasMaterialName && !$hasGstRate)
                    ? 'CORRECT - No redundant columns'
                    : 'NEEDS FIX - Redundant columns exist'
            ];

            $sampleData = DB::table('material_vendor')
                ->join('materials', 'material_vendor.material_id', '=', 'materials.id')
                ->join('vendors', 'material_vendor.vendor_id', '=', 'vendors.id')
                ->select(
                    'material_vendor.id as pivot_id',
                    'vendors.name as vendor_name',
                    'materials.name as material_name',
                    'materials.gst_rate as material_gst_rate',
                    'material_vendor.price_per_unit',
                    'material_vendor.min_order_qty'
                )
                ->limit(5)
                ->get();

            $result['sample_data'] = $sampleData;

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to debug table structure',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}