<?php

namespace Database\Seeders;

use App\Models\AppointmentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentStatus::create([
            'name' => 'Agendada',
            'description' => 'Cita agendada',
            'color' => 'blue'
        ]);

        AppointmentStatus::create([
            'name' => 'Cancelada',
            'description' => 'Cita cancelada',
            'color' => 'red'
        ]);

        AppointmentStatus::create([
            'name' => 'Confirmada',
            'description' => 'Cita confirmada',
            'color' => 'green'
        ]);

        AppointmentStatus::create([
            'name' => 'Completada',
            'description' => 'Cita completada',
            'color' => 'lime'
        ]);

        AppointmentStatus::create([
            'name' => 'No asistió',
            'description' => 'No asistió a la cita',
            'color' => 'gray'
        ]);
    }
}
