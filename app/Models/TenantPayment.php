<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCurrencyFormat;

class TenantPayment extends Model
{
    use SoftDeletes;
    use HasCurrencyFormat;

    protected $fillable = [
        'tenant_id',
        'payment_type_id',
        'payment_origin_id',
        'amount',
        'amount_cents',
        'reference_number',
        'payment_date',
        'period_start',
        'period_end',
        'notes',
        'status',
        'currency',
        'image_path'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'amount' => 'decimal:2',
        'amount_cents' => 'integer'
    ];

    public function setAmountAttribute($value)
    {
        $this->attributes['amount_cents'] = $this->convertToCents($value);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function paymentOrigin()
    {
        return $this->belongsTo(PaymentOrigin::class);
    }

    // Método de ayuda para obtener el concepto del pago
    public function getConceptAttribute()
    {
        return date('F Y', strtotime($this->period_start)) . ' - ' . date('F Y', strtotime($this->period_end));
    }


} 