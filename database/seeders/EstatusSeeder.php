<?php

namespace Database\Seeders;

use App\Models\Estatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Estatus::create([
            'name' => 'Activo',
            'description' => 'Estatus activo',
        ]);

        Estatus::create([
            'name' => 'Inactivo',
            'description' => 'Estatus inactivo',
        ]);

        Estatus::create([
            'name' => 'Deudor',
            'description' => 'Estatus deudor',
        ]);

        Estatus::create([
            'name' => 'Cancelado',
            'description' => 'Estatus cancelado',
        ]);

        Estatus::create([
            'name' => 'Suspendido',
            'description' => 'Estatus suspendido',
        ]);

        Estatus::create([
            'name' => 'Pruebas',
            'description' => 'Estatus pruebas',
        ]);
    }
}
