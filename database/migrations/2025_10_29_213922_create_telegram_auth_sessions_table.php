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
        Schema::create('telegram_auth_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id')->unique();
            $table->string('step'); // waiting_username, waiting_password
            $table->string('username')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_auth_sessions');
    }
};
