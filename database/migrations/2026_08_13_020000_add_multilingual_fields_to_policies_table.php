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
        Schema::table('policies', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_en');

            $table->text('content_en')->nullable()->after('content');
            $table->text('content_de')->nullable()->after('content_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_de', 'content_en', 'content_de']);
        });
    }
};
