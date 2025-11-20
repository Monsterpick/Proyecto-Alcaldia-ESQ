<?php

namespace App\Services;

use App\Models\Beneficiary;
use App\Models\Report;
use App\Models\Product;
use App\Models\ReportItem;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Servicio para cachear estadísticas del sistema
 * 
 * Cachea consultas pesadas para mejorar el rendimiento del dashboard
 * Cache duration: 5 minutos (300 segundos)
 */
class StatsService
{
    /**
     * Duración del cache en segundos (5 minutos)
     */
    const CACHE_DURATION = 300;

    /**
     * Obtener estadísticas generales del sistema (cacheadas)
     */
    public static function getGeneralStats(): array
    {
        return Cache::remember('stats:general', self::CACHE_DURATION, function () {
            return [
                'beneficiaries' => Beneficiary::count(),
                'beneficiaries_active' => Beneficiary::where('status', 'active')->count(),
                'reports' => Report::count(),
                'reports_delivered' => Report::where('status', 'delivered')->count(),
                'reports_in_process' => Report::where('status', 'in_process')->count(),
                'products' => Product::count(),
                'users' => User::count(),
            ];
        });
    }

    /**
     * Obtener estadísticas por parroquia (cacheadas)
     */
    public static function getStatsByParish(): array
    {
        return Cache::remember('stats:by_parish', self::CACHE_DURATION, function () {
            return Beneficiary::select('parish', DB::raw('COUNT(*) as count'))
                ->whereNotNull('parish')
                ->groupBy('parish')
                ->orderBy('count', 'desc')
                ->get()
                ->pluck('count', 'parish')
                ->toArray();
        });
    }

    /**
     * Obtener estadísticas por municipio (cacheadas)
     */
    public static function getStatsByMunicipality(): array
    {
        return Cache::remember('stats:by_municipality', self::CACHE_DURATION, function () {
            return Beneficiary::select('municipality', DB::raw('COUNT(*) as count'))
                ->whereNotNull('municipality')
                ->groupBy('municipality')
                ->orderBy('count', 'desc')
                ->get()
                ->pluck('count', 'municipality')
                ->toArray();
        });
    }

    /**
     * Obtener reportes recientes (cacheados)
     */
    public static function getRecentReports(int $limit = 10): array
    {
        return Cache::remember("stats:recent_reports:{$limit}", self::CACHE_DURATION, function () use ($limit) {
            return Report::with(['user'])
                ->latest('delivery_date')
                ->take($limit)
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'code' => $report->report_code,
                        'beneficiary_name' => $report->beneficiary_name,
                        'delivery_date' => $report->delivery_date->format('d/m/Y'),
                        'status' => $report->status,
                        'user' => $report->user->name ?? 'N/A',
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Obtener productos más entregados (cacheados)
     */
    public static function getTopProducts(int $limit = 10): array
    {
        return Cache::remember("stats:top_products:{$limit}", self::CACHE_DURATION, function () use ($limit) {
            return ReportItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->with('product')
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->take($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'product_name' => $item->product->name ?? 'N/A',
                        'total_quantity' => $item->total_quantity,
                        'category' => $item->product->category->name ?? 'N/A',
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Obtener estadísticas de reportes por mes (cacheadas)
     */
    public static function getReportsByMonth(int $months = 12): array
    {
        return Cache::remember("stats:reports_by_month:{$months}", self::CACHE_DURATION, function () use ($months) {
            $startDate = now()->subMonths($months);
            
            return Report::select(
                DB::raw('DATE_FORMAT(delivery_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
                ->where('delivery_date', '>=', $startDate)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
        });
    }

    /**
     * Obtener estadísticas para gráficos del dashboard
     */
    public static function getDashboardChartData(): array
    {
        return Cache::remember('stats:dashboard_charts', self::CACHE_DURATION, function () {
            return [
                'beneficiaries_by_status' => [
                    'active' => Beneficiary::where('status', 'active')->count(),
                    'inactive' => Beneficiary::where('status', 'inactive')->count(),
                ],
                'reports_by_status' => [
                    'delivered' => Report::where('status', 'delivered')->count(),
                    'in_process' => Report::where('status', 'in_process')->count(),
                    'not_delivered' => Report::where('status', 'not_delivered')->count(),
                ],
                'reports_last_7_days' => Report::where('delivery_date', '>=', now()->subDays(7))->count(),
                'reports_last_30_days' => Report::where('delivery_date', '>=', now()->subDays(30))->count(),
            ];
        });
    }

    /**
     * Limpiar TODO el cache de estadísticas
     * 
     * Llamar este método cuando se creen/actualicen/eliminen:
     * - Beneficiarios
     * - Reportes
     * - Productos
     */
    public static function clearCache(): void
    {
        $keys = [
            'stats:general',
            'stats:by_parish',
            'stats:by_municipality',
            'stats:dashboard_charts',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Limpiar cache de reportes recientes (diferentes límites)
        foreach ([5, 10, 20, 50] as $limit) {
            Cache::forget("stats:recent_reports:{$limit}");
            Cache::forget("stats:top_products:{$limit}");
        }

        // Limpiar cache de meses
        foreach ([6, 12, 24] as $months) {
            Cache::forget("stats:reports_by_month:{$months}");
        }
    }

    /**
     * Obtener todas las estadísticas para Telegram Bot (cacheadas)
     */
    public static function getTelegramStats(): array
    {
        return Cache::remember('stats:telegram', self::CACHE_DURATION, function () {
            return [
                'beneficiaries' => Beneficiary::count(),
                'reports' => Report::count(),
                'products' => Product::count(),
                'reports_today' => Report::whereDate('delivery_date', today())->count(),
                'reports_this_week' => Report::whereBetween('delivery_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'reports_this_month' => Report::whereMonth('delivery_date', now()->month)
                    ->whereYear('delivery_date', now()->year)
                    ->count(),
            ];
        });
    }
}
