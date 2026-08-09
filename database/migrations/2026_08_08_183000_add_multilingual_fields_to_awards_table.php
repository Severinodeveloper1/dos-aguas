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
        Schema::table('awards', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_de')->nullable()->after('description_en');
            $table->string('country_en')->nullable()->after('country');
            $table->string('country_de')->nullable()->after('country_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('awards', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_de', 'description_en', 'description_de', 'country_en', 'country_de']);
        });
    }
};
