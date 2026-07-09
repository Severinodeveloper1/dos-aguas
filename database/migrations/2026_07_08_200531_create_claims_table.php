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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_code')->unique();
            $table->string('document_type'); // DNI, CE, Pasaporte, RUC
            $table->string('document_number');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->boolean('is_minor')->default(false);
            $table->string('representative_name')->nullable();
            $table->string('representative_document_type')->nullable();
            $table->string('representative_document_number')->nullable();
            $table->string('type'); // reclamacion or queja
            $table->decimal('claimed_amount', 10, 2)->nullable();
            $table->text('product_service_description');
            $table->text('claim_details');
            $table->text('consumer_request');
            $table->string('status')->default('pending'); // pending, in_process, resolved
            $table->text('resolution_response')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
