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
        // Hacer nullable inventory_id y product_id en reports
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('inventory_id')->nullable()->change();
            $table->foreignId('product_id')->nullable()->change();
        });
        
        // warehouse_id ya existe desde la creación de la tabla report_items
        // No es necesario agregarlo nuevamente
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('inventory_id')->nullable(false)->change();
            $table->foreignId('product_id')->nullable(false)->change();
        });
        
        // No se elimina warehouse_id porque existe desde la creación de la tabla
    }
};
