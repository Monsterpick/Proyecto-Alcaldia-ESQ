<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Beneficiary;
use App\Models\Parroquia;
use App\Models\CircuitoComunal;
use App\Models\User;

class BeneficiaryTestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $parroquia = Parroquia::first();
        
        if (!$parroquia) {
            $this->command->error('No hay parroquias. Ejecuta los seeders de geografía primero.');
            return;
        }
        
        $circuito = CircuitoComunal::where('parroquia_id', $parroquia->id)->first();
        
        if (!$circuito) {
            $this->command->error('No hay circuitos comunales.');
            return;
        }

        // Crear 3 beneficiarios de prueba
        $beneficiaries = [
            [
                'first_name' => 'Juan',
                'second_name' => 'Carlos',
                'last_name' => 'Pérez',
                'second_last_name' => 'González',
                'document_type' => 'V',
                'cedula' => '12345678',
                'email' => 'juan.perez@example.com',
                'phone' => '0424-1234567',
            ],
            [
                'first_name' => 'María',
                'second_name' => 'Elena',
                'last_name' => 'Rodríguez',
                'second_last_name' => 'Martínez',
                'document_type' => 'V',
                'cedula' => '23456789',
                'email' => 'maria.rodriguez@example.com',
                'phone' => '0414-2345678',
            ],
            [
                'first_name' => 'Pedro',
                'second_name' => 'José',
                'last_name' => 'Sánchez',
                'second_last_name' => 'López',
                'document_type' => 'V',
                'cedula' => '34567890',
                'email' => 'pedro.sanchez@example.com',
                'phone' => '0426-3456789',
            ],
        ];

        foreach ($beneficiaries as $beneficiaryData) {
            Beneficiary::create(array_merge($beneficiaryData, [
                'parroquia_id' => $parroquia->id,
                'circuito_comunal_id' => $circuito->id,
                'sector' => 'Centro',
                'reference_point' => 'Cerca de la plaza',
                'address' => 'Calle Principal #' . rand(1, 100),
                'status' => 'active',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]));
        }

        $this->command->info('✅ 3 beneficiarios de prueba creados exitosamente.');
    }
}
