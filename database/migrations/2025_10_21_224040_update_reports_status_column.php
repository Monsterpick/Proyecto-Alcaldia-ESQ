<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero cambiamos la columna a string
        Schema::table('reports', function (Blueprint $table) {
            $table->string('status')->default('in_process')->change();
        });
        
        // Luego actualizamos los datos existentes
        DB::table('reports')->where('status', 'completed')->update(['status' => 'delivered']);
        DB::table('reports')->where('status', 'pending')->update(['status' => 'in_process']);
        DB::table('reports')->where('status', 'cancelled')->update(['status' => 'not_delivered']);
        
        // Finalmente convertimos a enum con los nuevos valores
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['in_process', 'delivered', 'not_delivered'])->default('in_process')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primero cambiar a string
        Schema::table('reports', function (Blueprint $table) {
            $table->string('status')->default('completed')->change();
        });
        
        // Revertir los datos
        DB::table('reports')->where('status', 'delivered')->update(['status' => 'completed']);
        DB::table('reports')->where('status', 'in_process')->update(['status' => 'pending']);
        DB::table('reports')->where('status', 'not_delivered')->update(['status' => 'cancelled']);
        
        // Volver al enum original
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed')->change();
        });
    }
};
