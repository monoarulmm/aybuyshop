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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('password')->nullable(); // Admin Approve করলে জেনারেট হবে

            // KYC & Payment Information
            $table->string('transaction_id')->nullable()->unique(); // nullable করুন
            $table->enum('payment_type', ['bkash', 'nagad', 'rocket'])->nullable(); // nullable করুন
            $table->string('nid_number')->nullable();
            $table->text('address')->nullable();
            $table->string('nid_image')->nullable();
            $table->string('profile_image')->nullable();

            // Roles & Status
            $table->enum('role', ['user', 'admin', 'super_admin', 'n_user'])->default('user');
            $table->string('type')->nullable(); // প্যাকেজের নাম: basic, premium, premium_pro
            $table->decimal('paid_amount', 10, 2)->default(0.00); // ইউজার এ পর্যন্ত কত টাকা মোট পে করেছে
            $table->timestamp('package_updated_at')->nullable(); // কবে প্যাকেজ আপডেট করেছে (ভবিষ্যতে ভ্যালিডিটি চেক করতে লাগবে)
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
