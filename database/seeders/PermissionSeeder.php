<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Roles
            'create-role',
            'edit-role',
            'delete-role',
            'view-role',
            'download-role',
            
            // Users
            'create-user',
            'edit-user',
            'delete-user',
            'view-user',
            'download-user',
            
            // Activity Log
            'view-activitylog',
            'create-activitylog',
            'edit-activitylog',
            'delete-activitylog',
            'download-activitylog',
            
            // Permissions
            'create-permission',
            'edit-permission',
            'delete-permission',
            'view-permission',
            'download-permission',
            
            // Tenants
            'view-tenant',
            'create-tenant',
            'edit-tenant',
            'delete-tenant',
            'download-tenant',
            
            // Dashboard
            'view-dashboard',
            'create-dashboard',
            'edit-dashboard',
            'delete-dashboard',
            'download-dashboard',

            // Actividades
            'view-actividad',
            'create-actividad',
            'edit-actividad',
            'delete-actividad',
            'download-actividad',

            // Estados
            'view-estado',
            'create-estado',
            'edit-estado',
            'delete-estado',
            'download-estado',

            // Estatus
            'view-estatus',
            'create-estatus',
            'edit-estatus',
            'delete-estatus',
            'download-estatus',

            // Municipios
            'view-municipio',
            'create-municipio',
            'edit-municipio',
            'delete-municipio',
            'download-municipio',

            // Parroquias
            'view-parroquia',
            'create-parroquia',
            'edit-parroquia',
            'delete-parroquia',
            'download-parroquia',

            // Planes
            'view-plan',
            'create-plan',
            'edit-plan',
            'delete-plan',
            'download-plan',

            // Pagos
            'view-tenant-payment',
            'create-tenant-payment',
            'edit-tenant-payment',
            'delete-tenant-payment',
            'download-tenant-payment',

            // Tipos de pago
            'view-payment-type',
            'create-payment-type',
            'edit-payment-type',
            'delete-payment-type',
            'download-payment-type',

            // Orígenes de pago
            'view-payment-origin',
            'create-payment-origin',
            'edit-payment-origin',
            'delete-payment-origin',
            'download-payment-origin',
        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
