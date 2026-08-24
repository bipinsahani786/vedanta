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
        Schema::table('service_charge_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('service_charge_invoices', 'points_redeemed')) {
                $table->decimal('points_redeemed', 10, 2)->default(0)->after('late_fee');
            }
            if (!Schema::hasColumn('service_charge_invoices', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('points_redeemed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_charge_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('service_charge_invoices', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
            if (Schema::hasColumn('service_charge_invoices', 'points_redeemed')) {
                $table->dropColumn('points_redeemed');
            }
        });
    }
};
