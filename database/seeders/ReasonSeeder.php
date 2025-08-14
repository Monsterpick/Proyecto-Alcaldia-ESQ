<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reason;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            //Razones para ingresos
            [
                'name' => 'Ajuste por Inventario',
                'type' => 1,
            ],
            [
                'name' => 'Devolución de cliente',
                'type' => 1,
            ],
            [
                'name' => 'Producción terminada',
                'type' => 1,
            ],
            [
                'name' => 'Error en salida anterior',
                'type' => 1,
            ],
            //Razones para salidas
            [
                'name' => 'Ajuste por Inventario 2',
                'type' => 2,
            ],
            [
                'name' => 'Salida por deterioro',
                'type' => 2,
            ],
            [
                'name' => 'Consumo interno',
                'type' => 2,
            ],
            //Razones para transferencias
            [
                'name' => 'Caducidad',
                'type' => 2,
            ],
            
        ];

        foreach($reasons as $reason){
            Reason::create($reason);
        }
        
    }
}
