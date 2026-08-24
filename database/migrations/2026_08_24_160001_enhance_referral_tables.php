<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enhance referrals table
        Schema::table('referrals', function (Blueprint $table) {
            if (!Schema::hasColumn('referrals', 'source')) {
                $table->string('source')->default('other')->after('referral_code_used');
            }
            if (!Schema::hasColumn('referrals', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('completed_at');
            }
            if (!Schema::hasColumn('referrals', 'interview_at')) {
                $table->timestamp('interview_at')->nullable()->after('verified_at');
            }
            if (!Schema::hasColumn('referrals', 'selected_at')) {
                $table->timestamp('selected_at')->nullable()->after('interview_at');
            }
            if (!Schema::hasColumn('referrals', 'joined_at')) {
                $table->timestamp('joined_at')->nullable()->after('selected_at');
            }
        });

        // Modify stage column to string to allow all 5 stages cleanly
        try {
            DB::statement("ALTER TABLE `referrals` MODIFY COLUMN `stage` VARCHAR(50) NOT NULL DEFAULT 'registered'");
            DB::statement("ALTER TABLE `referral_wallet_transactions` MODIFY COLUMN `source` VARCHAR(100) NOT NULL DEFAULT 'referral_registration'");
            DB::statement("ALTER TABLE `referral_wallet_transactions` MODIFY COLUMN `type` VARCHAR(50) NOT NULL DEFAULT 'credit'");
        } catch (\Exception $e) {
            // fallback if already string or unsupported
        }

        // 2. Create referral_milestones table
        if (!Schema::hasTable('referral_milestones')) {
            Schema::create('referral_milestones', function (Blueprint $table) {
                $table->id();
                $table->integer('successful_referrals_required');
                $table->decimal('bonus_points', 10, 2);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed default milestones (5 -> 500, 10 -> 1500, 25 -> 5000)
            DB::table('referral_milestones')->insert([
                [
                    'successful_referrals_required' => 5,
                    'bonus_points' => 500,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'successful_referrals_required' => 10,
                    'bonus_points' => 1500,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'successful_referrals_required' => 25,
                    'bonus_points' => 5000,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 3. Create referral_milestone_claims table
        if (!Schema::hasTable('referral_milestone_claims')) {
            Schema::create('referral_milestone_claims', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('milestone_id')->constrained('referral_milestones')->cascadeOnDelete();
                $table->decimal('bonus_points', 10, 2);
                $table->timestamp('claimed_at');
                $table->unique(['user_id', 'milestone_id']);
            });
        }

        // 4. Create referral_audit_logs table
        if (!Schema::hasTable('referral_audit_logs')) {
            Schema::create('referral_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('candidate_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->string('action');
                $table->text('reason')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 5. Update referral_settings with new specification defaults
        $settings = [
            'is_referral_active' => '1',
            'points_on_registration' => '50',
            'points_on_profile_complete' => '100',
            'points_on_verification' => '100',
            'points_on_interview' => '150',
            'points_on_placement' => '500',
            'referee_bonus_points' => '100', // 100 Welcome Points
            'point_rate_inr' => '0.50',     // 100 Points = ₹50
            'max_discount_percentage' => '30', // Max 30% discount cap
            'cookie_duration_days' => '30',
            'max_daily_invites' => '20',
        ];

        foreach ($settings as $key => $val) {
            DB::table('referral_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $val, 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_milestone_claims');
        Schema::dropIfExists('referral_milestones');
        Schema::dropIfExists('referral_audit_logs');
    }
};
