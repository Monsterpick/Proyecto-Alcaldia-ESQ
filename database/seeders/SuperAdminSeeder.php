<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o actualizar el usuario Super Admin
        $user = User::updateOrCreate(
            ['email' => 'ag@gmail.com'],
            [
                'name' => 'Super Admin',
                'last_name' => 'AG',
                'password' => Hash::make('1234'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol de Super Admin
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole($role);

        // Asignar todos los permisos al rol Super Admin
        $allPermissions = Permission::all();
        $role->syncPermissions($allPermissions);

        $this->command->info('✅ Usuario Super Admin creado exitosamente');
        $this->command->info('📧 Email: ag@gmail.com');
        $this->command->info('🔑 Password: 1234');
        $this->command->info('🔐 Permisos: ' . $allPermissions->count() . ' permisos asignados');
    }
}
