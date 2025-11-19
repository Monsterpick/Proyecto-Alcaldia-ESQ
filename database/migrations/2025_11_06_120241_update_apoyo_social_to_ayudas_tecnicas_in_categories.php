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
        // Actualizar el nombre de la categoría "Apoyo Social" a "Ayudas técnicas"
        DB::table('categories')
            ->where('name', 'Apoyo Social')
            ->update([
                'name' => 'Ayudas técnicas',
                'description' => 'Ayudas técnicas, dispositivos y recursos de apoyo social comunitario',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir el cambio
        DB::table('categories')
            ->where('name', 'Ayudas técnicas')
            ->update([
                'name' => 'Apoyo Social',
                'description' => 'Otros tipos de apoyo social, ayudas económicas y servicios comunitarios',
                'updated_at' => now(),
            ]);
    }
};
