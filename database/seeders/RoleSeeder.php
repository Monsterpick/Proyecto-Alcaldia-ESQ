<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);
        $beneficiario = Role::create(['name' => 'Beneficiario']);
        $coordinador = Role::create(['name' => 'Coordinador']);
        $operador = Role::create(['name' => 'Operador']);
        $administrador = Role::create(['name' => 'Administrador']);

        $admin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'view-user',
            'download-user',
            'view-role',
            'create-role',
            'edit-role',
            'delete-role',
            'download-role',
            'view-activitylog',
            'create-activitylog',
            'edit-activitylog',
            'delete-activitylog',
            'download-activitylog',
            'view-inventory',
            'create-inventory',
            'edit-inventory',
            'delete-inventory',
            'download-inventory',
            'view-product',
            'create-product',
            'edit-product',
            'delete-product',
            'download-product',
            'view-warehouse',
            'create-warehouse',
            'edit-warehouse',
            'delete-warehouse',
            'download-warehouse',
            'view-category',
            'create-category',
            'edit-category',
            'delete-category',
            'download-category',
            'view-movement',
            'create-movement',
            'edit-movement',
            'delete-movement',
            'download-movement',
            'view-stock-adjustment',
            'create-stock-adjustment',
            'edit-stock-adjustment',
            'delete-stock-adjustment',
            'download-stock-adjustment',
            'view-inventory-entry',
            'create-inventory-entry',
            'edit-inventory-entry',
            'delete-inventory-entry',
            'download-inventory-entry',
            'view-inventory-exit',
            'create-inventory-exit',
            'edit-inventory-exit',
            'delete-inventory-exit',
            'download-inventory-exit',
            'view-community-project',
            'create-community-project',
            'edit-community-project',
            'delete-community-project',
            'download-community-project',
            'view-project-in-progress',
            'create-project-in-progress',
            'edit-project-in-progress',
            'delete-project-in-progress',
            'view-project-executed',
            'create-project-executed',
            'edit-project-executed',
            'delete-project-executed',
            'view-project-proposed',
            'create-project-proposed',
            'edit-project-proposed',
            'delete-project-proposed',
        ]);

        $user->givePermissionTo([
            'view-user',
        ]);
    }
}
