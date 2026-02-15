<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Director extends Model
{
    protected $table = 'directores';

    protected static function booted(): void
    {
        static::deleting(function (Director $director): void {
            $user = $director->user;
            if ($user) {
                $user->syncRoles([]);
                $user->forceDelete();
            }
        });
    }

    protected $fillable = [
        'user_id',
        'nombre',
        'segundo_nombre',
        'apellido',
        'segundo_apellido',
        'tipo_documento',
        'cedula',
        'fecha_nacimiento',
        'telefono',
        'email',
        'activo',
        'departamento_id',
        'departamento_nombre_pendiente',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_nacimiento' => 'date',
    ];

    /**
     * El director tiene un usuario asociado para acceder al sistema
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El director pertenece a un departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Indica si este director es temporal en su departamento
     */
    public function esDirectorTemporal(): bool
    {
        return $this->departamento && $this->departamento->director_temporal_id === $this->id;
    }

    /**
     * Nombre completo del director
     */
    public function getNombreCompletoAttribute(): string
    {
        $partes = array_filter([
            $this->nombre,
            $this->segundo_nombre,
            $this->apellido,
            $this->segundo_apellido,
        ]);

        return implode(' ', $partes);
    }

    /**
     * Obtener el número de WhatsApp normalizado a formato +58
     */
    public function getWhatsappNormalizado(): ?string
    {
        $telefono = $this->telefono;
        if (empty($telefono)) {
            return null;
        }

        $telefono = trim($telefono);

        if (str_starts_with($telefono, '+')) {
            return $telefono;
        }

        if (str_starts_with($telefono, '0')) {
            return '+58' . substr($telefono, 1);
        }

        return '+58' . $telefono;
    }
}
