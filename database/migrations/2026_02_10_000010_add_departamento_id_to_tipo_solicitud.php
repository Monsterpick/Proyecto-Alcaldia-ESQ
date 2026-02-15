<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipo_solicitud', function (Blueprint $table) {
            $table->foreignId('departamento_id')->nullable()->after('activo')->constrained('departamentos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tipo_solicitud', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
        });
    }
};
