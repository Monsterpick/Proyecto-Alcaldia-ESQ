<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'MENSUAL',
            'description' => 'Plan mensual',
            'price' => '40.00',
            'trial_period_days' => '30',
            'active' => '1',
        ]);

        Plan::create([
            'name' => 'ANUAL',
            'description' => 'Plan anual',
            'price' => '300.00',
            'trial_period_days' => '30',
            'active' => '1',
        ]);

        Plan::create([
            'name' => 'SEMESTRAL',
            'description' => 'Plan semestral',
            'price' => '150.00',
            'trial_period_days' => '30',
            'active' => '1',
        ]);

        Plan::create([
            'name' => 'TRIMESTRAL',
            'description' => 'Plan trimestral',
            'price' => '75.00',
            'trial_period_days' => '30',
            'active' => '1',
        ]);
    }
}
