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
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->text('address')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('highest_qualification_id')->nullable()->constrained('qualifications')->nullOnDelete();
            $table->foreignId('preferred_state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('preferred_city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->integer('experience_years')->default(0);
            $table->string('current_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('resume_path')->nullable();
            
            // Registration Steps Status
            $table->boolean('is_profile_complete')->default(false);
            $table->boolean('is_agreement_signed')->default(false);
            $table->string('agreement_pdf_path')->nullable();
            $table->boolean('is_fee_paid')->default(false);
            $table->string('payment_id')->nullable();
            $table->timestamp('registration_completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
