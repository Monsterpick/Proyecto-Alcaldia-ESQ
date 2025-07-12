<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialities = [
            'Cardiología',
            'Neurología',
            'Pediatría',
            'Ginecología',
            'Traumatología',
            'Oncología',
            'Endocrinología',
            'Gastroenterología',
            'Hematología',
            'Infectología',
            'Nutrición',
        ];

        foreach ($specialities as $speciality) {
            Speciality::create([
                'name' => $speciality,
            ]);
        }
    }
}
