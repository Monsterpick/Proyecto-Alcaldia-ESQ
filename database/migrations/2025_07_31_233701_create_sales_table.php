<?php

use App\Models\Customer;
use App\Models\Quote;
use App\Models\Warehouse;
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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_type');
            $table->string('serie');
            $table->integer('correlative');
            $table->timestamp('date')->useCurrent();
            $table->foreignIdFor(Quote::class)->nullable();
            $table->foreignIdFor(Customer::class);
            $table->foreignIdFor(Warehouse::class);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
