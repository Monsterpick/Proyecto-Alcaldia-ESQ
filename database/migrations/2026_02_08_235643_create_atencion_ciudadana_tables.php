<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de ciudadanos (datos personales centralizados)
        Schema::create('ciudadanos', function (Blueprint $table) {
            $table->id();
            $table->string('cedula')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email');
            $table->string('telefono_movil');
            $table->string('whatsapp');
            $table->boolean('whatsapp_send')->default(true);
            $table->timestamps();

            $table->index('cedula');
            $table->index('email');
        });

        // 2. Tabla de tipos de solicitud
        Schema::create('tipo_solicitud', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 3. Tabla de solicitudes (atención ciudadana)
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ciudadano_id')
                ->constrained('ciudadanos')
                ->onDelete('cascade');

            $table->foreignId('tipo_solicitud_id')
                ->constrained('tipo_solicitud')
                ->onDelete('restrict');

            $table->longText('descripcion');
            $table->string('direccion'); // Dirección obligatoria del ciudadano
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->boolean('acepta_terminos')->default(true);

            // Respuesta del funcionario
            $table->timestamp('fecha_respuesta')->nullable();
            $table->text('respuesta')->nullable();

            $table->timestamps();

            $table->index('estado');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
        Schema::dropIfExists('tipo_solicitud');
        Schema::dropIfExists('ciudadanos');
    }
};
