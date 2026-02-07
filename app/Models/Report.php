<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Report extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'beneficiary_id',
        'created_by',
        'report_code',
        'quantity',
        'delivery_detail',
        'beneficiary_first_name',
        'beneficiary_last_name',
        'beneficiary_cedula',
        'beneficiary_document_type',
        'beneficiary_birth_date',
        'beneficiary_phone',
        'beneficiary_email',
        'country',
        'state',
        'municipality',
        'parish',
        'sector',
        'address',
        'reference_point',
        'latitude',
        'longitude',
        'communal_circuit',
        'delivery_date',
        'notes',
        'status',
        'pdf_path',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'beneficiary_birth_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected $appends = [
        'beneficiary_full_name',
        'beneficiary_full_cedula',
    ];

    // Accessors
    public function getBeneficiaryFullNameAttribute(): string
    {
        return trim("{$this->beneficiary_first_name} {$this->beneficiary_last_name}");
    }

    public function getBeneficiaryFullCedulaAttribute(): string
    {
        return "{$this->beneficiary_document_type}-{$this->beneficiary_cedula}";
    }

    // Relaciones
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * Obtener el circuito comunal completo basado en el código
     */
    public function circuitoComunal()
    {
        return $this->hasOneThrough(
            CircuitoComunal::class,
            Parroquia::class,
            'parroquia', // Foreign key en parroquias
            'parroquia_id', // Foreign key en circuito_comunals
            'parish', // Local key en reports
            'id' // Local key en parroquias
        )->where('circuito_comunals.codigo', $this->communal_circuit);
    }

    /**
     * Items del reporte (múltiples entregas)
     */
    public function items(): HasMany
    {
        return $this->hasMany(ReportItem::class);
    }

    /**
     * Categorías del reporte (many-to-many)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_report')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'in_process');
    }
    
    public function scopeInProcess($query)
    {
        return $query->where('status', 'in_process');
    }
    
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
    
    public function scopeNotDelivered($query)
    {
        return $query->where('status', 'not_delivered');
    }
    
    public function scopeEditable($query)
    {
        return $query->whereIn('status', ['in_process', 'not_delivered']);
    }

    public function scopeByCommunalCircuit($query, $circuit)
    {
        return $query->where('communal_circuit', $circuit);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('report_code', 'like', "%{$search}%")
              ->orWhere('beneficiary_first_name', 'like', "%{$search}%")
              ->orWhere('beneficiary_last_name', 'like', "%{$search}%")
              ->orWhere('beneficiary_cedula', 'like', "%{$search}%");
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopeByCategoryName($query, $categoryName)
    {
        return $query->whereHas('categories', function ($q) use ($categoryName) {
            $q->where('categories.name', $categoryName);
        });
    }

    // Método estático para generar código único
    public static function generateReportCode(): string
    {
        $date = now()->format('Ymd');
        $lastReport = self::whereDate('created_at', now())->latest()->first();
        $sequential = $lastReport ? (int) substr($lastReport->report_code, -4) + 1 : 1;
        
        return 'RPT-' . $date . '-' . str_pad($sequential, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Boot del modelo para eventos
     */
    protected static function boot()
    {
        parent::boot();
        
        // NO generar PDF automáticamente en created
        // Se generará manualmente después de guardar los items
    }
}
