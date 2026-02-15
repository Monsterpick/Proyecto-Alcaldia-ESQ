<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'director_id',
        'director_temporal_id',
        'servicios_generales',
    ];

    /**
     * El departamento tiene un director principal asignado
     */
    public function director(): BelongsTo
    {
        return $this->belongsTo(Director::class, 'director_id');
    }

    /**
     * Director temporal (cuando el principal está inactivo)
     */
    public function directorTemporal(): BelongsTo
    {
        return $this->belongsTo(Director::class, 'director_temporal_id');
    }

    /**
     * Tipos de solicitud (servicios) que ofrece este departamento.
     * Sincronizados desde servicios_generales.
     */
    public function tiposSolicitud(): HasMany
    {
        return $this->hasMany(TipoSolicitud::class);
    }

    /**
     * Sincroniza servicios_generales (comma-separated) con la tabla tipo_solicitud.
     * El formulario público de Alcaldía Digital usa estos tipos.
     */
    public function syncServiciosATipos(): void
    {
        $lista = array_filter(array_map('trim', explode(',', $this->servicios_generales ?? '')));
        $nombresNuevos = array_map(fn ($s) => \Illuminate\Support\Str::ucfirst($s), array_unique($lista));

        foreach ($nombresNuevos as $nombre) {
            if ($nombre === '') continue;
            TipoSolicitud::updateOrCreate(
                ['nombre' => $nombre],
                ['departamento_id' => $this->id, 'activo' => true]
            );
        }

        // Desactivar tipos que ya no están en servicios_generales
        $this->tiposSolicitud()
            ->whereNotIn('nombre', $nombresNuevos)
            ->update(['activo' => false]);

        \Illuminate\Support\Facades\Cache::forget('welcome_form_data');
    }

    protected static function booted(): void
    {
        static::deleting(function (Departamento $departamento) {
            $departamento->tiposSolicitud()->update(['activo' => false]);
        });
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('welcome_form_data'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('welcome_form_data'));
    }

    /**
     * Verifica si el departamento tiene un director activo (principal o temporal)
     */
    public function tieneDirectorActivo(): bool
    {
        if ($this->director && $this->director->activo) {
            return true;
        }
        if ($this->directorTemporal && $this->directorTemporal->activo) {
            return true;
        }
        return false;
    }
}
