<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReferralSetting;

class ReferralSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'is_referral_active' => ['1', 'Enable or disable the entire Refer & Earn system'],
            'points_on_registration' => ['50', 'Points awarded to referrer when friend registers'],
            'points_on_profile_complete' => ['100', 'Points awarded when referred friend completes full profile wizard'],
            'points_on_interview' => ['150', 'Points awarded when referred friend gets first interview scheduled'],
            'points_on_placement' => ['500', 'Points awarded when referred friend gets placed & converts'],
            'referee_bonus_points' => ['50', 'Welcome bonus points awarded to the new candidate'],
            'point_rate_inr' => ['1.0', 'Value of 1 Reward Point in INR (e.g. 1.0 = ₹1)'],
            'max_discount_percentage' => ['50', 'Maximum percentage discount allowable on service charge invoices'],
            'max_daily_invites' => ['20', 'Maximum email invites a candidate can send per 24 hours'],
        ];

        foreach ($defaults as $key => [$value, $description]) {
            ReferralSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'description' => $description]
            );
        }
    }
}
