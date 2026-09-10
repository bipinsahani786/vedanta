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
        Schema::create('referral_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('referral_wallets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referral_id')->nullable()->constrained('referrals')->nullOnDelete();
            $table->foreignId('service_charge_invoice_id')->nullable()->constrained('service_charge_invoices')->nullOnDelete();
            $table->enum('type', ['credit', 'debit', 'pending_credit', 'cancelled'])->default('credit');
            $table->decimal('points', 10, 2);
            $table->decimal('amount_equivalent', 10, 2)->default(0);
            $table->enum('source', [
                'referral_registration',
                'referral_profile',
                'referral_interview',
                'referral_placement',
                'service_charge_redemption',
                'admin_adjustment',
                'welcome_bonus'
            ])->default('referral_registration');
            $table->string('description');
            $table->enum('status', ['completed', 'pending', 'failed', 'cancelled'])->default('completed');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_wallet_transactions');
    }
};
