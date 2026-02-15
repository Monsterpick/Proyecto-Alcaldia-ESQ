<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSolicitud extends Model
{
    protected $table = 'tipo_solicitud';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: Un tipo tiene muchas solicitudes
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}
