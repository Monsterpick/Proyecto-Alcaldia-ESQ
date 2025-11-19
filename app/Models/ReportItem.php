<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'report_id',
        'product_id',
        'warehouse_id',
        'inventory_id',
        'quantity',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relaciones
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
