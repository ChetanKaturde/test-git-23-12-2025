<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Barcode extends Model
{
    use HasFactory;

    // Cache configuration
    private const BARCODE_CACHE_TTL = 300; // 5 minutes
    private const QR_DATA_CACHE_PREFIX = 'qr_data_';
    
    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_DAMAGED = 'damaged';
    public const STATUS_EXPIRED = 'expired';
    
    // Quality grade constants
    public const GRADE_A = 'A';
    public const GRADE_B = 'B';
    public const GRADE_C = 'C';
    public const GRADE_D = 'D';
    
    // Barcode type constants
    public const TYPE_STANDARD = 'standard';
    public const TYPE_QR = 'qr';
    public const TYPE_BOTH = 'both';

    protected $fillable = [
        'barcode_number',
        'batch_id',
        'purchase_order_id',
        'material_id',
        'material_name',
        'material_code',
        'supplier_name',
        'quantity',
        'weight',
        'unit_price',
        'expiry_date',
        'storage_location',
        'quality_grade',
        'barcode_type',
        'status',
        'notes',
        'qr_code_data',
        'created_by',
        'updated_by',
        'last_scanned_at',
        'scan_count',
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'last_scanned_at' => 'datetime',
        'quantity' => 'decimal:2',
        'weight' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'scan_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
        'barcode_type' => self::TYPE_STANDARD,
        'scan_count' => 0,
    ];

    // Indexes for better query performance
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate barcode number on creation
        static::creating(function ($model) {
            if (empty($model->barcode_number)) {
                $model->barcode_number = self::generateBarcodeNumber();
            }
            
            // Generate QR data on creation
            $model->qr_code_data = $model->generateQRData();
        });

        // Regenerate QR data when relevant fields change
        static::updating(function ($model) {
            if ($model->isDirty(['material_name', 'material_code', 'supplier_name', 'storage_location', 'quality_grade'])) {
                $model->qr_code_data = $model->generateQRData();
                
                // Clear cache when QR data changes
                Cache::forget(self::QR_DATA_CACHE_PREFIX . $model->id);
            }
        });
    }

    /**
     * Generate unique barcode number with better collision handling
     */
    public static function generateBarcodeNumber(): string
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            $timestamp = now()->format('ymdHis');
            $random = strtoupper(Str::random(4));
            $number = "BC{$timestamp}{$random}";
            
            $attempt++;
            
            if ($attempt >= $maxAttempts) {
                // Fallback to UUID-based generation
                $number = 'BC' . strtoupper(str_replace('-', '', Str::uuid()));
                break;
            }
            
        } while (self::where('barcode_number', $number)->exists());
        
        return $number;
    }

    /**
     * Generate QR code data with caching
     */
    public function generateQRData(): string
    {
        $data = [
            'barcode_number' => $this->barcode_number,
            'material_code' => $this->material_code,
            'material_name' => $this->material_name,
            'batch_number' => $this->batch?->batch_number,
            'supplier_name' => $this->supplier_name,
            'expiry_date' => $this->expiry_date?->format('Y-m-d'),
            'storage_location' => $this->storage_location,
            'quality_grade' => $this->quality_grade,
            'generated_at' => now()->toISOString(),
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get cached QR data
     */
    public function getCachedQRData(): string
    {
        return Cache::remember(
            self::QR_DATA_CACHE_PREFIX . $this->id,
            self::BARCODE_CACHE_TTL,
            fn() => $this->qr_code_data ?: $this->generateQRData()
        );
    }

    // ==================== RELATIONSHIPS ====================

    public function batch(): BelongsTo
    {
        return $this->belongsTo(InventoryBatch::class, 'batch_id');
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeDamaged(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DAMAGED);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expiry_date', '<', now())
                    ->orWhere('status', self::STATUS_EXPIRED);
    }

    public function scopeExpiringSoon(Builder $query, int $days = 7): Builder
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    public function scopeByMaterial(Builder $query, int $materialId): Builder
    {
        return $query->where('material_id', $materialId);
    }

    public function scopeBySupplier(Builder $query, string $supplierName): Builder
    {
        return $query->where('supplier_name', 'like', "%{$supplierName}%");
    }

    public function scopeRecentlyScanned(Builder $query, int $hours = 24): Builder
    {
        return $query->where('last_scanned_at', '>=', now()->subHours($hours));
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('barcode_number', 'like', "%{$search}%")
              ->orWhere('material_name', 'like', "%{$search}%")
              ->orWhere('material_code', 'like', "%{$search}%")
              ->orWhere('supplier_name', 'like', "%{$search}%");
        });
    }

    // ==================== ACCESSORS & MUTATORS ====================

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= 7;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_INACTIVE => 'secondary',
            self::STATUS_DAMAGED => 'danger',
            self::STATUS_EXPIRED => 'warning',
            default => 'primary'
        };
    }

    public function getQualityGradeColorAttribute(): string
    {
        return match($this->quality_grade) {
            self::GRADE_A => 'success',
            self::GRADE_B => 'info',
            self::GRADE_C => 'warning',
            self::GRADE_D => 'danger',
            default => 'secondary'
        };
    }

    public function getFormattedExpiryDateAttribute(): ?string
    {
        return $this->expiry_date?->format('d M, Y');
    }

    public function getFormattedLastScannedAttribute(): ?string
    {
        return $this->last_scanned_at?->format('d M, Y H:i');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if barcode is scannable
     */
    public function isScannable(): bool
    {
        return $this->status === self::STATUS_ACTIVE && !$this->is_expired;
    }

    /**
     * Mark barcode as scanned
     */
    public function markAsScanned(): self
    {
        $this->increment('scan_count');
        $this->update(['last_scanned_at' => now()]);
        
        return $this;
    }

    /**
     * Get material display name
     */
    public function getMaterialDisplayName(): string
    {
        if ($this->material_name && $this->material_code) {
            return "{$this->material_name} ({$this->material_code})";
        }
        
        return $this->material_name ?: $this->material_code ?: 'Unknown Material';
    }

    /**
     * Get supplier display name
     */
    public function getSupplierDisplayName(): string
    {
        return $this->supplier_name ?: 'No Supplier';
    }

    /**
     * Get batch display name
     */
    public function getBatchDisplayName(): string
    {
        return $this->batch?->batch_number ?: 'No Batch';
    }

    /**
     * Check if barcode needs attention (expired, expiring soon, damaged)
     */
    public function needsAttention(): bool
    {
        return $this->is_expired || 
               $this->is_expiring_soon || 
               $this->status === self::STATUS_DAMAGED;
    }

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DAMAGED => 'Damaged',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    /**
     * Get all available quality grades
     */
    public static function getQualityGrades(): array
    {
        return [
            self::GRADE_A => 'Grade A',
            self::GRADE_B => 'Grade B', 
            self::GRADE_C => 'Grade C',
            self::GRADE_D => 'Grade D',
        ];
    }

    /**
     * Get all available barcode types
     */
    public static function getBarcodeTypes(): array
    {
        return [
            self::TYPE_STANDARD => 'Standard Barcode',
            self::TYPE_QR => 'QR Code',
            self::TYPE_BOTH => 'Both Types',
        ];
    }

    /**
     * Export data for CSV/Excel
     */
    public function toExportArray(): array
    {
        return [
            'barcode_number' => $this->barcode_number,
            'material_name' => $this->material_name,
            'material_code' => $this->material_code,
            'supplier_name' => $this->supplier_name,
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'unit_price' => $this->unit_price,
            'expiry_date' => $this->formatted_expiry_date,
            'storage_location' => $this->storage_location,
            'quality_grade' => $this->quality_grade,
            'status' => $this->status,
            'scan_count' => $this->scan_count,
            'last_scanned' => $this->formatted_last_scanned,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }

    // ==================== VALIDATION RULES ====================

    public static function validationRules(): array
    {
        return [
            'barcode_number' => 'required|string|max:50|unique:barcodes,barcode_number',
            'batch_id' => 'nullable|exists:inventory_batches,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'material_id' => 'nullable|exists:materials,id',
            'material_name' => 'nullable|string|max:255',
            'material_code' => 'nullable|string|max:100',
            'supplier_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'storage_location' => 'nullable|string|max:100',
            'quality_grade' => 'required|in:A,B,C,D',
            'barcode_type' => 'required|in:standard,qr,both',
            'status' => 'required|in:active,inactive,damaged,expired',
            'notes' => 'nullable|string|max:500',
        ];
    }
}