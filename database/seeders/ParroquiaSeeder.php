<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParroquiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('parroquias')->delete();
        
        \DB::table('parroquias')->insert(array(
            0 =>
            array(
                'id' => 1,
                'municipio_id' => 1, // Escuque
                'parroquia' => 'Escuque',
            ),
            1 =>
            array(
                'id' => 2,
                'municipio_id' => 1, // Escuque
                'parroquia' => 'Sabana Libre',
            ),
            2 =>
            array(
                'id' => 3,
                'municipio_id' => 1, // Escuque
                'parroquia' => 'La Unión',
            ),
            3 =>
            array(
                'id' => 4,
                'municipio_id' => 1, // Escuque
                'parroquia' => 'Santa Rita',
            ),
        ));
    }
}
