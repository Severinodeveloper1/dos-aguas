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
        // 1. Timeline Events (Adding German fields)
        Schema::table('timeline_events', function (Blueprint $table) {
            $table->string('title_de')->nullable()->after('title_en');
            $table->text('description_de')->nullable()->after('description_en');
        });

        // 2. Posts (Adding English and German fields)
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_en');
            $table->text('excerpt_en')->nullable()->after('excerpt');
            $table->text('excerpt_de')->nullable()->after('excerpt_en');
            $table->longText('content_en')->nullable()->after('content');
            $table->longText('content_de')->nullable()->after('content_en');
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
        Schema::table('timeline_events', function (Blueprint $table) {
            $table->dropColumn(['title_de', 'description_de']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'title_en', 'title_de',
                'excerpt_en', 'excerpt_de',
                'content_en', 'content_de',
                'meta_title_en', 'meta_title_de',
                'meta_description_en', 'meta_description_de'
            ]);
        });
    }
};
