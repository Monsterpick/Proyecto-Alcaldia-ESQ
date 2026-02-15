<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Roles del sistema: Super Admin, Alcalde, Analista, Operador.
 * Crea roles con permisos y usuarios ficticios para pruebas.
 */
class RolesSistemaSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->syncRoles();

        // Crear usuarios ficticios si no existen
        $this->createFictitiousUsers();
    }

    private function syncRoles(): void
    {
        $guard = 'web';

        // 1) Super Admin - tiene todos los permisos (ya manejado por Gate::before)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => $guard]);
        $superAdmin->syncPermissions(Permission::all());

        // 2) Alcalde (Administrador) - todo excepto Config General (view-super-admin-config)
        $alcalde = Role::firstOrCreate(['name' => 'Alcalde', 'guard_name' => $guard]);
        $permisosAlcalde = Permission::where('name', '!=', 'view-super-admin-config')->pluck('name');
        $alcalde->syncPermissions($permisosAlcalde);

        // 3) Analista - solo Directores, Departamentos, Solicitudes. SIN dashboard/estadísticas.
        $analista = Role::firstOrCreate(['name' => 'Analista', 'guard_name' => $guard]);
        $permisosAnalista = [
            'view-director', 'create-director', 'edit-director', 'view-departamento', 'create-departamento', 'edit-departamento',
            'view-solicitud',
            'profile-setting',
        ];
        $analista->syncPermissions($permisosAnalista);

        // 4) Operador - Beneficiarios, Inventario, Movimientos (sin delete historial), Mapa, Reportes, Dashboard
        $operador = Role::firstOrCreate(['name' => 'Operador', 'guard_name' => $guard]);
        $permisosOperador = [
            'view-dashboard',
            'view-beneficiary', 'create-beneficiary', 'edit-beneficiary',
            'view-inventory', 'view-product', 'view-warehouse', 'view-category', 'view-stock-adjustment',
            'view-movement', 'create-movement', 'edit-movement', 'view-inventory-entry', 'create-inventory-entry',
            'edit-inventory-entry', 'view-inventory-exit', 'create-inventory-exit', 'edit-inventory-exit',
            'view-report', 'view-map',
            'profile-setting',
        ];
        $operador->syncPermissions($permisosOperador);

        // 5) Director - solo solicitudes de su departamento (ver, aprobar, rechazar, pendiente, descargar PDF). Sin eliminar.
        $director = Role::firstOrCreate(['name' => 'Director', 'guard_name' => $guard]);
        $director->syncPermissions([
            'view-solicitud',
            'profile-setting',
        ]);
    }

    private function createFictitiousUsers(): void
    {
        $usuarios = [
            [
                'email' => 'alcalde@alcaldia.escuque.com',
                'name' => 'Juan',
                'last_name' => 'Alcalde',
                'document' => 'V12345678',
                'phone' => '+584121234567',
                'password' => 'alcalde123',
                'role' => 'Alcalde',
            ],
            [
                'email' => 'analista@alcaldia.escuque.com',
                'name' => 'María',
                'last_name' => 'Analista',
                'document' => 'V23456789',
                'phone' => '+584122345678',
                'password' => 'analista123',
                'role' => 'Analista',
            ],
            [
                'email' => 'operador@alcaldia.escuque.com',
                'name' => 'Carlos',
                'last_name' => 'Operador',
                'document' => 'V34567890',
                'phone' => '+584123456789',
                'password' => 'operador123',
                'role' => 'Operador',
            ],
        ];

        foreach ($usuarios as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'last_name' => $data['last_name'],
                    'document' => $data['document'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles([]);
            $user->assignRole($data['role']);
        }

        $this->command->info('Usuarios ficticios creados/actualizados: Alcalde, Analista, Operador');
    }
}
