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
        Schema::table('company_infos', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->text('maps_iframe')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->text('about_history')->nullable();
            $table->text('about_mission')->nullable();
            $table->text('about_vision')->nullable();
            $table->text('about_values')->nullable();
            $table->string('brochure_path')->nullable();
            $table->string('contact_email_receiver')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'logo_path', 'phone', 'whatsapp_phone', 'email', 'address', 'maps_iframe',
                'facebook_url', 'instagram_url', 'tiktok_url', 'youtube_url',
                'about_history', 'about_mission', 'about_vision', 'about_values',
                'brochure_path', 'contact_email_receiver'
            ]);
        });
    }
};
