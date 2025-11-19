<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Beneficiary;
use App\Models\Parroquia;
use App\Models\CircuitoComunal;
use App\Models\User;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        
        // Obtener parroquias
        $parroquias = Parroquia::all();
        
        if ($parroquias->isEmpty()) {
            $this->command->warn('No hay parroquias. Ejecuta ParroquiaSeeder primero.');
            return;
        }

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
                'second_name' => null,
                'last_name' => 'Sánchez',
                'second_last_name' => 'López',
                'document_type' => 'V',
                'cedula' => '34567890',
                'email' => null,
                'phone' => '0426-3456789',
            ],
            [
                'first_name' => 'Ana',
                'second_name' => 'Lucía',
                'last_name' => 'Fernández',
                'second_last_name' => null,
                'document_type' => 'V',
                'cedula' => '45678901',
                'email' => 'ana.fernandez@example.com',
                'phone' => null,
            ],
            [
                'first_name' => 'Luis',
                'second_name' => 'Alberto',
                'last_name' => 'García',
                'second_last_name' => 'Ramírez',
                'document_type' => 'V',
                'cedula' => '56789012',
                'email' => 'luis.garcia@example.com',
                'phone' => '0412-5678901',
            ],
        ];

        foreach ($beneficiaries as $beneficiaryData) {
            // Seleccionar parroquia aleatoria
            $parroquia = $parroquias->random();
            
            // Obtener circuito comunal de esa parroquia
            $circuito = CircuitoComunal::where('parroquia_id', $parroquia->id)->inRandomOrder()->first();
            
            if (!$circuito) {
                continue;
            }

            Beneficiary::create(array_merge($beneficiaryData, [
                'state' => 'Trujillo',
                'municipality' => 'Escuque',
                'parroquia_id' => $parroquia->id,
                'circuito_comunal_id' => $circuito->id,
                'sector' => 'Sector Centro',
                'reference_point' => 'Cerca de la plaza principal',
                'address' => 'Calle Principal, Casa #' . rand(1, 50),
                'status' => 'active',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]));
        }

        $this->command->info('✅ Beneficiarios de prueba creados exitosamente.');
    }
}
