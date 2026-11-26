<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'role', 'password', 'referral_code', 'referred_by_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'password_changed_at' => 'datetime',
            'temp_password_issued_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->getAttributes()['referral_code'] ?? null)) {
                $user->referral_code = self::generateUniqueReferralCode();
            }
        });
    }

    public static function generateUniqueReferralCode(): string
    {
        do {
            $code = 'VPA-REF-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public function getReferralCodeAttribute($value): ?string
    {
        if (empty($value)) {
            $value = self::generateUniqueReferralCode();
            if ($this->exists) {
                $this->forceFill(['referral_code' => $value])->saveQuietly();
            }
        }
        return $value;
    }

    public function getReferralLinkAttribute(): string
    {
        return url('/r/' . $this->referral_code);
    }

    public function profile()
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'candidate_id');
    }

    public function employerProfile()
    {
        return $this->hasOne(EmployerProfile::class);
    }

    public function rating()
    {
        return $this->hasOne(CandidateRating::class, 'candidate_id');
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'candidate_id');
    }

    public function referralWallet()
    {
        return $this->hasOne(ReferralWallet::class);
    }

    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id')->latest();
    }

    public function referralReceived()
    {
        return $this->hasOne(Referral::class, 'referee_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referralTransactions()
    {
        return $this->hasMany(ReferralWalletTransaction::class, 'user_id')->latest();
    }

    public function referralEmailInvites()
    {
        return $this->hasMany(ReferralEmailInvite::class, 'referrer_id')->latest();
    }

    public function referralRedemptions()
    {
        return $this->hasMany(ReferralRedemption::class, 'user_id')->latest();
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class, 'user_id')->latest();
    }

    public function crmFollowUps()
    {
        return $this->hasMany(CrmFollowUp::class, 'candidate_id')->latest();
    }

    public function latestFollowUp()
    {
        return $this->hasOne(CrmFollowUp::class, 'candidate_id')->latestOfMany();
    }

    public function openFollowUps()
    {
        return $this->hasMany(CrmFollowUp::class, 'candidate_id')->where('status', 'open');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    public function isCandidate(): bool
    {
        return $this->role === 'candidate';
    }

    public function requiresPasswordChange(): bool
    {
        return array_key_exists('must_change_password', $this->getAttributes())
            && (bool) $this->must_change_password;
    }

    /**
     * Store only a one-way hash of the temporary password and require rotation on next login.
     */
    public function issueTemporaryPassword(string $plainTemporaryPassword): void
    {
        $updates = [
            'password' => \Illuminate\Support\Facades\Hash::make($plainTemporaryPassword),
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'must_change_password')) {
            $updates['must_change_password'] = true;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'temp_password_issued_at')) {
            $updates['temp_password_issued_at'] = now();
        }

        $this->forceFill($updates)
            ->setRememberToken(\Illuminate\Support\Str::random(60));
        $this->save();
    }

    /**
     * Hash and persist a user-chosen password and clear any temporary password lock.
     */
    public function completePasswordUpdate(string $newPlainPassword): void
    {
        $updates = [
            'password' => \Illuminate\Support\Facades\Hash::make($newPlainPassword),
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'must_change_password')) {
            $updates['must_change_password'] = false;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'password_changed_at')) {
            $updates['password_changed_at'] = now();
        }

        $this->forceFill($updates)
            ->setRememberToken(\Illuminate\Support\Str::random(60));
        $this->save();
    }

    public function canViewJobProtectedDetails(?JobPost $job = null): bool
    {
        // Admins can always see details
        if ($this->isAdmin()) {
            return true;
        }

        // Employer who owns the job can see details
        if ($this->isEmployer() && $job && $job->user_id === $this->id) {
            return true;
        }

        // Candidates must have completed full registration (profile, agreement, payment)
        if ($this->isCandidate()) {
            return (bool) ($this->profile && $this->profile->isRegistrationCompleted());
        }

        return false;
    }
}
