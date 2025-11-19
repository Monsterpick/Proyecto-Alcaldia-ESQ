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
                'name' => 'Medicamentos',
                'description' => 'Medicamentos, insumos médicos y productos farmacéuticos',
                'icon' => 'fa-solid fa-pills',
                'is_active' => true,
            ],
            [
                'name' => 'Alimentos y Despensa',
                'description' => 'Alimentos no perecederos, productos de despensa y ayuda alimentaria',
                'icon' => 'fa-solid fa-basket-shopping',
                'is_active' => true,
            ],
            [
                'name' => 'Educación y Útiles',
                'description' => 'Útiles escolares, libros, material educativo y apoyo académico',
                'icon' => 'fa-solid fa-book',
                'is_active' => true,
            ],
            [
                'name' => 'Vivienda',
                'description' => 'Materiales de construcción, mejoras de vivienda y equipamiento del hogar',
                'icon' => 'fa-solid fa-house',
                'is_active' => true,
            ],
            [
                'name' => 'Ayudas técnicas',
                'description' => 'Ayudas técnicas, dispositivos y recursos de apoyo social comunitario',
                'icon' => 'fa-solid fa-hands-holding-circle',
                'is_active' => true,
            ],
            [
                'name' => 'Higiene Personal',
                'description' => 'Productos de higiene y cuidado personal',
                'icon' => 'fa-solid fa-pump-soap',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
