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
        if (Schema::hasColumn('beneficiaries', 'birth_date')) {
            return; // La columna ya existe, no hacer nada
        }

        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('cedula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn('birth_date');
        });
    }
};
