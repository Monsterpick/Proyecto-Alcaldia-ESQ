<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index()
    {
        // Obtener estadísticas por parroquia
        $parroquiasData = DB::table('reports')
            ->select(
                'parish as parroquia',
                DB::raw('COUNT(*) as total_reportes'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as entregados'),
                DB::raw('SUM(CASE WHEN status = "not_delivered" THEN 1 ELSE 0 END) as no_entregados'),
                DB::raw('SUM(CASE WHEN status = "in_process" THEN 1 ELSE 0 END) as en_proceso')
            )
            ->whereNull('deleted_at')
            ->whereNotNull('parish')
            ->where('parish', '!=', '')
            ->groupBy('parish')
            ->get();

        // Coordenadas de las parroquias del Municipio Escuque, Trujillo
        $parroquias = [
            [
                'nombre' => 'Escuque',
                'lat' => 9.3114,
                'lng' => -70.7592,
                'color' => '#3b82f6', // Azul
            ],
            [
                'nombre' => 'La Quebrada',
                'lat' => 9.3567,
                'lng' => -70.7123,
                'color' => '#10b981', // Verde
            ],
            [
                'nombre' => 'Sabana Libre',
                'lat' => 9.2856,
                'lng' => -70.8234,
                'color' => '#f59e0b', // Amarillo
            ],
            [
                'nombre' => 'Santa Rita',
                'lat' => 9.2445,
                'lng' => -70.7890,
                'color' => '#ef4444', // Rojo
            ],
        ];

        // Combinar coordenadas con estadísticas
        $parroquiasMap = [];
        foreach ($parroquias as $parroquia) {
            $stats = $parroquiasData->firstWhere('parroquia', $parroquia['nombre']);
            
            $parroquiasMap[] = [
                'nombre' => $parroquia['nombre'],
                'lat' => $parroquia['lat'],
                'lng' => $parroquia['lng'],
                'color' => $parroquia['color'],
                'total_reportes' => $stats->total_reportes ?? 0,
                'entregados' => $stats->entregados ?? 0,
                'no_entregados' => $stats->no_entregados ?? 0,
                'en_proceso' => $stats->en_proceso ?? 0,
            ];
        }

        return view('map.mapa', compact('parroquiasMap'));
    }

    public function getParroquiaStats($parroquia)
    {
        $stats = DB::table('reports')
            ->select(
                DB::raw('COUNT(*) as total_reportes'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as entregados'),
                DB::raw('SUM(CASE WHEN status = "not_delivered" THEN 1 ELSE 0 END) as no_entregados'),
                DB::raw('SUM(CASE WHEN status = "in_process" THEN 1 ELSE 0 END) as en_proceso')
            )
            ->where('parish', $parroquia)
            ->whereNull('deleted_at')
            ->first();

        return response()->json($stats);
    }
}
