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
        'departamento_id',
        'parroquia_id',
        'circuito_comunal_id',
        'sector',
        'descripcion',
        'direccion',
        'direccion_exacta',
        'estado',
        'acepta_terminos',
        'fecha_respuesta',
        'respuesta',
    ];

    protected $casts = [
        'acepta_terminos' => 'boolean',
        'fecha_respuesta' => 'datetime',
    ];

    /**
     * Relación: Una solicitud pertenece a un ciudadano
     */
    public function ciudadano(): BelongsTo
    {
        return $this->belongsTo(Ciudadano::class);
    }

    /**
     * Relación: Una solicitud pertenece a un tipo de solicitud
     */
    public function tipoSolicitud(): BelongsTo
    {
        return $this->belongsTo(TipoSolicitud::class);
    }

    /**
     * Relación: Una solicitud pertenece a un departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Relación: Una solicitud pertenece a una parroquia
     */
    public function parroquia(): BelongsTo
    {
        return $this->belongsTo(Parroquia::class);
    }

    /**
     * Relación: Una solicitud pertenece a un circuito comunal
     */
    public function circuitoComunal(): BelongsTo
    {
        return $this->belongsTo(CircuitoComunal::class);
    }

    /**
     * Obtiene el número de WhatsApp del director del departamento (principal o temporal activo).
     */
    public function getNumeroWhatsappDirector(): ?string
    {
        $departamento = $this->departamento()->with(['director', 'directorTemporal'])->first();
        if (!$departamento) {
            return null;
        }

        $destino = null;
        if ($departamento->director && $departamento->director->activo) {
            $destino = $departamento->director;
        } elseif ($departamento->directorTemporal && $departamento->directorTemporal->activo) {
            $destino = $departamento->directorTemporal;
        }

        return $destino && method_exists($destino, 'getWhatsappNormalizado')
            ? ($destino->getWhatsappNormalizado() ?: null)
            : null;
    }
}
