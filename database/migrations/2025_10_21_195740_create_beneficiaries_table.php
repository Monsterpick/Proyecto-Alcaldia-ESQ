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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            
            // Información Personal
            $table->string('first_name'); // Primer nombre
            $table->string('second_name')->nullable(); // Segundo nombre
            $table->string('last_name'); // Apellido
            $table->string('second_last_name')->nullable(); // Segundo apellido
            $table->string('document_type')->default('V'); // V, E, J, G, P
            $table->string('cedula')->unique(); // Cédula única
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Ubicación Geográfica (usando datos de Direcciones)
            $table->string('state')->default('Trujillo'); // Fijo
            $table->string('municipality')->default('Escuque'); // Fijo
            $table->foreignId('parroquia_id')->constrained('parroquias')->onDelete('cascade');
            $table->foreignId('circuito_comunal_id')->constrained('circuito_comunals')->onDelete('cascade');
            $table->string('sector')->nullable(); // Sector específico
            $table->string('reference_point')->nullable(); // Punto de referencia
            $table->text('address')->nullable(); // Dirección exacta
            
            // Estado del beneficiario
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
