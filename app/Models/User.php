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
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
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
}
