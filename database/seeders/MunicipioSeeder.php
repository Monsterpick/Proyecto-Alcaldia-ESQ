<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('municipios')->delete();
        
        \DB::table('municipios')->insert(array(
            0 =>
            array(
                'id' => 1,
                'estado_id' => 1, // Trujillo
                'municipio' => 'Escuque',
            ),
        ));
    }
}
