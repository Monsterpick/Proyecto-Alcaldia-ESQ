<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Almacén principal',
                'location' => 'Calle 123, Caracas, Urdaneta',
            ],
            [
                'name' => 'Almacén secundario',
                'location' => 'Calle 123, Trujillo, Valera',
            ],
            [
                'name' => 'Almacén de producción',
                'location' => 'Calle 123, Caracas, Chacao',
            ],
            [
                'name' => 'Almacén de resguardo',
                'location' => 'Calle 123, Caracas, Chacao',
            ],
        ];

        foreach($warehouses as $warehouse){
            Warehouse::create($warehouse);
        }
        
    }
}
