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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('beneficiary_id')->nullable()->constrained('beneficiaries')->onDelete('set null');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Información del reporte
            $table->string('report_code')->unique(); // Código único del reporte
            $table->integer('quantity'); // Cantidad entregada
            $table->text('delivery_detail')->nullable(); // Detalle de la entrega
            
            // Datos del beneficiario (copiados en el momento)
            $table->string('beneficiary_first_name');
            $table->string('beneficiary_last_name');
            $table->string('beneficiary_cedula');
            $table->string('beneficiary_document_type')->default('V');
            $table->string('beneficiary_phone')->nullable();
            $table->string('beneficiary_email')->nullable();
            
            // Ubicación
            $table->string('country')->default('Venezuela');
            $table->string('state')->nullable();
            $table->string('municipality')->nullable();
            $table->string('parish')->nullable();
            $table->string('sector')->nullable();
            $table->text('address')->nullable();
            $table->string('reference_point')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Circuito Comunal (OBLIGATORIO)
            $table->string('communal_circuit');
            
            // Información adicional
            $table->date('delivery_date'); // Fecha de entrega
            $table->text('notes')->nullable(); // Notas adicionales
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('report_code');
            $table->index('delivery_date');
            $table->index('communal_circuit');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
