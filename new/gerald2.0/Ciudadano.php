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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un ciudadano tiene muchas solicitudes
     */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }

    /**
     * Relación: Un ciudadano tiene muchas solicitudes de derecho de palabra
     */
    public function derechosPalabra(): HasMany
    {
        return $this->hasMany(DerechoDePalabra::class);
    }

    /**
     * Obtener nombre completo del ciudadano
     */
    public function getNombreCompleto(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Verifica si el ciudadano desea recibir mensajes por WhatsApp
     */
    public function whatsappSend(): bool
    {
        return (bool) $this->whatsapp_send;
    }

    /**
     * Obtener el número de WhatsApp normalizado
     */
    public function getWhatsappNormalizado(): string
    {
        $whatsapp = $this->whatsapp;
        // Si ya comienza con +, devolverlo tal cual
        if (str_starts_with($whatsapp, '+')) {
            return $whatsapp;
        }
        // Si comienza con 0, reemplazar por +58
        if (str_starts_with($whatsapp, '0')) {
            return '+58' . substr($whatsapp, 1);
        }
        // Si no tiene prefijo, agregar +58
        return '+58' . $whatsapp;
    }
}
