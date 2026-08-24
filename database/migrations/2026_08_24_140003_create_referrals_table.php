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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referee_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('referral_code_used');
            $table->enum('stage', ['registered', 'profile_completed', 'interview_scheduled', 'placed', 'rejected'])->default('registered');
            $table->decimal('points_earned', 10, 2)->default(0);
            $table->decimal('points_pending', 10, 2)->default(0);
            $table->enum('status', ['active', 'completed', 'cancelled', 'flagged'])->default('active');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
