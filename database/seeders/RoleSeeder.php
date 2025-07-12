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
        $paciente = Role::create(['name' => 'Paciente']);
        $doctor = Role::create(['name' => 'Doctor']);
        $recepcionista = Role::create(['name' => 'Recepcionista']);
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
        ]);

        $user->givePermissionTo([
            'view-user',
        ]);
    }
}
