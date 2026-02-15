<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('directores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('tipo_documento', 20);
            $table->string('cedula', 20)->unique();
            $table->date('fecha_nacimiento');
            $table->string('telefono', 30);
            $table->string('email');
            $table->string('departamento_nombre_pendiente')->nullable();
            $table->timestamps();

            $table->index('cedula');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directores');
    }
};
