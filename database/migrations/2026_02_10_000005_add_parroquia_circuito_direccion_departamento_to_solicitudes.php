<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->foreignId('departamento_id')->nullable()->after('tipo_solicitud_id')->constrained('departamentos')->nullOnDelete();
            $table->foreignId('parroquia_id')->nullable()->after('departamento_id')->constrained('parroquias')->nullOnDelete();
            $table->foreignId('circuito_comunal_id')->nullable()->after('parroquia_id')->constrained('circuito_comunals')->nullOnDelete();
            $table->string('direccion_exacta')->nullable()->after('direccion');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropForeign(['parroquia_id']);
            $table->dropForeign(['circuito_comunal_id']);
            $table->dropForeign(['departamento_id']);
            $table->dropColumn(['direccion_exacta']);
        });
    }
};
