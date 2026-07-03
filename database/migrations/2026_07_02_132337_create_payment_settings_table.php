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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('bank_transfer_enabled')->default(false);
            $table->text('bank_transfer_details')->nullable();
            $table->boolean('cod_enabled')->default(false);
            $table->boolean('gateway_enabled')->default(false);
            $table->enum('gateway_provider', ['culqi', 'niubiz', 'mercadopago'])->default('mercadopago');
            $table->string('gateway_public_key')->nullable();
            $table->string('gateway_private_key')->nullable();
            $table->boolean('gateway_sandbox_mode')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};

