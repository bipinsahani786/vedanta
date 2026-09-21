<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    /** @use HasFactory<\Database\Factories\CandidateProfileFactory> */
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($profile) {
            if (empty($profile->vpa_id)) {
                $year = date('Y');
                $lastProfile = self::where('vpa_id', 'like', "VPA-{$year}-%")->orderBy('id', 'desc')->first();
                $nextSequence = 1;
                if ($lastProfile && $lastProfile->vpa_id) {
                    $parts = explode('-', $lastProfile->vpa_id);
                    if (count($parts) === 3) {
                        $nextSequence = intval($parts[2]) + 1;
                    }
                }
                $profile->vpa_id = sprintf("VPA-%s-%03d", $year, $nextSequence);
            }
        });
    }

    protected $casts = [
        'date_of_birth' => 'date',
        'is_profile_complete' => 'boolean',
        'is_agreement_signed' => 'boolean',
        'is_fee_paid' => 'boolean',
        'registration_completed_at' => 'datetime',
        'signature_date_time' => 'datetime',
        'plan_started_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function highestQualification()
    {
        return $this->belongsTo(Qualification::class, 'highest_qualification_id');
    }

    public function preferredState()
    {
        return $this->belongsTo(State::class, 'preferred_state_id');
    }

    public function preferredCity()
    {
        return $this->belongsTo(City::class, 'preferred_city_id');
    }

    public function getPendingReasonAttribute()
    {
        if ($this->is_fee_paid) {
            return 'Completed';
        }
        if (!$this->is_profile_complete) {
            return 'Pending Profile Completion';
        }
        if (!$this->is_terms_agreed) {
            return 'Pending Terms & Conditions';
        }
        if (!$this->is_agreement_signed) {
            return 'Pending Agreement Upload';
        }
        if (!$this->is_fee_paid) {
            return 'Pending Registration Fee';
        }
        return 'Completed';
    }

    public function getPendingActionUrlAttribute()
    {
        if (!$this->is_profile_complete) {
            return route('candidate.wizard');
        }
        if (!$this->is_terms_agreed) {
            return route('candidate.wizard', ['step' => 2]); // Usually wizard redirects to the correct step automatically
        }
        if (!$this->is_agreement_signed) {
            return route('candidate.wizard', ['step' => 3]);
        }
        if (!$this->is_fee_paid) {
            return route('candidate.wizard', ['step' => 4]);
        }
        return route('candidate.dashboard');
    }

    public function getHasPaidPlanAttribute(): bool
    {
        return (bool) ($this->initial_fee_paid || $this->is_fee_paid || ($this->paid_amount ?? 0) >= 500);
    }

    public function getPlanDurationMonthsAttribute(): int
    {
        $type = strtolower($this->plan_type ?? '');
        if ($type === 'premium' || ($this->paid_amount ?? 0) >= 1000) {
            return 6;
        }
        return 3;
    }

    public function getPlanValidityDateAttribute(): ?\Carbon\Carbon
    {
        if (!$this->has_paid_plan || !$this->plan_started_at) {
            return null;
        }
        return \Carbon\Carbon::parse($this->plan_started_at)->addMonths($this->plan_duration_months);
    }

    public function getIsPlanExpiredAttribute(): bool
    {
        if (!$this->has_paid_plan || !$this->plan_started_at) {
            return false;
        }
        return $this->plan_validity_date ? $this->plan_validity_date->isPast() : false;
    }

    public function getIsPlanActiveAttribute(): bool
    {
        return $this->has_paid_plan && !$this->is_plan_expired;
    }

    public function getCurrentPlanNameAttribute(): string
    {
        if (!$this->has_paid_plan) {
            return 'No Active Plan';
        }
        return ($this->plan_type ? ucfirst($this->plan_type) : 'Standard') . ' Plan';
    }

    public function getPlanStatusBadgeAttribute(): string
    {
        if (!$this->has_paid_plan) {
            return 'Inactive';
        }
        if ($this->is_plan_expired) {
            return 'Expired';
        }
        return 'Active';
    }

    public function isRegistrationCompleted(): bool
    {
        // 1. If explicitly marked with registration_completed_at, they are registered
        if (!empty($this->registration_completed_at)) {
            return true;
        }

        // 2. Otherwise, requires profile completed, agreement accepted, and paid registration fee/plan
        $hasPayment = (bool) ($this->initial_fee_paid || $this->is_fee_paid || ($this->paid_amount ?? 0) >= 500);
        return (bool) ($this->is_profile_complete && $this->is_agreement_signed && $hasPayment);
    }
}
