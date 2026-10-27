<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\CandidateProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CandidateProfile extends Model
{
    /** @use HasFactory<CandidateProfileFactory> */
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
                $profile->vpa_id = sprintf('VPA-%s-%03d', $year, $nextSequence);
            }
        });

        static::updating(function ($profile) {
            if ($profile->is_agreement_signed && empty($profile->agreement_id)) {
                if (! empty($profile->vpa_id)) {
                    $profile->agreement_id = str_replace('VPA-', 'VPA-AGR-', $profile->vpa_id);
                    // Ensure CAN is replaced if it exists
                    $profile->agreement_id = str_replace('VPA-AGR-CAN-', 'VPA-AGR-', $profile->agreement_id);
                } else {
                    $profile->agreement_id = 'VPA-AGR-'.date('Y').'-'.str_pad($profile->id, 6, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    public function ensureIdsAssigned(): void
    {
        $dirty = false;
        $year = $this->created_at ? $this->created_at->format('Y') : date('Y');

        if (empty($this->vpa_id)) {
            $lastProfile = self::where('vpa_id', 'like', "VPA-{$year}-%")->orderBy('id', 'desc')->first();
            $nextSequence = 1;
            if ($lastProfile && $lastProfile->vpa_id) {
                $parts = explode('-', $lastProfile->vpa_id);
                if (count($parts) === 3 && is_numeric($parts[2])) {
                    $nextSequence = intval($parts[2]) + 1;
                }
            }
            $this->vpa_id = sprintf('VPA-%s-%03d', $year, $nextSequence);
            $dirty = true;
        }

        $isSigned = $this->is_agreement_signed
            || ! empty($this->agreement_pdf_path)
            || ! empty($this->signature_data);

        if ($isSigned) {
            if (! $this->is_agreement_signed) {
                $this->is_agreement_signed = true;
                $dirty = true;
            }
            if (empty($this->agreement_id)) {
                $this->agreement_id = str_replace('VPA-', 'VPA-AGR-', $this->vpa_id);
                $dirty = true;
            }
        }

        if ($dirty) {
            $this->saveQuietly();
        }
    }

    protected $casts = [
        'date_of_birth' => 'date',
        'is_profile_complete' => 'boolean',
        'is_agreement_signed' => 'boolean',
        'is_fee_paid' => 'boolean',
        'registration_completed_at' => 'datetime',
        'signature_date_time' => 'datetime',
        'plan_started_at' => 'datetime',
        'agreement_signed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsManualAgreementAttribute(): bool
    {
        return ! empty($this->agreement_pdf_path) && (
            str_contains($this->agreement_pdf_path, 'admin_uploaded') ||
            str_contains($this->agreement_pdf_path, 'manual') ||
            (! empty($this->manual_agreement_path) && $this->agreement_pdf_path === $this->manual_agreement_path)
        );
    }

    public function getHasUploadedManualBackupAttribute(): bool
    {
        return ! empty($this->manual_agreement_path) &&
            Storage::disk('public')->exists($this->manual_agreement_path);
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

    public function getAgreementSignedAtAttribute()
    {
        if (! empty($this->attributes['agreement_signed_at'])) {
            return $this->asDateTime($this->attributes['agreement_signed_at']);
        }

        return $this->signature_date_time;
    }

    public function setAgreementSignedAtAttribute($value)
    {
        $dt = $value ? $this->fromDateTime($value) : null;
        if (array_key_exists('agreement_signed_at', $this->attributes)) {
            $this->attributes['agreement_signed_at'] = $dt;
        }
        $this->attributes['signature_date_time'] = $dt;
    }

    public function hasCompletedProfileDetails(): bool
    {
        if (! $this->is_profile_complete) {
            return false;
        }

        return ! empty($this->date_of_birth)
            && ! empty($this->address)
            && ! empty($this->highest_qualification_id)
            && ! empty($this->category_id)
            && ! empty($this->subject_id);
    }

    public function getPendingReasonAttribute()
    {
        if ($this->isRegistrationCompleted()) {
            return 'Completed';
        }
        if (! $this->hasCompletedProfileDetails()) {
            return 'Pending Profile Completion';
        }
        $hasAgreement = (bool) (
            $this->is_agreement_signed ||
            ! empty($this->signature_date_time) ||
            ! empty($this->agreement_signed_at) ||
            ! empty($this->agreement_pdf_path) ||
            $this->is_manual_agreement
        );
        if (! $hasAgreement) {
            return 'Pending Agreement Upload';
        }
        if (! $this->has_paid_plan) {
            return 'Pending Registration Fee';
        }

        return 'Completed';
    }

    public function getPendingActionUrlAttribute()
    {
        if ($this->isRegistrationCompleted()) {
            return route('candidate.dashboard');
        }
        if (! $this->hasCompletedProfileDetails()) {
            return route('candidate.wizard', ['step' => 1]);
        }
        $hasAgreement = (bool) (
            $this->is_agreement_signed ||
            ! empty($this->signature_date_time) ||
            ! empty($this->agreement_signed_at) ||
            ! empty($this->agreement_pdf_path) ||
            $this->is_manual_agreement
        );
        if (! $hasAgreement) {
            return route('candidate.wizard', ['step' => 2]);
        }
        if (! $this->has_paid_plan) {
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

    public function getPlanValidityDateAttribute(): ?Carbon
    {
        if (! $this->has_paid_plan || ! $this->plan_started_at) {
            return null;
        }

        return Carbon::parse($this->plan_started_at)->addMonths($this->plan_duration_months);
    }

    public function getIsPlanExpiredAttribute(): bool
    {
        if (! $this->has_paid_plan || ! $this->plan_started_at) {
            return false;
        }

        return $this->plan_validity_date ? $this->plan_validity_date->isPast() : false;
    }

    public function getIsPlanActiveAttribute(): bool
    {
        return $this->has_paid_plan && ! $this->is_plan_expired;
    }

    public function getCurrentPlanNameAttribute(): string
    {
        if (! $this->has_paid_plan) {
            return 'No Active Plan';
        }

        return ($this->plan_type ? ucfirst($this->plan_type) : 'Standard').' Plan';
    }

    public function getPlanStatusBadgeAttribute(): string
    {
        if (! $this->has_paid_plan) {
            return 'Inactive';
        }
        if ($this->is_plan_expired) {
            return 'Expired';
        }

        return 'Active';
    }

    public function isRegistrationCompleted(): bool
    {
        $hasDetails = $this->hasCompletedProfileDetails();

        $hasAgreement = (bool) (
            $this->is_agreement_signed ||
            ! empty($this->signature_date_time) ||
            ! empty($this->agreement_signed_at) ||
            ! empty($this->agreement_pdf_path) ||
            $this->is_manual_agreement
        );

        $hasPayment = (bool) ($this->initial_fee_paid || $this->is_fee_paid || ($this->paid_amount ?? 0) >= 500);

        // If explicitly stamped with registration_completed_at, candidate must at least have profile details
        if (! empty($this->registration_completed_at)) {
            return $hasDetails;
        }

        return (bool) ($hasDetails && $hasAgreement && $hasPayment);
    }
}
