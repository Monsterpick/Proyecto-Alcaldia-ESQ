<?php

namespace App\Models;

use App\Traits\HasCurrencyFormat;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasCurrencyFormat;

    protected $fillable = [
        'name',
        'cost_cents',
        'price_cents',
    ];

    protected $casts = [
        'cost_cents' => 'integer',
        'price_cents' => 'integer',
        'cost' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    /**
     * Mutador para el costo
     */
    public function setCostAttribute($value)
    {
        $this->attributes['cost_cents'] = $this->convertToCents($value);
    }

    /**
     * Mutador para el precio
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price_cents'] = $this->convertToCents($value);
    }

    /**
     * Accessor para el costo formateado
     */
    public function getFormattedCostAttribute()
    {
        return $this->formatMoney($this->cost_cents);
    }

    /**
     * Accessor para el precio formateado
     */
    public function getFormattedPriceAttribute()
    {
        return $this->formatMoney($this->price_cents);
    }

    /**
     * Calcula el margen de ganancia
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_cents === 0) return 0;
        return (($this->price_cents - $this->cost_cents) / $this->cost_cents) * 100;
    }
} 