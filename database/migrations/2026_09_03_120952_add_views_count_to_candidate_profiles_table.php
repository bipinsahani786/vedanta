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
        if (!Schema::hasColumn('candidate_profiles', 'views_count')) {
            Schema::table('candidate_profiles', function (Blueprint $table) {
                $table->unsignedBigInteger('views_count')->default(0)->after('used_applications');
            });
        }

        if (!Schema::hasTable('candidate_profile_views')) {
            Schema::create('candidate_profile_views', function (Blueprint $table) {
                $table->id();
                $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('employer_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();

                $table->index(['candidate_id', 'employer_id']);
                $table->index(['candidate_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('candidate_profiles', 'views_count')) {
            Schema::table('candidate_profiles', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }

        Schema::dropIfExists('candidate_profile_views');
    }
};
