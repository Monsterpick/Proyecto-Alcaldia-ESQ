<?php

namespace App\Livewire\Pages\Admin\Map;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $parroquiasMap = [];

    public function mount()
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

        // Coordenadas REALES de las parroquias del Municipio Escuque, Estado Trujillo, Venezuela
        // Fuente: Google Maps y OpenStreetMap
        $parroquias = [
            [
                'nombre' => 'Escuque',
                'lat' => 9.296520597950986, // Capital del municipio Escuque
                'lng' => -70.67268456421307,
                'color' => '#3b82f6', // Azul
            ],
            [
                'nombre' => 'La Unión',
                'lat' => 9.325181573111243, // Parroquia La Unión
                'lng' => -70.67617343346436,
                'color' => '#10b981', // Verde
            ],
            [
                'nombre' => 'Sabana Libre',
                'lat' => 9.33754472398007, // Parroquia Sabana Libre
                'lng' => -70.64839627137194,
                'color' => '#f59e0b', // Naranja
            ],
            [
                'nombre' => 'Santa Rita',
                'lat' => 9.310867072135284, // Parroquia Santa Rita
                'lng' => -70.64646605127774,
                'color' => '#ef4444', // Rojo
            ],
        ];

        // Combinar coordenadas con estadísticas
        foreach ($parroquias as $parroquia) {
            $stats = $parroquiasData->firstWhere('parroquia', $parroquia['nombre']);
            
            $this->parroquiasMap[] = [
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
    }

    public function render()
    {
        return view('livewire.pages.admin.map.index');
    }
}
