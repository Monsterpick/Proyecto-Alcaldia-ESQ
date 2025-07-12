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
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del tipo de pago (ej: Transferencia, Efectivo, etc)
            $table->string('description')->nullable(); // Descripción opcional
            $table->boolean('is_active')->default(true); // Para desactivar tipos de pago si es necesario
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_types');
    }
}; 