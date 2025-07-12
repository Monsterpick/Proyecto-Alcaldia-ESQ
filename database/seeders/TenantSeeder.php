<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'OPTIRANGO',
            'razon_social' => 'OPTIRANGO, C.A.',
            'rif' => 'J-505868454',
            'direccion_fiscal' => 'AV. URDANETA ESQ PELOTA EDIF CENTRO PROFESIONAL URDANETA',
            'actividad_id' => 1,
            'telefono_principal' => '+584246406797',
            'telefono_secundario' => '+584126426797',
            'email_principal' => 'jhonnytorresforro@gmail.com',
            'email_secundario' => 'silvio.ramirez.m@gmail.com',
            'estado_id' => 24,
            'municipio_id' => 462,
            'parroquia_id' => 1126,
            'responsable' => 'Jhonny Torres',
            'cargo_responsable' => 'Gerente General',
            'telefono_responsable' => '+584246406797',
            'email_responsable' => 'jhonnytorresforro@gmail.com',
            'plan_id' => 1,
            'estatus_id' => 1,
            'domain' => 'optirango.nevora.app',
        ]);

        

    }
}
