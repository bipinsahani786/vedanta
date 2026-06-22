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
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->boolean('initial_fee_paid')->default(false)->after('is_fee_paid');
            $table->decimal('paid_amount', 8, 2)->default(0)->after('initial_fee_paid');
            $table->decimal('pending_amount', 8, 2)->default(0)->after('paid_amount');
            $table->boolean('is_verified')->default(false)->after('pending_amount');
            $table->integer('total_allowed_applications')->default(3)->after('is_verified');
            $table->integer('used_applications')->default(0)->after('total_allowed_applications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'initial_fee_paid',
                'paid_amount',
                'pending_amount',
                'is_verified',
                'total_allowed_applications',
                'used_applications'
            ]);
        });
    }
};
