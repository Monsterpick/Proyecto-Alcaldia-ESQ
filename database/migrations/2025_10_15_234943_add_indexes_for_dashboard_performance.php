<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Índice para filtrar por quantity_out en consultas de salidas
            $table->index('quantity_out', 'idx_inventories_quantity_out');
            
            // Índice para filtrar por quantity_in en consultas de entradas
            $table->index('quantity_in', 'idx_inventories_quantity_in');
            
            // Índice compuesto para consultas por fecha
            $table->index(['created_at'], 'idx_inventories_created_at');
            
            // Índice compuesto para consultas del dashboard (product_id + quantity_out)
            $table->index(['product_id', 'quantity_out'], 'idx_inventories_product_out');
        });

        Schema::table('products', function (Blueprint $table) {
            // Índice para relación con categorías (ya existe como foreign key pero lo optimizamos)
            if (!Schema::hasIndex('products', 'idx_products_category')) {
                $table->index('category_id', 'idx_products_category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropIndex('idx_inventories_quantity_out');
            $table->dropIndex('idx_inventories_quantity_in');
            $table->dropIndex('idx_inventories_created_at');
            $table->dropIndex('idx_inventories_product_out');
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasIndex('products', 'idx_products_category')) {
                $table->dropIndex('idx_products_category');
            }
        });
    }
};
