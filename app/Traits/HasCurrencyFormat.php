<?php

namespace App\Traits;

use App\Models\Setting;

trait HasCurrencyFormat
{
    /**
     * Formatea un monto para mostrar
     */
    public function formatMoney($amount)
    {
        $symbol = Setting::get('currency_symbol', '$');
        $position = Setting::get('currency_position', 'before');
        $decimalSeparator = Setting::get('decimal_separator', '.');
        $thousandSeparator = Setting::get('thousand_separator', ',');

        // Si el monto viene en centavos, convertirlo a decimal
        if ($this->shouldConvertFromCents()) {
            $amount = $amount / 100;
        }

        $formattedAmount = number_format(
            $amount,
            2,
            $decimalSeparator,
            $thousandSeparator
        );

        return $position === 'before'
            ? "{$symbol}{$formattedAmount}"
            : "{$formattedAmount}{$symbol}";
    }

    /**
     * Convierte un monto de la forma de presentación a centavos para almacenar
     */
    public function convertToCents($amount)
    {
        // Primero normalizamos el formato del número
        $decimalSeparator = Setting::get('decimal_separator', '.');
        $thousandSeparator = Setting::get('thousand_separator', ',');

        // Reemplazar separadores
        if ($decimalSeparator !== '.') {
            $amount = str_replace([$thousandSeparator, $decimalSeparator], ['', '.'], $amount);
        } else {
            $amount = str_replace($thousandSeparator, '', $amount);
        }

        // Convertir a float y luego a centavos
        return (int) round((float) $amount * 100);
    }

    /**
     * Determina si el monto está almacenado en centavos
     */
    protected function shouldConvertFromCents(): bool
    {
        return $this->amountInCents ?? true;
    }

    /**
     * Obtiene el monto formateado del modelo
     */
    public function getFormattedAmountAttribute()
    {
        return $this->formatMoney($this->amount_cents ?? $this->amount);
    }

    /**
     * Obtiene el monto en la moneda base (sin símbolo)
     */
    public function getBaseAmountAttribute()
    {
        $amount = $this->amount_cents ?? $this->amount;
        return $this->shouldConvertFromCents() ? $amount / 100 : $amount;
    }
} 