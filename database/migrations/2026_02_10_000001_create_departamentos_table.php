<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->foreignId('director_id')->nullable()->constrained('directores')->nullOnDelete();
            $table->text('servicios_generales')->nullable();
            $table->timestamps();

            $table->index('nombre');
        });

        Schema::table('directores', function (Blueprint $table) {
            $table->foreignId('departamento_id')->nullable()->after('email')->constrained('departamentos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('directores', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
        });
        Schema::dropIfExists('departamentos');
    }
};
