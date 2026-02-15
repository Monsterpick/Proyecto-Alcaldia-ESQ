<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DirectorDepartamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view-director', 'create-director', 'edit-director', 'delete-director',
            'view-departamento', 'create-departamento', 'edit-departamento', 'delete-departamento',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        foreach (['admin', 'Administrador', 'Super Admin'] as $roleName) {
            $role = Role::firstWhere('name', $roleName);
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }
}
