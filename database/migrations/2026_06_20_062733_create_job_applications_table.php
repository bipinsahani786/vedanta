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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained('job_posts')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['applied', 'shortlisted', 'rejected', 'hired'])->default('applied');
            $table->integer('match_score')->default(0); // e.g., 85 for 85%
            $table->text('cover_letter')->nullable();
            $table->timestamps();
            
            // A candidate should only apply to a job once
            $table->unique(['job_post_id', 'candidate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
