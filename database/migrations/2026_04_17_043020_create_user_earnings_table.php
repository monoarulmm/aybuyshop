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
        Schema::create('user_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2); // কত টাকা যোগ হলো
            $table->enum('type', ['video_view', 'referral', 'bonus']); // আয়ের উৎস
            $table->date('date'); // প্রতিদিনের হিসাব রাখার জন্য
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_earnings');
    }
};
