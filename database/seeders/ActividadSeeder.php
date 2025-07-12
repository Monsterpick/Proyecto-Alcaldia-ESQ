<?php

namespace Database\Seeders;

use App\Models\Actividad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Actividad::create([
            'name' => 'OPTICA',
            'description' => 'Actividad de optica',
        ]);

        Actividad::create([
            'name' => 'PROVEEDOR',
            'description' => 'Actividad de proveedor',
        ]);

        Actividad::create([
            'name' => 'LABORATORIO',
            'description' => 'Actividad de laboratorio',
        ]);
    }
}
