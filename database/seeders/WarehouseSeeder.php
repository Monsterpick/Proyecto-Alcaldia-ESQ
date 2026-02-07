<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::create([
            'name' => 'Alcaldía de Escuque',
            'location' => 'Escuque, Estado Trujillo, Venezuela',
            'description' => 'Almacén principal de la Alcaldía de Escuque para gestión de beneficios sociales y programas comunitarios',
            'responsible' => 'Coordinación de Beneficios Sociales',
            'phone' => '+58424-0000000',
            'is_active' => true,
        ]);
    }
}
