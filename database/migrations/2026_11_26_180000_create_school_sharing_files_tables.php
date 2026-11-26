<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('school_sharing_files')) {
            Schema::create('school_sharing_files', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('share_token', 64)->unique();
                $table->boolean('is_active')->default(true)->index();

                // File password controls (hashed for auth + encrypted snapshot only for Admin WhatsApp/email share actions)
                $table->string('password_hash')->nullable();
                $table->text('password_encrypted')->nullable();
                $table->boolean('separate_password_share')->default(false);
                $table->timestamp('password_updated_at')->nullable();

                // Field-level permissions (strictly view-only CV, no download)
                $table->boolean('allow_qualifications')->default(true);
                $table->boolean('allow_date_of_birth')->default(true);
                $table->boolean('allow_current_salary')->default(true);
                $table->boolean('allow_expected_salary')->default(true);
                $table->boolean('allow_experience')->default(true);
                $table->boolean('allow_availability')->default(true);
                $table->boolean('allow_cv_view')->default(true);
                $table->boolean('allow_photo')->default(false);
                $table->boolean('allow_contact')->default(false);

                $table->timestamp('last_shared_at')->nullable();
                $table->string('last_shared_email')->nullable();
                $table->unsignedInteger('views_count')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('school_sharing_categories')) {
            Schema::create('school_sharing_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sharing_file_id')->constrained('school_sharing_files')->cascadeOnDelete();
                $table->string('name');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['sharing_file_id', 'name'], 'ssf_cat_unique');
            });
        }

        if (! Schema::hasTable('school_sharing_file_candidates')) {
            Schema::create('school_sharing_file_candidates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sharing_file_id')->constrained('school_sharing_files')->cascadeOnDelete();
                $table->foreignId('category_id')->constrained('school_sharing_categories')->cascadeOnDelete();
                $table->foreignId('candidate_user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('admin_note', 500)->nullable();
                $table->timestamps();

                $table->unique(['category_id', 'candidate_user_id'], 'ssf_cat_cand_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('school_sharing_file_candidates');
        Schema::dropIfExists('school_sharing_categories');
        Schema::dropIfExists('school_sharing_files');
    }
};
