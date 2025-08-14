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
            'name' => 'Silvio',
            'last_name' => 'Ramírez',
            'document' => 'V20428781',
            'phone' => '+584126713413',
            'email' => 'silvio.ramirez.m@gmail.com',
            'password' => Hash::make('S0p0rt3!'),
            'email_verified_at' => now(),
        ]);

        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'name' => 'Jhonny',
            'last_name' => 'Torres',
            'document' => 'V20428781',
            'phone' => '+584126713413',
            'email' => 'jhonnytorresforro@gmail.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('Super Admin');
    }
}
