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
            $table->string('email')->unique();
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

            // Ciudadano que hace la solicitud
            $table->foreignId('ciudadano_id')
                ->constrained('ciudadanos')
                ->onDelete('cascade');

            // Tipo de solicitud
            $table->foreignId('tipo_solicitud_id')
                ->constrained('tipo_solicitud')
                ->onDelete('restrict');

            // Contenido
            $table->longText('descripcion');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->boolean('acepta_terminos')->default(true);

            // Respuesta
            $table->timestamp('fecha_respuesta')->nullable();
            $table->text('respuesta')->nullable();

            $table->timestamps();

            $table->index('estado');
            $table->index('created_at');
        });

        // 4. Tabla de categorías de participación
        Schema::create('categorias_participacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 5. Tabla de sesiones municipales
        Schema::create('sesions_municipal', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->foreignId('categoria_participacion_id')
                ->constrained('categorias_participacion')
                ->onDelete('cascade');
            $table->dateTime('fecha_hora');
            $table->enum('estado', ['proxima', 'abierta', 'cerrada', 'completada'])
                ->default('proxima');
            $table->timestamps();

            $table->index('fecha_hora');
            $table->index('estado');
        });

        // 6. Tabla de derecho de palabra
        Schema::create('derecho_palabra', function (Blueprint $table) {
            $table->id();

            // Ciudadano que solicita derecho de palabra
            $table->foreignId('ciudadano_id')
                ->constrained('ciudadanos')
                ->onDelete('cascade');

            // Sesión donde quiere hablar
            $table->foreignId('sesion_municipal_id')
                ->nullable()
                ->constrained('sesions_municipal')
                ->onDelete('set null');

            // Comisión (si aplica)
            $table->foreignId('comision_id')
                ->nullable()
                ->constrained('comisions')
                ->onDelete('set null');

            // Detalles
            $table->text('motivo_solicitud');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])
                ->default('pendiente');
            $table->text('observaciones')->nullable();

            // Respuesta
            $table->timestamp('fecha_respuesta')->nullable();
            $table->boolean('acepta_terminos')->default(true);

            $table->timestamps();

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('derecho_palabra');
        Schema::dropIfExists('sesions_municipal');
        Schema::dropIfExists('categorias_participacion');
        Schema::dropIfExists('solicitudes');
        Schema::dropIfExists('tipo_solicitud');
        Schema::dropIfExists('ciudadanos');
    }
};
