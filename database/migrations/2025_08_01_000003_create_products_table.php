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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->enum('unit_type', ['unidad', 'caja', 'litro', 'kilo', 'servicio', 'paquete', 'otro'])->default('unidad');
            $table->text('description')->nullable();
            $table->text('observation')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('qrcode')->nullable();
            $table->date('expedition_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
