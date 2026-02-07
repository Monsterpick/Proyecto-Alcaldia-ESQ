<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('estados')->delete();

        \DB::table('estados')->insert(array(
            0 =>
            array(
                'id' => 1,
                'estado' => 'Trujillo',
                'iso_3166-2' => 'VE-T',
            ),
        ));
    }
}
