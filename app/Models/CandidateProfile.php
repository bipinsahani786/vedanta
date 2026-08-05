<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    /** @use HasFactory<\Database\Factories\CandidateProfileFactory> */
    use HasFactory;

    protected $guarded = [];

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
}
