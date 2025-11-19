<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\Inventory;
use Carbon\Carbon;

class DashboardDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Creando datos de ejemplo para el dashboard...');

        // Obtener categorías y almacenes existentes
        $categories = Category::all();
        $warehouses = Warehouse::all();

        if ($categories->isEmpty()) {
            $this->command->error('❌ No hay categorías. Ejecuta CategorySeeder primero.');
            return;
        }

        if ($warehouses->isEmpty()) {
            $this->command->error('❌ No hay almacenes. Ejecuta WarehouseSeeder primero.');
            return;
        }

        // Crear productos de ejemplo organizados por categoría
        $productos = [
            // ALIMENTOS (Productos de la canasta básica)
            ['name' => 'Arroz (1kg)', 'category' => 'Alimentos y Despensa', 'description' => 'Arroz blanco de primera calidad'],
            ['name' => 'Aceite (1L)', 'category' => 'Alimentos y Despensa', 'description' => 'Aceite vegetal comestible'],
            ['name' => 'Pasta (500g)', 'category' => 'Alimentos y Despensa', 'description' => 'Pasta alimenticia'],
            ['name' => 'Harina de Trigo (1kg)', 'category' => 'Alimentos y Despensa', 'description' => 'Harina de trigo para pan'],
            ['name' => 'Azúcar (1kg)', 'category' => 'Alimentos y Despensa', 'description' => 'Azúcar refinada'],
            ['name' => 'Leche en Polvo (400g)', 'category' => 'Alimentos y Despensa', 'description' => 'Leche en polvo fortificada'],
            ['name' => 'Sal (1kg)', 'category' => 'Alimentos y Despensa', 'description' => 'Sal de mesa yodada'],
            ['name' => 'Café (500g)', 'category' => 'Alimentos y Despensa', 'description' => 'Café molido'],
            ['name' => 'Sardinas en Lata', 'category' => 'Alimentos y Despensa', 'description' => 'Sardinas en aceite'],
            ['name' => 'Atún en Lata', 'category' => 'Alimentos y Despensa', 'description' => 'Atún en agua'],
            
            // HIGIENE PERSONAL
            ['name' => 'Jabón de Tocador', 'category' => 'Higiene Personal', 'description' => 'Jabón antibacterial'],
            ['name' => 'Pasta Dental (120ml)', 'category' => 'Higiene Personal', 'description' => 'Pasta dental con flúor'],
            ['name' => 'Shampoo (400ml)', 'category' => 'Higiene Personal', 'description' => 'Shampoo para todo tipo de cabello'],
            ['name' => 'Papel Higiénico (4 rollos)', 'category' => 'Higiene Personal', 'description' => 'Papel higiénico doble hoja'],
            ['name' => 'Desodorante', 'category' => 'Higiene Personal', 'description' => 'Desodorante en barra'],
            ['name' => 'Cepillo Dental', 'category' => 'Higiene Personal', 'description' => 'Cepillo dental de cerdas suaves'],
            ['name' => 'Toallas Sanitarias', 'category' => 'Higiene Personal', 'description' => 'Toallas sanitarias con alas'],
            
            // MEDICAMENTOS
            ['name' => 'Paracetamol 500mg', 'category' => 'Medicamentos', 'description' => 'Analgésico y antipirético'],
            ['name' => 'Ibuprofeno 400mg', 'category' => 'Medicamentos', 'description' => 'Antiinflamatorio'],
            ['name' => 'Acetaminofén Infantil', 'category' => 'Medicamentos', 'description' => 'Jarabe para niños'],
            ['name' => 'Sales de Rehidratación', 'category' => 'Medicamentos', 'description' => 'Sobres de electrolitos'],
            ['name' => 'Vitaminas Multivitamínico', 'category' => 'Medicamentos', 'description' => 'Complejo vitamínico'],
            ['name' => 'Antiácido', 'category' => 'Medicamentos', 'description' => 'Tabletas antiácidas'],
            ['name' => 'Alcohol Isopropílico', 'category' => 'Medicamentos', 'description' => 'Alcohol medicinal 70%'],
            ['name' => 'Gasas Estériles', 'category' => 'Medicamentos', 'description' => 'Gasas estériles 10x10cm'],
        ];

        $createdProducts = [];
        foreach ($productos as $prod) {
            $category = $categories->firstWhere('name', $prod['category']) ?? $categories->first();
            
            // Generar SKU sin caracteres especiales
            $skuBase = preg_replace('/[^A-Z0-9]/', '', strtoupper($prod['name']));
            $sku = 'SKU-' . substr($skuBase, 0, 3) . '-' . rand(100, 999);
            
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $prod['name'],
                'sku' => $sku,
                'barcode' => null,
                'description' => $prod['description'],
                'unit_type' => 'unidad',
                'price' => rand(100, 1000),
                'is_active' => true,
            ]);

            $createdProducts[] = $product;
        }

        $this->command->info('✅ ' . count($createdProducts) . ' productos creados');

        // Crear registros de inventario usando batch inserts (optimizado)
        $warehouse = $warehouses->first();
        $inventoryBatch = [];
        $now = Carbon::now();
        
        foreach ($createdProducts as $product) {
            // Entradas de inventario
            $cantidadEntradas = rand(3, 6);
            for ($i = 0; $i < $cantidadEntradas; $i++) {
                $quantityIn = rand(10, 100);
                $costIn = rand(50, 200);
                
                $inventoryBatch[] = [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'detail' => 'Entrada de inventario - ' . $product->name,
                    'quantity_in' => $quantityIn,
                    'cost_in' => $costIn,
                    'total_in' => $quantityIn * $costIn,
                    'quantity_out' => 0,
                    'cost_out' => 0,
                    'total_out' => 0,
                    'quantity_balance' => $quantityIn,
                    'cost_balance' => $costIn,
                    'total_balance' => $quantityIn * $costIn,
                    'inventoryable_type' => null,
                    'inventoryable_id' => null,
                    'created_at' => $now->copy()->subDays(rand(1, 60)),
                    'updated_at' => $now,
                ];
            }

            // Salidas de inventario
            $cantidadSalidas = rand(2, 4);
            for ($i = 0; $i < $cantidadSalidas; $i++) {
                $quantityOut = rand(5, 30);
                $costOut = rand(50, 200);
                
                $inventoryBatch[] = [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'detail' => 'Salida de inventario - Distribución',
                    'quantity_in' => 0,
                    'cost_in' => 0,
                    'total_in' => 0,
                    'quantity_out' => $quantityOut,
                    'cost_out' => $costOut,
                    'total_out' => $quantityOut * $costOut,
                    'quantity_balance' => -$quantityOut,
                    'cost_balance' => $costOut,
                    'total_balance' => -$quantityOut * $costOut,
                    'inventoryable_type' => null,
                    'inventoryable_id' => null,
                    'created_at' => $now->copy()->subDays(rand(1, 30)),
                    'updated_at' => $now,
                ];
            }
        }

        // Insertar todos los registros en un solo batch (mucho más rápido)
        Inventory::insert($inventoryBatch);
        
        $this->command->info('✅ ' . count($inventoryBatch) . ' registros de inventario creados (batch insert)');
        $this->command->info('');
        $this->command->info('🎉 Dashboard listo con datos de ejemplo!');
        $this->command->info('📊 Productos: ' . Product::count());
        $this->command->info('📦 Registros de inventario: ' . Inventory::count());
    }
}
