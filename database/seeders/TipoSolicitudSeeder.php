<?php

namespace Database\Seeders;

use App\Models\TipoSolicitud;
use Illuminate\Database\Seeder;

class TipoSolicitudSeeder extends Seeder
{
    /**
     * Los servicios del formulario provienen de departamentos.servicios_generales.
     * Este seeder solo desactiva tipos antiguos (genéricos).
     */
    public function run(): void
    {
        $tiposAntiguos = [
            'Ayuda Social', 'Beca Educativa', 'Asistencia Médica',
            'Apoyo al Empleo', 'Mejora de Vivienda', 'Participación Ciudadana',
        ];
        TipoSolicitud::whereIn('nombre', $tiposAntiguos)->update(['activo' => false]);
    }
}
