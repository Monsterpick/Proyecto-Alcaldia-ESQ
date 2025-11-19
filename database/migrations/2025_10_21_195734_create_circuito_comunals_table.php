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
        Schema::create('circuito_comunals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parroquia_id')->constrained('parroquias')->onDelete('cascade');
            $table->string('nombre'); // Nombre del circuito comunal
            $table->string('codigo', 50)->unique(); // Código único del circuito (ej: CC-ESC-001) - REQUERIDO
            $table->text('descripcion')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índices para mejor rendimiento
            $table->index('parroquia_id');
            $table->index('codigo');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circuito_comunals');
    }
};
