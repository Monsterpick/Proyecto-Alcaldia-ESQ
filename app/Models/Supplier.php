<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'identity_id',
        'document_number',
        'name',
        'address',
        'phone',
        'email',
    ];

    //Relacion uno a muchos inversa
    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    //Relacion uno a muchos
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    //Relacion uno a muchos
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
