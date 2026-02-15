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

            // Settings
            'view-setting',
            'create-setting',
            'edit-setting',
            'delete-setting',
            'download-setting',

            // Direcciones
            'view-direccion',

            // General
            'view-general',

            // Profile
            'profile-setting',

            // Inventario/Almacén
            'view-inventory',
            'create-inventory',
            'edit-inventory',
            'delete-inventory',
            'download-inventory',

            // Productos
            'view-product',
            'create-product',
            'edit-product',
            'delete-product',
            'download-product',

            // Almacenes
            'view-warehouse',
            'create-warehouse',
            'edit-warehouse',
            'delete-warehouse',
            'download-warehouse',

            // Categorías
            'view-category',
            'create-category',
            'edit-category',
            'delete-category',
            'download-category',

            // Movimientos
            'view-movement',
            'create-movement',
            'edit-movement',
            'delete-movement',
            'download-movement',

            // Ajustes de Stock
            'view-stock-adjustment',
            'create-stock-adjustment',
            'edit-stock-adjustment',
            'delete-stock-adjustment',
            'download-stock-adjustment',

            // Entrada de Inventario
            'view-inventory-entry',
            'create-inventory-entry',
            'edit-inventory-entry',
            'delete-inventory-entry',
            'download-inventory-entry',

            // Salida de Inventario
            'view-inventory-exit',
            'create-inventory-exit',
            'edit-inventory-exit',
            'delete-inventory-exit',
            'download-inventory-exit',

            // Proyectos Comunitarios
            'view-community-project',
            'create-community-project',
            'edit-community-project',
            'delete-community-project',
            'download-community-project',

            // Proyectos en Proceso
            'view-project-in-progress',
            'create-project-in-progress',
            'edit-project-in-progress',
            'delete-project-in-progress',

            // Proyectos Ejecutados
            'view-project-executed',
            'create-project-executed',
            'edit-project-executed',
            'delete-project-executed',

            // Proyectos Propuestos
            'view-project-proposed',
            'create-project-proposed',
            'edit-project-proposed',
            'delete-project-proposed',

            // Directores y Departamentos
            'view-director',
            'create-director',
            'edit-director',
            'delete-director',
            'view-departamento',
            'create-departamento',
            'edit-departamento',
            'delete-departamento',

            // Solicitudes Alcaldía Digital
            'view-solicitud',
            'delete-solicitud',

            // Config exclusiva Super Admin (Datos empresa, Moneda, Logos, Colores, Roles, Permisos, etc.)
            'view-super-admin-config',

            // Beneficiarios
            'view-beneficiary',
            'create-beneficiary',
            'edit-beneficiary',
            'delete-beneficiary',

            // Reportes y Mapa
            'view-report',
            'view-map',
        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
