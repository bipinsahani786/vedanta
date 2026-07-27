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
            if (!Schema::hasColumn('service_charge_invoices', 'description')) {
                $table->string('description')->nullable()->after('status');
            }
        });

        // Make job_application_id nullable if it is not
        try {
            Schema::table('service_charge_invoices', function (Blueprint $table) {
                $table->unsignedBigInteger('job_application_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // Ignore if doctrine/dbal is not installed or already nullable
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_charge_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('service_charge_invoices', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
