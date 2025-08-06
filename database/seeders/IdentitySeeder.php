<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Identity;

class IdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $identities = [
            'Sin Documento',
            'CEDULA',
            'RIF',
            'RUC',
            'DNI',
            'Pasaporte',
            'Carnet de extranjeria',
        ];

        foreach ($identities as $identity) {
            Identity::create([
                'name' => $identity,
            ]);
        }
    }
}
