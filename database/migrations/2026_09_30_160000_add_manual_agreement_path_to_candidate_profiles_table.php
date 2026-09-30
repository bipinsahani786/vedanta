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
        if (Schema::hasTable('candidate_profiles') && ! Schema::hasColumn('candidate_profiles', 'manual_agreement_path')) {
            Schema::table('candidate_profiles', function (Blueprint $table) {
                $table->string('manual_agreement_path')->nullable()->after('agreement_pdf_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('candidate_profiles') && Schema::hasColumn('candidate_profiles', 'manual_agreement_path')) {
            Schema::table('candidate_profiles', function (Blueprint $table) {
                $table->dropColumn('manual_agreement_path');
            });
        }
    }
};
