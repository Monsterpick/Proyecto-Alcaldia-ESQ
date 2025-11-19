<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramAuthSession extends Model
{
    protected $fillable = [
        'chat_id',
        'step',
        'username',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Scope para sesiones activas (no expiradas)
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Limpiar sesiones expiradas
     */
    public static function cleanExpired()
    {
        return self::where('expires_at', '<=', now())->delete();
    }
}
