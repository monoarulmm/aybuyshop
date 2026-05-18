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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            // Branding
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('site_name')->nullable();

            // Banners
            $table->string('main_banner')->nullable(); // মেইন স্লাইডার বা ব্যানার
            $table->json('sponsor_banner')->nullable(); // স্পন্সর ব্যানার

            // Contact Info
            $table->string('phone_primary')->nullable();
            $table->string('phone_secondary')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // Social Links
            $table->string('fb_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('whatsapp_link')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
