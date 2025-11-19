<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use HasFactory;
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
            CircuitoComunalSeeder::class, // 85 Circuitos Comunales organizados por parroquia
            DefaultUserSeeder::class,
            EstatusSeeder::class,
            PaymentTypeSeeder::class,
            PaymentOriginSeeder::class,
            CategorySeeder::class,
            WarehouseSeeder::class,
        ]);
    }
}
