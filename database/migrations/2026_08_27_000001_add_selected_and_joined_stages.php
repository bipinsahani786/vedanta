<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add 'selected' and 'joined' stages to referral funnel.
     * Backward compatible: existing 'placed' records are migrated to 'joined'.
     */
    public function up(): void
    {
        // Migrate existing 'placed' stage records to 'joined' (the correct final stage)
        DB::table('referrals')
            ->where('stage', 'placed')
            ->update(['stage' => 'joined']);

        // Update max_daily_invites from 20 to 10 (per SRS specification)
        DB::table('referral_settings')
            ->where('key', 'max_daily_invites')
            ->update(['value' => '10', 'updated_at' => now()]);

        // Add points_on_selection setting (0 points — reward only on confirmed joining)
        DB::table('referral_settings')->updateOrInsert(
            ['key' => 'points_on_selection'],
            ['value' => '0', 'updated_at' => now()]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'joined' back to 'placed'
        DB::table('referrals')
            ->where('stage', 'joined')
            ->update(['stage' => 'placed']);

        DB::table('referral_settings')
            ->where('key', 'max_daily_invites')
            ->update(['value' => '20', 'updated_at' => now()]);

        DB::table('referral_settings')
            ->where('key', 'points_on_selection')
            ->delete();
    }
};
