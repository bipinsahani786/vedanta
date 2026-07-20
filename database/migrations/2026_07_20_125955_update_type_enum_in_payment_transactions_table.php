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
        DB::statement("ALTER TABLE payment_transactions MODIFY COLUMN type ENUM('registration_fee', 'placement_fee', 'service_charge') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE payment_transactions MODIFY COLUMN type ENUM('registration_fee', 'placement_fee') NOT NULL");
    }
};
