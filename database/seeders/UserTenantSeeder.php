<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor = User::create([
            'name' => 'Test Name',
            'last_name' => 'Test Last Name',
            'document' => 'V21231456',
            'phone' => '+584126713413',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
        ]);

        $doctor->assignRole(roles: 'Doctor');
    }
}
