<?php

namespace App\Models;

use App\Traits\HasCurrencyFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasCurrencyFormat;

    protected $fillable = [
        'number',
        'subtotal_cents',
        'tax_cents',
    ];

    protected $casts = [
        'subtotal_cents' => 'integer',
        'tax_cents' => 'integer',
        'total_cents' => 'integer',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Obtiene el subtotal formateado
     */
    public function getFormattedSubtotalAttribute()
    {
        return $this->formatMoney($this->subtotal_cents);
    }

    /**
     * Obtiene el impuesto formateado
     */
    public function getFormattedTaxAttribute()
    {
        return $this->formatMoney($this->tax_cents);
    }

    /**
     * Obtiene el total formateado
     */
    public function getFormattedTotalAttribute()
    {
        return $this->formatMoney($this->total_cents);
    }

    /**
     * Recalcula los totales basado en los items
     */
    public function recalculateTotals()
    {
        $this->subtotal_cents = $this->items()->sum('subtotal_cents');
        // Ejemplo: 16% de impuesto
        $this->tax_cents = (int) round($this->subtotal_cents * 0.16);
        $this->save();
    }
} 