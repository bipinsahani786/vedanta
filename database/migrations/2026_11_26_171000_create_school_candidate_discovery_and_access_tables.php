<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_profiles', 'school_discovery_visible')) {
                $table->boolean('school_discovery_visible')->default(false)->after('is_verified')->index();
            }
            if (! Schema::hasColumn('candidate_profiles', 'is_best_profile')) {
                $table->boolean('is_best_profile')->default(false)->after('school_discovery_visible')->index();
            }
            if (! Schema::hasColumn('candidate_profiles', 'school_display_mode')) {
                $table->string('school_display_mode', 20)->default('vpa_id')->after('is_best_profile');
            }
        });

        if (! Schema::hasTable('candidate_access_requests')) {
            Schema::create('candidate_access_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('candidate_user_id')->constrained('users')->cascadeOnDelete();
                $table->string('status', 30)->default('pending')->index();

                // Requested scopes from school
                $table->boolean('requested_photo')->default(true);
                $table->boolean('requested_full_profile')->default(true);
                $table->boolean('requested_cv')->default(true);
                $table->boolean('requested_contact')->default(true);
                $table->text('school_note')->nullable();

                // Admin-approved field-level permissions
                $table->boolean('can_view_photo')->default(false);
                $table->boolean('can_view_full_profile')->default(false);
                $table->boolean('can_view_cv')->default(false);
                $table->boolean('can_view_contact')->default(false);

                // Admin sharing method & review audit
                $table->string('delivery_method', 20)->default('dashboard'); // dashboard | email | both
                $table->timestamp('email_sent_at')->nullable();
                $table->text('admin_notes')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->unique(['school_user_id', 'candidate_user_id'], 'school_candidate_unique');
            });
        }

        if (! Schema::hasTable('candidate_access_logs')) {
            Schema::create('candidate_access_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('access_request_id')->constrained('candidate_access_requests')->cascadeOnDelete();
                $table->foreignId('school_user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('candidate_user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action', 50)->index(); // requested, re_requested, approved, partially_approved, declined, email_shared, updated
                $table->string('status', 30);
                $table->json('granted_permissions')->nullable();
                $table->string('delivery_method', 20)->nullable();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index(['school_user_id', 'candidate_user_id'], 'cal_school_cand_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_access_logs');
        Schema::dropIfExists('candidate_access_requests');

        Schema::table('candidate_profiles', function (Blueprint $table) {
            $dropCols = [];
            foreach (['school_discovery_visible', 'is_best_profile', 'school_display_mode'] as $col) {
                if (Schema::hasColumn('candidate_profiles', $col)) {
                    $dropCols[] = $col;
                }
            }
            if (! empty($dropCols)) {
                $table->dropColumn($dropCols);
            }
        });
    }
};
