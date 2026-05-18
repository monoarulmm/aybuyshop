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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('video_type', ['youtube', 'facebook', 'tiktok', 'local', 'others'])->default('youtube');
            $table->text('video_url')->nullable();
            $table->string('local_video')->nullable();
            $table->decimal('earning_per_view', 8, 2)->default(5.00);
            // nullable করা হয়েছে যাতে এটি বাধ্যতামূলক না হয়
            $table->integer('duration')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
