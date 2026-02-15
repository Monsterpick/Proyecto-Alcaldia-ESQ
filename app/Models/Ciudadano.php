<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudadano extends Model
{
    use HasFactory;

    protected $table = 'ciudadanos';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'email',
        'telefono_movil',
        'whatsapp',
        'whatsapp_send',
    ];

    protected $casts = [
        'whatsapp_send' => 'boolean',
    ];

    /**
     * Relación: Un ciudadano tiene muchas solicitudes
     */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }

    /**
     * Obtener nombre completo del ciudadano
     */
    public function getNombreCompleto(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Obtener el número de WhatsApp normalizado a formato +58
     */
    public function getWhatsappNormalizado(): string
    {
        $whatsapp = $this->whatsapp;

        if (str_starts_with($whatsapp, '+')) {
            return $whatsapp;
        }
        if (str_starts_with($whatsapp, '0')) {
            return '+58' . substr($whatsapp, 1);
        }

        return '+58' . $whatsapp;
    }
}
