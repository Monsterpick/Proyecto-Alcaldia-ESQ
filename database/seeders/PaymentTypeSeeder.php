<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Transferencia Bancaria',
                'description' => 'Pago realizado mediante transferencia bancaria',
                'is_active' => true
            ],
            [
                'name' => 'Efectivo Dólar',
                'description' => 'Pago realizado en efectivo',
                'is_active' => true
            ],
            [
                'name' => 'Efectivo Bolívar',
                'description' => 'Pago realizado en efectivo',
                'is_active' => true
            ],
            [
                'name' => 'Pago Móvil',
                'description' => 'Pago realizado mediante pago móvil',
                'is_active' => true
            ],
            [
                'name' => 'Punto de Venta',
                'description' => 'Pago realizado con tarjeta de débito/crédito',
                'is_active' => true
            ],
            [
                'name' => 'Zelle',
                'description' => 'Pago realizado mediante Zelle',
                'is_active' => true
            ],
            [
                'name' => 'Binance',
                'description' => 'Pago realizado mediante Binance',
                'is_active' => true
            ],
            [
                'name' => 'Paypal',
                'description' => 'Pago realizado mediante Paypal',
                'is_active' => true
            ],
            [
                'name' => 'Mercado Pago',
                'description' => 'Pago realizado mediante Mercado Pago',
                'is_active' => true
            ],
            [
                'name' => 'Payoneer',
                'description' => 'Pago realizado mediante Payoneer',
                'is_active' => true
            ],
            [
                'name' => 'Skrill',
                'description' => 'Pago realizado mediante Skrill',
                'is_active' => true
            ],
            [
                'name' => 'Western Union',
                'description' => 'Pago realizado mediante Western Union',
                'is_active' => true
            ],
        ];

        foreach ($types as $type) {
            PaymentType::create($type);
        }
    }
} 