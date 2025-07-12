<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tenant extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'id',
        'name',
        'razon_social',
        'domain',
        'rif',
        'actividad_id',
        'telefono_principal',
        'telefono_secundario',
        'email_principal',
        'email_secundario',
        'estado_id',
        'municipio_id',
        'parroquia_id',
        'direccion_fiscal',
        'responsable',
        'cargo_responsable',
        'telefono_responsable',
        'email_responsable',
        'plan_id',
        'estatus_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Este modelo ha sido {$eventName}")
            ->dontSubmitEmptyLogs();
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(TenantPayment::class);
    }
}
