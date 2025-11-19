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
        Schema::table('report_items', function (Blueprint $table) {
            // Verificar si warehouse_id existe, si no, agregarla
            if (!Schema::hasColumn('report_items', 'warehouse_id')) {
                $table->foreignId('warehouse_id')->nullable()->after('product_id')->constrained();
            }
            
            // Modificar inventory_id para que acepte NULL
            $table->foreignId('inventory_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_items', function (Blueprint $table) {
            // Revertir inventory_id a NOT NULL
            $table->foreignId('inventory_id')->nullable(false)->change();
        });
    }
};
