<?php

namespace App\Models;

use App\Traits\HasCurrencyFormat;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasCurrencyFormat;

    protected $fillable = [
        'amount_cents',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'amount' => 'decimal:2',
    ];

    /**
     * Mutador para establecer el monto en centavos
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount_cents'] = $this->convertToCents($value);
    }
} 