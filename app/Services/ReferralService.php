<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralWallet;
use App\Models\ReferralWalletTransaction;
use App\Models\ReferralEmailInvite;
use App\Models\ReferralSetting;
use App\Models\ReferralRedemption;
use App\Models\ReferralMilestone;
use App\Models\ReferralAuditLog;
use App\Models\ServiceChargeInvoice;
use App\Mail\ReferralInviteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReferralService
{
    /**
     * Get or create candidate's referral wallet.
     */
    public static function getWallet(User $user): ReferralWallet
    {
        return ReferralWallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'available_points' => 0,
                'pending_points' => 0,
                'lifetime_points' => 0,
                'is_locked' => false,
            ]
        );
    }

    /**
     * Get conversion rate: 1 Point = X INR.
     * Default: 100 Points = ₹50 => 1 Point = ₹0.50.
     */
    public static function getPointRate(): float
    {
        return (float) ReferralSetting::get('point_rate_inr', 0.50);
    }

    /**
     * Check if referral system is globally active.
     */
    public static function isSystemActive(): bool
    {
        return (bool) ReferralSetting::get('is_referral_active', true);
    }

    /**
     * Process referral registration when a new candidate signs up.
     */
    public static function recordReferral(User $referee, ?string $referralCode, string $source = 'other'): ?Referral
    {
        if (!self::isSystemActive() || empty($referralCode)) {
            return null;
        }

        $referralCode = strtoupper(trim($referralCode));
        $referrer = User::where('referral_code', $referralCode)->first();

        // Fraud prevention checks: Self-referral & duplicate checks
        if (!$referrer || $referrer->id === $referee->id) {
            return null;
        }

        if ($referrer->email === $referee->email || ($referrer->phone && $referrer->phone === $referee->phone)) {
            return null;
        }

        // Prevent duplicate referee (one candidate = one referrer)
        if (Referral::where('referee_id', $referee->id)->exists()) {
            return null;
        }

        return DB::transaction(function () use ($referrer, $referee, $referralCode, $source) {
            $referee->referred_by_id = $referrer->id;
            $referee->saveQuietly();

            $referral = Referral::create([
                'referrer_id' => $referrer->id,
                'referee_id' => $referee->id,
                'referral_code_used' => $referralCode,
                'source' => $source,
                'stage' => 'registered',
                'points_earned' => 0,
                'points_pending' => 0,
                'status' => 'active',
            ]);

            // Mark any matching email invite as registered
            ReferralEmailInvite::where('referrer_id', $referrer->id)
                ->where('friend_email', $referee->email)
                ->update([
                    'status' => 'registered',
                    'registered_at' => now(),
                ]);

            // Stage 1 Reward: Points on Registration (+50 pts)
            $regPoints = (float) ReferralSetting::get('points_on_registration', 50);
            if ($regPoints > 0) {
                self::creditReward(
                    $referrer,
                    $regPoints,
                    'referral_registration',
                    "Referral bonus: Friend {$referee->name} registered",
                    $referral->id
                );
                $referral->increment('points_earned', $regPoints);
            }

            // Referee Welcome Bonus (+100 Welcome Points)
            $welcomePoints = (float) ReferralSetting::get('referee_bonus_points', 100);
            if ($welcomePoints > 0) {
                self::creditReward(
                    $referee,
                    $welcomePoints,
                    'welcome_bonus',
                    "🎉 Welcome to Vedanta! Received {$welcomePoints} Welcome Points for joining via referral."
                );
            }

            return $referral;
        });
    }

    /**
     * Advance referral funnel stage (6-Stage progression).
     * Stages: registered -> profile_completed -> verified -> interview_scheduled -> selected -> joined
     */
    public static function advanceStage(User $referee, string $newStage): ?Referral
    {
        // Backward compat: map 'placed' to 'joined'
        if ($newStage === 'placed') {
            $newStage = 'joined';
        }

        $referral = Referral::where('referee_id', $referee->id)->first();
        if (!$referral || $referral->status !== 'active') {
            return null;
        }

        $stageHierarchy = [
            'registered' => 1,
            'profile_completed' => 2,
            'verified' => 3,
            'interview_scheduled' => 4,
            'selected' => 5,
            'joined' => 6,
        ];

        $currentOrder = $stageHierarchy[$referral->stage] ?? 1;
        $newOrder = $stageHierarchy[$newStage] ?? 1;

        if ($newOrder <= $currentOrder) {
            return $referral; // Already at or past this milestone
        }

        return DB::transaction(function () use ($referral, $referee, $newStage, $newOrder, $stageHierarchy) {
            $referrer = $referral->referrer;

            // Stage 2: Profile Complete (+100 pts)
            if ($newOrder >= 2 && ($stageHierarchy[$referral->stage] ?? 0) < 2) {
                if (!self::hasRewardBeenIssued($referral->id, 'referral_profile')) {
                    $points = (float) ReferralSetting::get('points_on_profile_complete', 100);
                    if ($points > 0 && $referrer) {
                        self::creditReward(
                            $referrer,
                            $points,
                            'referral_profile',
                            "Milestone bonus: {$referee->name} completed profile & onboarding",
                            $referral->id
                        );
                        $referral->increment('points_earned', $points);
                    }
                }
            }

            // Stage 3: Profile Verified (+100 pts)
            if ($newOrder >= 3 && ($stageHierarchy[$referral->stage] ?? 0) < 3) {
                if (!self::hasRewardBeenIssued($referral->id, 'referral_verification')) {
                    $points = (float) ReferralSetting::get('points_on_verification', 100);
                    if ($points > 0 && $referrer) {
                        self::creditReward(
                            $referrer,
                            $points,
                            'referral_verification',
                            "Milestone bonus: {$referee->name}'s profile was verified",
                            $referral->id
                        );
                        $referral->increment('points_earned', $points);
                    }
                }
                $referral->verified_at = now();
            }

            // Stage 4: Interview Scheduled (+150 pts)
            if ($newOrder >= 4 && ($stageHierarchy[$referral->stage] ?? 0) < 4) {
                if (!self::hasRewardBeenIssued($referral->id, 'referral_interview')) {
                    $points = (float) ReferralSetting::get('points_on_interview', 150);
                    if ($points > 0 && $referrer) {
                        self::creditReward(
                            $referrer,
                            $points,
                            'referral_interview',
                            "Milestone bonus: {$referee->name} attended interview",
                            $referral->id
                        );
                        $referral->increment('points_earned', $points);
                    }
                }
                $referral->interview_at = now();
            }

            // Stage 5: Selected (+0 pts by default — configurable)
            if ($newOrder >= 5 && ($stageHierarchy[$referral->stage] ?? 0) < 5) {
                if (!self::hasRewardBeenIssued($referral->id, 'referral_selection')) {
                    $points = (float) ReferralSetting::get('points_on_selection', 0);
                    if ($points > 0 && $referrer) {
                        self::creditReward(
                            $referrer,
                            $points,
                            'referral_selection',
                            "Milestone bonus: {$referee->name} was selected for placement",
                            $referral->id
                        );
                        $referral->increment('points_earned', $points);
                    }
                }
                $referral->selected_at = now();
            }

            // Stage 6: Joined & Confirmed (+500 pts)
            if ($newOrder >= 6 && ($stageHierarchy[$referral->stage] ?? 0) < 6) {
                if (!self::hasRewardBeenIssued($referral->id, 'referral_placement')) {
                    $points = (float) ReferralSetting::get('points_on_placement', 500);
                    if ($points > 0 && $referrer) {
                        self::creditReward(
                            $referrer,
                            $points,
                            'referral_placement',
                            "🎉 Big Reward: {$referee->name} got successfully joined with Vedanta!",
                            $referral->id
                        );
                        $referral->increment('points_earned', $points);
                    }
                }
                $referral->status = 'completed';
                $referral->completed_at = now();
                $referral->joined_at = now();

                // Mark email invite as converted
                ReferralEmailInvite::where('referrer_id', $referral->referrer_id)
                    ->where('friend_email', $referee->email)
                    ->update(['status' => 'converted']);
            }

            $referral->stage = $newStage;
            $referral->save();

            // Check and award Milestone Bonuses after stage is joined
            if ($newOrder >= 6 && $referrer) {
                self::checkAndAwardMilestoneBonuses($referrer);
            }

            return $referral;
        });
    }

    /**
     * Check if a specific reward type has already been issued for a referral (Idempotency Guard).
     */
    public static function hasRewardBeenIssued(int $referralId, string $source): bool
    {
        return ReferralWalletTransaction::where('referral_id', $referralId)
            ->where('source', $source)
            ->exists();
    }

    /**
     * Check and award Referral Milestone Bonuses (e.g. 5, 10, 25 successful referrals).
     */
    public static function checkAndAwardMilestoneBonuses(User $referrer): void
    {
        $successfulCount = Referral::where('referrer_id', $referrer->id)
            ->where('stage', 'placed')
            ->count();

        $milestones = ReferralMilestone::where('is_active', true)
            ->where('successful_referrals_required', '<=', $successfulCount)
            ->orderBy('successful_referrals_required')
            ->get();

        foreach ($milestones as $milestone) {
            $alreadyClaimed = DB::table('referral_milestone_claims')
                ->where('user_id', $referrer->id)
                ->where('milestone_id', $milestone->id)
                ->exists();

            if (!$alreadyClaimed) {
                DB::table('referral_milestone_claims')->insert([
                    'user_id' => $referrer->id,
                    'milestone_id' => $milestone->id,
                    'bonus_points' => $milestone->bonus_points,
                    'claimed_at' => now(),
                ]);

                self::creditReward(
                    $referrer,
                    (float) $milestone->bonus_points,
                    'milestone_bonus',
                    "🏆 Milestone Reached: Awarded {$milestone->bonus_points} Bonus Points for {$milestone->successful_referrals_required} successful referrals!"
                );
            }
        }
    }

    /**
     * Helper to verify candidate profile and trigger Stage 3.
     */
    public static function verifyCandidate(User $candidate): void
    {
        self::advanceStage($candidate, 'verified');
    }

    /**
     * Credit reward points to user wallet.
     */
    public static function creditReward(
        User $user,
        float $points,
        string $source,
        string $description,
        ?int $referralId = null,
        ?int $adminId = null
    ): ReferralWalletTransaction {
        $wallet = self::getWallet($user);
        $rate = self::getPointRate();
        $inrValue = round($points * $rate, 2);

        $wallet->increment('available_points', $points);
        $wallet->increment('lifetime_points', $points);

        return ReferralWalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'referral_id' => $referralId,
            'type' => 'credit',
            'points' => $points,
            'amount_equivalent' => $inrValue,
            'source' => $source,
            'description' => $description,
            'status' => 'completed',
            'admin_id' => $adminId,
        ]);
    }

    /**
     * Redeem available wallet points against a service charge invoice.
     * Enforces the 30% discount cap (Admin configurable).
     */
    public static function redeemForServiceCharge(User $user, ServiceChargeInvoice $invoice, float $pointsToRedeem): array
    {
        $wallet = self::getWallet($user);

        if ($wallet->is_locked) {
            return [
                'success' => false,
                'message' => 'Your referral wallet is currently locked: ' . ($wallet->lock_reason ?: 'Contact support.'),
            ];
        }

        if ($pointsToRedeem <= 0 || $pointsToRedeem > $wallet->available_points) {
            return [
                'success' => false,
                'message' => 'Insufficient points balance in your wallet.',
            ];
        }

        $grossAmount = (float) ($invoice->amount + ($invoice->late_fee ?? 0));
        if ($grossAmount <= 0) {
            return [
                'success' => false,
                'message' => 'Invoice has no payable amount.',
            ];
        }

        $pointRate = self::getPointRate(); // 0.50 INR per point
        $maxDiscountPercentage = (float) ReferralSetting::get('max_discount_percentage', 30); // 30%
        $maxDiscountAllowed = round($grossAmount * ($maxDiscountPercentage / 100), 2);

        $requestedDiscount = round($pointsToRedeem * $pointRate, 2);
        $actualDiscount = min($requestedDiscount, $maxDiscountAllowed);
        $actualPointsDeducted = round($actualDiscount / $pointRate, 2);

        if ($actualDiscount <= 0) {
            return [
                'success' => false,
                'message' => 'Maximum discount cap already reached for this invoice.',
            ];
        }

        return DB::transaction(function () use ($user, $wallet, $invoice, $actualPointsDeducted, $actualDiscount, $grossAmount) {
            $wallet->decrement('available_points', $actualPointsDeducted);

            // Record Ledger Debit
            $txn = ReferralWalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'debit',
                'points' => $actualPointsDeducted,
                'amount_equivalent' => $actualDiscount,
                'source' => 'service_charge_redemption',
                'description' => "Redeemed {$actualPointsDeducted} points for ₹{$actualDiscount} discount on Invoice #{$invoice->id}",
                'status' => 'completed',
            ]);

            // Create Redemption Audit Record
            ReferralRedemption::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'service_charge_invoice_id' => $invoice->id,
                'points_redeemed' => $actualPointsDeducted,
                'discount_amount' => $actualDiscount,
                'status' => 'applied',
                'notes' => "Wallet discount applied on Service Charge Invoice #{$invoice->id}",
            ]);

            // Update Invoice Record
            $newDiscount = (float) ($invoice->discount_amount ?? 0) + $actualDiscount;
            $newPointsRedeemed = (float) ($invoice->points_redeemed ?? 0) + $actualPointsDeducted;
            $invoice->update([
                'discount_amount' => $newDiscount,
                'points_redeemed' => $newPointsRedeemed,
            ]);

            return [
                'success' => true,
                'message' => "Successfully applied ₹" . number_format($actualDiscount, 2) . " discount using " . number_format($actualPointsDeducted) . " points!",
                'discount_amount' => $actualDiscount,
                'net_amount' => max(0, $grossAmount - $newDiscount),
            ];
        });
    }

    /**
     * Admin manual wallet adjustments (Credit or Debit) with mandatory audit logging.
     */
    public static function adminAdjust(
        ReferralWallet $wallet,
        float $points,
        string $type,
        string $reason,
        User $admin
    ): ReferralWalletTransaction {
        $rate = self::getPointRate();
        $inrValue = round($points * $rate, 2);

        return DB::transaction(function () use ($wallet, $points, $type, $reason, $admin, $inrValue) {
            if ($type === 'credit') {
                $wallet->increment('available_points', $points);
                $wallet->increment('lifetime_points', $points);
            } else {
                $wallet->decrement('available_points', min($wallet->available_points, $points));
            }

            self::logAudit(
                $admin->id,
                $wallet->user_id,
                "wallet_adjustment_{$type}",
                $reason,
                ['points' => $points, 'inr_equivalent' => $inrValue]
            );

            return ReferralWalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'type' => $type,
                'points' => $points,
                'amount_equivalent' => $inrValue,
                'source' => 'admin_adjustment',
                'description' => "Admin {$type}: {$reason}",
                'status' => 'completed',
                'admin_id' => $admin->id,
            ]);
        });
    }

    /**
     * Log Referral Audit Actions
     */
    public static function logAudit(
        ?int $adminId,
        ?int $candidateId,
        string $action,
        ?string $reason = null,
        ?array $metadata = null
    ): ReferralAuditLog {
        return ReferralAuditLog::create([
            'admin_id' => $adminId,
            'candidate_id' => $candidateId,
            'action' => $action,
            'reason' => $reason,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * Send email invitation to friend(s) with daily spam rate limiting.
     */
    public static function sendEmailInvite(User $user, string $friendName, string $friendEmail): array
    {
        $maxDaily = (int) ReferralSetting::get('max_daily_invites', 20);
        $todaySentCount = ReferralEmailInvite::where('referrer_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        if ($todaySentCount >= $maxDaily) {
            return [
                'success' => false,
                'message' => "Daily invite limit of {$maxDaily} emails reached. Please try again tomorrow.",
            ];
        }

        // Generate secure invitation token
        $token = Str::random(32);

        $invite = ReferralEmailInvite::create([
            'referrer_id' => $user->id,
            'friend_name' => $friendName,
            'friend_email' => $friendEmail,
            'token' => $token,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        try {
            Mail::to($friendEmail)->queue(new ReferralInviteMail($user, $invite));
        } catch (\Exception $e) {
            Log::error("Failed to queue referral invite email: " . $e->getMessage());
        }

        return [
            'success' => true,
            'message' => "Invitation successfully sent to {$friendEmail}!",
            'invite' => $invite,
        ];
    }
}
