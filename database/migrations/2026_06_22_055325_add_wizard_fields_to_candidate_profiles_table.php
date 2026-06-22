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
            $table->string('passport_photo_path')->nullable();
            $table->string('current_school')->nullable();
            $table->enum('english_fluency', ['beginner', 'intermediate', 'fluent'])->nullable();
            $table->enum('residential_preference', ['residential', 'day', 'both'])->nullable();
            $table->string('availability_to_join')->nullable();
            
            // Signature fields
            $table->enum('signature_type', ['draw', 'upload', 'type'])->nullable();
            $table->text('signature_data')->nullable();
            $table->timestamp('signature_date_time')->nullable();
            $table->text('signature_device_info')->nullable();
            $table->string('signature_ip_address')->nullable();
            
            // Plan selection
            $table->enum('plan_type', ['standard', 'premium'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'passport_photo_path',
                'current_school',
                'english_fluency',
                'residential_preference',
                'availability_to_join',
                'signature_type',
                'signature_data',
                'signature_date_time',
                'signature_device_info',
                'signature_ip_address',
                'plan_type'
            ]);
        });
    }
};
