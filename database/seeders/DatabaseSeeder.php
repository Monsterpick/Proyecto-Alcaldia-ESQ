<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
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
            DefaultUserSeeder::class,
            ActividadSeeder::class,
            EstatusSeeder::class,
            PlanSeeder::class,
            PaymentTypeSeeder::class,
            PaymentOriginSeeder::class,
            TenantSeeder::class,
            AppointmentStatusSeeder::class,
            CategorySeeder::class,
            IdentitySeeder::class,
            ReasonSeeder::class,
            WarehouseSeeder::class,
        ]);

        Customer::factory(100)->create();
        Supplier::factory(100)->create();
        Product::factory(100)->create();
    }
}
