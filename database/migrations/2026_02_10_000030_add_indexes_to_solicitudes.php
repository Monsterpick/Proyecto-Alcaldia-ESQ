<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $indexExists = false;

        if ($driver === 'mysql') {
            $existing = collect(DB::select('SHOW INDEX FROM solicitudes'))->pluck('Key_name')->unique()->values();
            $indexExists = $existing->contains('solicitudes_departamento_created_index');
        } elseif ($driver === 'sqlite') {
            $indexes = collect(DB::select("PRAGMA index_list('solicitudes')"))->pluck('name');
            $indexExists = $indexes->contains('solicitudes_departamento_created_index');
        }

        if (!$indexExists) {
            Schema::table('solicitudes', function (Blueprint $table) {
                $table->index(['departamento_id', 'created_at'], 'solicitudes_departamento_created_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropIndex('solicitudes_departamento_created_index');
        });
    }
};
