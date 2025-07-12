<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Actividad;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Plan;
use App\Models\Estatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('razon_social');
            $table->string('rif');
            $table->string('direccion_fiscal');
            $table->foreignIdFor(Actividad::class)->constrained();
            $table->string('telefono_principal');
            $table->string('telefono_secundario');
            $table->string('email_principal');
            $table->string('email_secundario');
            $table->string('domain')->nullable();
            $table->foreignIdFor(Estado::class)->constrained();
            $table->foreignIdFor(Municipio::class)->constrained();
            $table->foreignIdFor(Parroquia::class)->constrained();
            $table->string('responsable');
            $table->string('cargo_responsable');
            $table->string('telefono_responsable');
            $table->string('email_responsable');
            $table->foreignIdFor(Plan::class)->constrained();
            $table->foreignIdFor(Estatus::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
