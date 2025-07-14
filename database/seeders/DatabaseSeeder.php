<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SettingsSeeder::class,
            EstadoSeeder::class,
            MunicipioSeeder::class,
            ParroquiaSeeder::class,
            DefaultUserSeeder::class,
            ActividadSeeder::class,
            EstatusSeeder::class,
            PlanSeeder::class,
            PaymentTypeSeeder::class,
            PaymentOriginSeeder::class,
            TenantSeeder::class,
            AppointmentStatusSeeder::class,
        ]);
    }
}
