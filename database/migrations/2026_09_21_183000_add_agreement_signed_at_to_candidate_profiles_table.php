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
        if (Schema::hasTable('candidate_profiles') && !Schema::hasColumn('candidate_profiles', 'agreement_signed_at')) {
            Schema::table('candidate_profiles', function (Blueprint $table) {
                $table->timestamp('agreement_signed_at')->nullable()->after('signature_date_time');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('candidate_profiles') && Schema::hasColumn('candidate_profiles', 'agreement_signed_at')) {
            Schema::table('candidate_profiles', function (Blueprint $table) {
                $table->dropColumn('agreement_signed_at');
            });
        }
    }
};
