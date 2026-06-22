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
        Schema::create('candidate_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->integer('communication')->default(0); // 1 to 5 stars
            $table->integer('subject_knowledge')->default(0);
            $table->integer('demo_performance')->default(0);
            $table->integer('english_fluency')->default(0);
            $table->integer('discipline')->default(0);
            $table->decimal('overall_rating', 3, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->foreignId('rated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_ratings');
    }
};
