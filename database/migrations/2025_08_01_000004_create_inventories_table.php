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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->string('detail')->nullable();
            $table->integer('quantity_in')->default(0);
            $table->decimal('cost_in', 12, 2)->default(0);
            $table->decimal('total_in', 12, 2)->default(0);
            $table->integer('quantity_out')->default(0);
            $table->decimal('cost_out', 12, 2)->default(0);
            $table->decimal('total_out', 12, 2)->default(0);
            $table->integer('quantity_balance')->default(0);
            $table->decimal('cost_balance', 12, 2)->default(0);
            $table->decimal('total_balance', 12, 2)->default(0);
            $table->morphs('inventoryable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
