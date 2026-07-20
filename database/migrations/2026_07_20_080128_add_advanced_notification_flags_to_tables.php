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
            $table->boolean('abandoned_reminder_sent')->default(false)->after('is_profile_complete');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->boolean('interview_reminder_sent')->default(false)->after('interview_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn(['abandoned_reminder_sent']);
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('interview_reminder_sent');
        });
    }
};
