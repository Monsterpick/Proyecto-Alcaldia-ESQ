<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoSolicitud extends Model
{
    protected $table = 'tipo_solicitud';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        'departamento_id',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: Un tipo tiene muchas solicitudes
     */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }

    /**
     * Relación: Un tipo de solicitud pertenece a un departamento
     */
    public function departamento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Departamento::class);
    }
}
