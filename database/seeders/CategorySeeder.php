<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrónica',
                'description' => 'Productos electrónicos',
            ],
            [
                'name' => 'Accesorios',
                'description' => 'Productos electrónicos',
            ],
            [
                'name' => 'Optica',
                'description' => 'Productos electrónicos',
            ],
            [
                'name' => 'Lentes',
                'description' => 'Productos electrónicos',
            ],
            
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
