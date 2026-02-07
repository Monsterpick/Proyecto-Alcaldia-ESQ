<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentOrigin;

class PaymentOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $origins = [
            [
                'name' => 'Virtual',
                'description' => 'Pago registrado a través de la plataforma virtual',
                'is_active' => true
            ],
            [
                'name' => 'Oficina',
                'description' => 'Pago recibido en oficina',
                'is_active' => true
            ],
            [
                'name' => 'Banco',
                'description' => 'Pago registrado directamente en el banco',
                'is_active' => true
            ]
        ];

        foreach ($origins as $origin) {
            PaymentOrigin::create($origin);
        }
    }
} 