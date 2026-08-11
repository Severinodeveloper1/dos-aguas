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
        // 1. Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_de')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_de')->nullable()->after('description_en');
            $table->string('meta_title_en')->nullable()->after('meta_title');
            $table->string('meta_title_de')->nullable()->after('meta_title_en');
            $table->text('meta_description_en')->nullable()->after('meta_description');
            $table->text('meta_description_de')->nullable()->after('meta_description_en');
        });

        // 2. Banners
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_en');
            $table->string('subtitle_en')->nullable()->after('subtitle');
            $table->string('subtitle_de')->nullable()->after('subtitle_en');
            $table->string('button_text_en')->nullable()->after('button_text');
            $table->string('button_text_de')->nullable()->after('button_text_en');
        });

        // 3. Product Variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_de')->nullable()->after('name_en');
        });

        // 4. Products
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_de')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_de')->nullable()->after('description_en');
            $table->text('tasting_notes_en')->nullable()->after('tasting_notes');
            $table->text('tasting_notes_de')->nullable()->after('tasting_notes_en');
            $table->text('natural_benefits_en')->nullable()->after('natural_benefits');
            $table->text('natural_benefits_de')->nullable()->after('natural_benefits_en');
            $table->json('nutritional_values_en')->nullable()->after('nutritional_values');
            $table->json('nutritional_values_de')->nullable()->after('nutritional_values_en');
            $table->string('meta_title_en')->nullable()->after('meta_title');
            $table->string('meta_title_de')->nullable()->after('meta_title_en');
            $table->text('meta_description_en')->nullable()->after('meta_description');
            $table->text('meta_description_de')->nullable()->after('meta_description_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'name_en', 'name_de',
                'description_en', 'description_de',
                'meta_title_en', 'meta_title_de',
                'meta_description_en', 'meta_description_de'
            ]);
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'title_en', 'title_de',
                'subtitle_en', 'subtitle_de',
                'button_text_en', 'button_text_de'
            ]);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_de']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name_en', 'name_de',
                'description_en', 'description_de',
                'tasting_notes_en', 'tasting_notes_de',
                'natural_benefits_en', 'natural_benefits_de',
                'nutritional_values_en', 'nutritional_values_de',
                'meta_title_en', 'meta_title_de',
                'meta_description_en', 'meta_description_de'
            ]);
        });
    }
};
