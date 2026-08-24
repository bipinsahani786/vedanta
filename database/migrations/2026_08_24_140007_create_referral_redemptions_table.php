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
        Schema::create('referral_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained('referral_wallets')->cascadeOnDelete();
            $table->foreignId('service_charge_invoice_id')->nullable()->constrained('service_charge_invoices')->nullOnDelete();
            $table->decimal('points_redeemed', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->enum('status', ['applied', 'approved', 'rejected', 'reversed'])->default('applied');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_redemptions');
    }
};
