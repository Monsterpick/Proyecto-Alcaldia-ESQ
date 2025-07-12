<?php

namespace App\Models;

use App\Traits\HasCurrencyFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasCurrencyFormat;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price_cents',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price_cents' => 'integer',
        'subtotal_cents' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Obtiene el precio unitario formateado
     */
    public function getFormattedUnitPriceAttribute()
    {
        return $this->formatMoney($this->unit_price_cents);
    }

    /**
     * Obtiene el subtotal formateado
     */
    public function getFormattedSubtotalAttribute()
    {
        return $this->formatMoney($this->subtotal_cents);
    }

    /**
     * Mutador para el precio unitario
     */
    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price_cents'] = $this->convertToCents($value);
    }

    protected static function booted()
    {
        // Cuando se crea o actualiza un item, recalcular los totales de la factura
        static::saved(function ($item) {
            $item->invoice->recalculateTotals();
        });
    }
} 