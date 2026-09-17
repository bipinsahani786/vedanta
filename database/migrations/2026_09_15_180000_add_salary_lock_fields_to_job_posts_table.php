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
        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('salary_mode', 30)->default('range')->after('salary_range');
            $table->decimal('salary_min', 12, 2)->nullable()->after('salary_mode');
            $table->decimal('salary_max', 12, 2)->nullable()->after('salary_min');
            $table->string('salary_rate', 30)->default('per month')->after('salary_max');
            $table->string('school_image')->nullable()->after('school_name');
            $table->string('experience', 100)->nullable()->after('qualification_id');
            $table->string('openings', 50)->nullable()->default('Multiple')->after('experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn([
                'salary_mode',
                'salary_min',
                'salary_max',
                'salary_rate',
                'school_image',
                'experience',
                'openings'
            ]);
        });
    }
};
