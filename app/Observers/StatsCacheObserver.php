<?php

namespace App\Observers;

use App\Services\StatsService;

/**
 * Observer para limpiar cache de estadísticas automáticamente
 * 
 * Se activa cuando se crean/actualizan/eliminan:
 * - Beneficiarios
 * - Reportes
 * - Productos
 */
class StatsCacheObserver
{
    /**
     * Handle the model "created" event.
     */
    public function created($model): void
    {
        StatsService::clearCache();
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated($model): void
    {
        StatsService::clearCache();
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted($model): void
    {
        StatsService::clearCache();
    }

    /**
     * Handle the model "restored" event.
     */
    public function restored($model): void
    {
        StatsService::clearCache();
    }
}
