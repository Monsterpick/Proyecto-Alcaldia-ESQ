<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Gex',
            'last_name' => 'y Angel',
            'document' => 'V00000000',
            'phone' => '+584120000000',
            'email' => 'ag@gmail.com',
            'password' => Hash::make('1234'),
            'email_verified_at' => now(),
        ]);

        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'name' => 'Alejandro',
            'last_name' => 'Admin',
            'document' => 'V20428781',
            'phone' => '+584126713413',
            'email' => 'alejandro@admin.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('Super Admin');
    }
}
