<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToBusiness;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
/**
 * Class Material
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property string $unit
 * @property float $unit_price
 * @property float $gst_rate
 * @property string $category
 * @property bool $is_available
 * @property float|null $length
 * @property float|null $width
 * @property float|null $height
 * @property float|null $weight
 * @property float|null $volume
 */
class Material extends Model
{
    use BelongsToBusiness;
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($material) {
            if (!$material->code) {
                $material->code = static::generateCode($material);
            }
            if (!$material->sku) {
                $material->sku = static::generateSKU($material);
            }
            if (!$material->barcode) {
                $material->barcode = static::generateBarcode();
            }
            // Set unit to unit_of_stock for backward compatibility
            if (!$material->unit && $material->unit_of_stock) {
                $material->unit = $material->unit_of_stock;
            }
        });
    }
    
    // Protect the 'id' from mass assignment
    protected $guarded = ['id'];

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'item_type',
        'hsn_code',
        'code',
        'sku',
        'barcode',
        'description',
        'unit',
        'unit_price',
        'gst_rate',
        'category',
        'material_type',
        'material_form',
        'grade',
        'unit_of_stock',
        'unit_of_order',
        'estimated_weight_per_piece',
        'is_active',
        'dimensions',
        'business_id',
    ];

    // Cast fields to appropriate data types
    protected $casts = [
        'unit_price'   => 'decimal:2',
        'gst_rate'     => 'decimal:2',
        'estimated_weight_per_piece' => 'decimal:4',
        'is_active' => 'boolean',
        'dimensions' => 'array',
        'item_type' => 'string',
    ];

// In Material.php model
public function getDimensionsAttribute($value)
{
    return json_decode($value, true);
}

  
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'material_vendor', 'material_id', 'vendor_id')
            ->withPivot('price_per_unit', 'min_order_qty', 'notes', 'business_id')
            ->withTimestamps();
    }
  
  
   // Scope for available materials
    public function scopeAvailable($query)
    {
        return $query->where('is_active', 1);
    }
    
    // Check if material is currently available
    public function isAvailable()
    {
        return $this->is_active == 1;
    }
  
  

   public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
  public function purchaseOrders()
{
    return $this->hasMany(PurchaseOrder::class); // Adjust if using pivot
}
    /**
     * Get related inventory batches.
     */
    public function inventoryBatches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class);
    }

    /**
     * Get related barcodes.
     */
    public function barcodes(): HasMany
    {
        return $this->hasMany(Barcode::class);
    }
  
   /**
     * Search materials by various fields
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('code', 'LIKE', "%{$search}%")
              ->orWhere('sku', 'LIKE', "%{$search}%")
              ->orWhere('barcode', $search)
              ->orWhere('category', 'LIKE', "%{$search}%");
        });
    }
  
   /**
     * Get material by barcode
     */
    public static function findByBarcode($barcode)
    {
        $businessId = auth()->check() ? auth()->user()->business_id : null;
        $query = static::where('barcode', $barcode);
        
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        return $query->first();
    }

    /**
     * Get material by SKU
     */
    public static function findBySku($sku)
    {
        $businessId = auth()->check() ? auth()->user()->business_id : null;
        $query = static::where('sku', $sku);
        
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        return $query->first();
    }

    /**
     * Calculate the current stock based on active inventory batches.
     *
     * @return float
     */
    public function getCurrentStock(): float
    {
        return (float) $this->inventoryBatches()
            ->where('status', 'active')
            ->sum('current_quantity');
    }
    
    /**
     * Generate unique SKU for material
     */
    public static function generateSKU($material): string
    {
        // Create SKU based on category and name
        $category = $material->category ?? 'GEN';
        $name = $material->name;

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

        $businessId = $material->business_id ?? (auth()->check() ? auth()->user()->business_id : 1);
        $baseSKU = $categoryCode . $nameCode;

        // Generate unique number suffix within the business
        $counter = 1;
        $timestamp = now()->format('His'); // HHMMSS

        do {
            $sku = $baseSKU . '-' . $timestamp . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $exists = static::where('sku', $sku)->where('business_id', $businessId)->exists(); // Business-scoped uniqueness
            $counter++;
        } while ($exists && $counter <= 99);

        return $sku;
    }

    /**
     * Generate unique barcode (EAN-13 compatible)
     */
    public static function generateBarcode(): string
    {
        $businessId = auth()->check() ? auth()->user()->business_id : 1;
        
        do {
            // Generate 12 digit number (EAN-13 without check digit)
            $barcode = '2' . str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
            
            // Calculate EAN-13 check digit
            $checkDigit = static::calculateEAN13CheckDigit($barcode);
            $fullBarcode = $barcode . $checkDigit;
            
            $exists = static::where('barcode', $fullBarcode)->where('business_id', $businessId)->exists();
        } while ($exists);
        
        return $fullBarcode;
    }

    /**
     * Calculate EAN-13 check digit
     */
    private static function calculateEAN13CheckDigit(string $barcode): int
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
     * Generate unique code for material
     */
    public static function generateCode($material): string
    {
        $name = $material->name;
        $form = $material->material_form ?? '';
        
        // Create code from name and form
        $nameCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 6));
        $formCode = $form ? strtoupper(substr($form, 0, 2)) : '';
        
        $baseCode = $nameCode . $formCode;
        $counter = 1;
        
        $businessId = $material->business_id ?? (auth()->check() ? auth()->user()->business_id : 1);
        
        do {
            $code = $baseCode . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $exists = static::where('code', $code)->where('business_id', $businessId)->exists();
            $counter++;
        } while ($exists && $counter <= 99);
        
        return $code;
    }

    public function getDisplayNameAttribute()
    {
        $display = $this->name;
        if ($this->material_form) {
            $display .= " ({$this->material_form})";
        }
        if ($this->grade) {
            $display .= " - {$this->grade}";
        }
        return $display;
    }

    public function isDualUnit()
    {
        return $this->unit_of_order && $this->unit_of_order !== $this->unit_of_stock;
    }

    public function getEstimatedWeightForQuantity($quantity)
    {
        if ($this->isDualUnit() && $this->estimated_weight_per_piece) {
            return $quantity * $this->estimated_weight_per_piece;
        }
        return $quantity;
    }

    public function isService()
    {
        return $this->item_type === 'service';
    }

    public function isGood()
    {
        return $this->item_type === 'good';
    }
}
