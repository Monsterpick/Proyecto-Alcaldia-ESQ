<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'currency_code',
                'value' => 'USD',
                'type' => 'string',
                'group' => 'currency',
                'name' => 'Código de Moneda',
                'description' => 'Código ISO de la moneda principal del sistema (USD, EUR, etc.)',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
                'type' => 'string',
                'group' => 'currency',
                'name' => 'Símbolo de Moneda',
                'description' => 'Símbolo para mostrar junto a los montos ($, €, etc.)',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'currency_position',
                'value' => 'before',
                'type' => 'string',
                'group' => 'currency',
                'name' => 'Posición del Símbolo',
                'description' => 'Posición del símbolo de moneda (before: $100, after: 100$)',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'available_currencies',
                'value' => json_encode([
                    ['code' => 'USD', 'symbol' => '$', 'name' => 'Dólar Estadounidense'],
                    ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
                    ['code' => 'GBP', 'symbol' => '£', 'name' => 'Libra Esterlina'],
                    ['code' => 'VES', 'symbol' => 'Bs.', 'name' => 'Bolívar Digital'],
                ]),
                'type' => 'json',
                'group' => 'currency',
                'name' => 'Monedas Disponibles',
                'description' => 'Lista de monedas disponibles en el sistema',
                'is_public' => true,
                'is_tenant_editable' => false,
            ],
            [
                'key' => 'decimal_separator',
                'value' => '.',
                'type' => 'string',
                'group' => 'currency',
                'name' => 'Separador Decimal',
                'description' => 'Carácter usado como separador decimal (. o ,)',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'thousand_separator',
                'value' => ',',
                'type' => 'string',
                'group' => 'currency',
                'name' => 'Separador de Miles',
                'description' => 'Carácter usado como separador de miles (, o .)',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
