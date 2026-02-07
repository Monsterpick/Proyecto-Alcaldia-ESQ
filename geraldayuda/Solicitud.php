<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'ciudadano_id',
        'tipo_solicitud_id',
        'descripcion',
        'estado',
        'acepta_terminos',
        'fecha_respuesta',
        'respuesta',
    ];

    protected $casts = [
        'acepta_terminos' => 'boolean',
        'fecha_respuesta' => 'datetime',
    ];

    // Relación: Una solicitud pertenece a un ciudadano
    public function ciudadano(): BelongsTo
    {
        return $this->belongsTo(Ciudadano::class, 'ciudadano_id');
    }

    // Relación: Una solicitud pertenece a un tipo de solicitud
    public function tipoSolicitud(): BelongsTo
    {
        return $this->belongsTo(TipoSolicitud::class, 'tipo_solicitud_id');
    }
}
