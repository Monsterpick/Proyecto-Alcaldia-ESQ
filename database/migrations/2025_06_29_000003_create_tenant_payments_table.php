<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;
use App\Models\PaymentType;
use App\Models\PaymentOrigin;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class); // ID del tenant que realiza el pago
            $table->foreignIdFor(PaymentType::class);
            $table->foreignIdFor(PaymentOrigin::class);
            $table->unsignedBigInteger('amount_cents')->default(0); // Monto del pago en centavos
            $table->decimal('amount', 12, 2)->storedAs('amount_cents / 100')->nullable(); // Monto del pago
            $table->string('reference_number')->nullable(); // Número de referencia del pago
            $table->date('payment_date'); // Fecha en que se realizó el pago
            $table->date('period_start'); // Inicio del período que cubre el pago
            $table->date('period_end'); // Fin del período que cubre el pago
            $table->text('notes')->nullable(); // Notas adicionales
            $table->string('status'); // Estado del pago (pendiente, confirmado, rechazado, etc)
            $table->string('currency', 3)->default('VES'); // Moneda del pago
            $table->string('image_path')->nullable(); // Ruta de la imagen del comprobante
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_payments');
    }
}; 