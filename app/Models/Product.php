<?php

namespace App\Models;

use App\Traits\HasCurrencyFormat;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasCurrencyFormat;
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'category_id',
        'name',
        'unit_type',
        'description',
        'observation',
        'sku',
        'barcode',
        'qrcode',
        'expedition_date',
        'expiration_date',
        'price',
        'is_active',
    ];

    //Relacion uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Relacion muchos a muchos 
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    //Relacion muchos a muchos polimorfica
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function purchaseOrders()
    {
        return $this->morphedByMany(PurchaseOrder::class, 'productable');
    }

    public function quotes()
    {
        return $this->morphedByMany(Quote::class, 'productable');
    }

    //Accesores
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->images->count() ? Storage::url($this->images->first()->path) : 'https://www.freeiconspng.com/uploads/no-image-icon-15.png',
        );
    }
} 