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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json, etc.
            $table->string('group')->default('general'); // Para agrupar configuraciones: general, currency, email, etc.
            $table->string('name'); // Nombre amigable para mostrar en la interfaz
            $table->text('description')->nullable(); // Descripción de la configuración
            $table->boolean('is_public')->default(true); // Si la configuración es pública o privada
            $table->boolean('is_tenant_editable')->default(false); // Si los tenants pueden editar esta configuración
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
