<?php
namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials.
     */
  
    public function index()
    {
        $materials = Material::where('business_id', auth()->user()->business_id)
            ->with('inventoryBatches')
            ->orderByDesc('created_at')->paginate(10);
        return view('materials.index', compact('materials'));
    }
  
    /**
     * Get material details by barcode scan
     */
    public function getByBarcode(Request $request)
    {
        try {
            $request->validate([
                'barcode' => 'required|string|max:50'
            ]);

            $material = Material::where('barcode', $request->barcode)
                ->where('business_id', auth()->user()->business_id)
                ->first();
        
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found with this barcode'
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching material by barcode', [
                'barcode' => $request->barcode ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching material'
            ], 500);
        }

        return response()->json([
            'success' => true,
           'material' => [
    'id' => $material->id,
    'name' => $material->name,
    'code' => $material->code,
    'sku' => $material->sku,
    'barcode' => $material->barcode,
    'description' => $material->description,
    'unit' => $material->unit,
    'unit_price' => $material->unit_price,
    'gst_rate' => $material->gst_rate,
    'category' => $material->category,
    'is_active' => $material->is_active,
    'dimensions' => $material->dimensions, // Return as JSON object
    'created_at' => $material->created_at,
    'updated_at' => $material->updated_at
]

        ]);
    }

    /**
     * Search materials by SKU, barcode, name, or code
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:100'
        ]);
        
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        $materials = Material::where('business_id', auth()->user()->business_id)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('code', 'LIKE', '%' . $query . '%')
                  ->orWhere('sku', 'LIKE', '%' . $query . '%')
                  ->orWhere('barcode', '=', $query);
            })
            ->select('id', 'name', 'code', 'sku', 'barcode', 'unit', 'unit_price', 'is_active', 'dimensions')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'materials' => $materials
        ]);
    }

    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        return view('materials.create');
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateMaterial($request);
            
            // Add business_id
            $validatedData['business_id'] = auth()->user()->business_id;
            
            // Generate SKU and Barcode
            $validatedData['sku'] = $this->generateSKU($validatedData);
            $validatedData['barcode'] = $this->generateBarcode();
            
            // Process dimensions data
            $validatedData = $this->processDimensions($validatedData, $request);
            
            $material = Material::create($validatedData);
            
            \Log::info('Material created successfully', [
                'material_id' => $material->id,
                'sku' => $material->sku,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('materials.index')
                ->with('success', 'Commodity created successfully with SKU: ' . $material->sku);
        } catch (\Exception $e) {
            \Log::error('Error creating material', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create commodity. Please try again.');
        }
    }

    /**
     * Display the specified material.
     */
    public function show(Material $material)
    {
        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

public function update(Request $request, Material $material)
{
    $validatedData = $this->validateMaterial($request, $material->id);

    // Conditionally regenerate SKU and barcode
    if ($request->has('regenerate_sku') && $request->regenerate_sku) {
        $validatedData['sku'] = $this->generateSKU($validatedData);
    }

    if ($request->has('regenerate_barcode') && $request->regenerate_barcode) {
        $validatedData['barcode'] = $this->generateBarcode();
    }

    // ✅ Fix: process and clean dimensions
    $validatedData = $this->processDimensions($validatedData, $request);

    $material->update($validatedData);

    return redirect()->route('materials.index')
        ->with('success', 'Commodity updated successfully.');
}


    /**
     * Remove the specified material from storage.
     */
    public function destroy(Material $material)
    {
        try {
            DB::beginTransaction();
            
            // Delete related inventory batches
            $material->inventoryBatches()->delete();
            
            // Delete related purchase order items (if using pivot)
            if (method_exists($material, 'purchaseOrderItems')) {
                $material->purchaseOrderItems()->delete();
            }
            
            // Delete the material itself
            $material->delete();
            
            DB::commit();
            
            return redirect()->route('materials.index')
                ->with('success', 'Commodity and related data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('materials.index')
                ->with('error', 'Failed to delete commodity. It may be in use.');
        }
    }

   /**
 * Process dimensions data from request
 */
private function processDimensions(array $validatedData, Request $request): array
{
    // Get dimensions from request
    $dimensions = $request->input('dimensions', []);
    
    // Clean up dimensions - remove empty values and convert to float
    $cleanDimensions = [];
    foreach ($dimensions as $key => $value) {
        if (!empty($value) && is_numeric($value)) {
            $cleanDimensions[$key] = (float) $value;
        }
    }

    // Assign cleaned array directly (no json_encode)
    $validatedData['dimensions'] = !empty($cleanDimensions) ? $cleanDimensions : null;

    return $validatedData;
}


    /**
     * Generate unique SKU for material
     */
    private function generateSKU(array $materialData): string
    {
        // Create SKU based on category and name
        $category = $materialData['category'] ?? 'GEN';
        $name = $materialData['name'];
        
        // Clean and format category (first 3 letters, uppercase)
        $categoryCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category), 0, 3));
        if (strlen($categoryCode) < 3) {
            $categoryCode = str_pad($categoryCode, 3, 'X');
        }
        
        // Clean and format name (first 3 letters, uppercase)
        $nameCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        if (strlen($nameCode) < 3) {
            $nameCode = str_pad($nameCode, 3, 'X');
        }
        
        // Generate unique number suffix
        $baseSKU = $categoryCode . $nameCode;
        $counter = 1;
        
        do {
            $sku = $baseSKU . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $exists = Material::where('sku', $sku)
                ->where('business_id', auth()->user()->business_id)
                ->exists();
            $counter++;
        } while ($exists && $counter <= 999);
        
        return $sku;
    }

    /**
     * Generate unique barcode (EAN-13 compatible)
     */
    private function generateBarcode(): string
    {
        do {
            // Generate 12 digit number (EAN-13 without check digit)
            $barcode = '2' . str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
            
            // Calculate EAN-13 check digit
            $checkDigit = $this->calculateEAN13CheckDigit($barcode);
            $fullBarcode = $barcode . $checkDigit;
            
            $exists = Material::where('barcode', $fullBarcode)
                ->where('business_id', auth()->user()->business_id)
                ->exists();
        } while ($exists);
        
        return $fullBarcode;
    }

    /**
     * Calculate EAN-13 check digit
     */
    private function calculateEAN13CheckDigit(string $barcode): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $barcode[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        
        $checkDigit = (10 - ($sum % 10)) % 10;
        return $checkDigit;
    }

    /**
     * Generate new barcode for existing material
     */
    public function regenerateBarcode(Material $material)
    {
        $material->barcode = $this->generateBarcode();
        $material->save();
        
        return response()->json([
            'success' => true,
            'barcode' => $material->barcode,
            'message' => 'New barcode generated successfully'
        ]);
    }

    /**
     * Validate material request data.
     */
    private function validateMaterial(Request $request, int $materialId = null): array
    {
        $businessId = auth()->user()->business_id;
        
        return $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:100',
                function ($attribute, $value, $fail) use ($businessId, $materialId) {
                    $query = Material::where('name', $value)->where('business_id', $businessId);
                    if ($materialId) $query->where('id', '!=', $materialId);
                    if ($query->exists()) {
                        $fail('This commodity name already exists.');
                    }
                }
            ],
            'item_type' => ['required', 'in:good,service'],
            'code' => [
                'nullable', 
                'string', 
                'max:50', 
                'alpha_num',
                function ($attribute, $value, $fail) use ($businessId, $materialId) {
                    if ($value) {
                        $query = Material::where('code', $value)->where('business_id', $businessId);
                        if ($materialId) $query->where('id', '!=', $materialId);
                        if ($query->exists()) {
                            $fail('This commodity code already exists.');
                        }
                    }
                }
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'unit' => ['required', 'string', 'max:20'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'gst_rate' => ['nullable', 'numeric', 'between:0,100'],
            'category' => ['nullable', 'string', 'max:100'],
            'material_type' => ['nullable', 'string', 'max:50'],
            'material_form' => ['nullable', 'string', 'max:50'],
            'grade' => ['nullable', 'string', 'max:100'],
            'unit_of_order' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
          
            // Validate dimensions as nested array
            'dimensions' => ['nullable', 'array'],
            'dimensions.length' => ['nullable', 'numeric', 'min:0'],
            'dimensions.width' => ['nullable', 'numeric', 'min:0'],
            'dimensions.height' => ['nullable', 'numeric', 'min:0'],
            'dimensions.diameter' => ['nullable', 'numeric', 'min:0'],
        ]);
    }
  
    public function getAvailableMaterials()
    {
        try {
            $materials = Material::available()
                ->where('business_id', auth()->user()->business_id)
                ->select('id', 'name', 'sku', 'barcode', 'unit', 'gst_rate', 'is_active', 'dimensions')
                ->orderBy('name')
                ->get();

            return response()->json($materials);
        } catch (\Exception $e) {
            Log::error('Error fetching available materials', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'error' => 'Failed to fetch available materials',
                'message' => 'Please try again or contact support'
            ], 500);
        }
    }

    /**
     * Get all materials for current business (API endpoint)
     */
    public function getAllMaterials()
    {
        try {
            $materials = Material::where('business_id', auth()->user()->business_id)
                ->select('id', 'name', 'code', 'unit_price', 'unit', 'gst_rate', 'sku', 'barcode')
                ->orderBy('name')
                ->get();

            return response()->json($materials);
        } catch (\Exception $e) {
            Log::error('Error fetching all materials', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'error' => 'Failed to fetch materials'
            ], 500);
        }
    }
}