<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    /** @use HasFactory<\Database\Factories\JobPostFactory> */
    use HasFactory;

    protected $guarded = [];

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

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_post_id');
    }

    public function getSuggestedCandidates($limit = 5)
    {
        return CandidateProfile::with(['user', 'category', 'subject', 'highestQualification', 'preferredState', 'preferredCity'])
            ->where('is_profile_complete', true)
            ->get()
            ->map(function ($candidate) {
                $score = 0;
                $matched = [];
                
                if ($this->category_id && $candidate->category_id == $this->category_id) {
                    $score += 30;
                    $matched[] = 'category';
                }
                if ($this->subject_id && $candidate->subject_id == $this->subject_id) {
                    $score += 50;
                    $matched[] = 'subject';
                }
                if ($this->qualification_id && $candidate->highest_qualification_id == $this->qualification_id) {
                    $score += 20;
                    $matched[] = 'qualification';
                }
                
                $candidate->match_percentage = $score;
                $candidate->matched_criteria = $matched;
                return $candidate;
            })
            ->filter(function ($candidate) {
                return $candidate->match_percentage > 0;
            })
            ->sortByDesc('match_percentage')
            ->take($limit)
            ->values();
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedJob::class, 'job_post_id');
    }

    protected $casts = [
        'salary_min' => 'float',
        'salary_max' => 'float',
    ];

    /**
     * Unique display code for job (e.g. #JOB-1030)
     */
    public function getJobCodeAttribute(): string
    {
        return '#JOB-' . (1000 + $this->id);
    }

    /**
     * Check if a specific user (or currently authenticated user) can view protected school details.
     */
    public function canUserViewProtectedDetails(?User $user = null): bool
    {
        $user = $user ?? (auth()->check() ? auth()->user() : null);
        if (!$user) {
            return false;
        }
        return $user->canViewJobProtectedDetails($this);
    }

    /**
     * Get formatted salary string based on salary_mode, min, max, rate, or legacy salary_range.
     */
    public function getFormattedSalaryAttribute(): string
    {
        $rate = $this->salary_rate ?: 'per month';

        if ($this->salary_mode === 'range') {
            if ($this->salary_min > 0 && $this->salary_max > 0) {
                return '₹' . number_format($this->salary_min) . ' – ₹' . number_format($this->salary_max) . ' ' . $rate;
            }
        } elseif ($this->salary_mode === 'starting_amount') {
            if ($this->salary_min > 0) {
                return 'From ₹' . number_format($this->salary_min) . ' ' . $rate;
            }
        } elseif ($this->salary_mode === 'maximum_amount') {
            $amount = $this->salary_max > 0 ? $this->salary_max : $this->salary_min;
            if ($amount > 0) {
                return 'Up to ₹' . number_format($amount) . ' ' . $rate;
            }
        } elseif ($this->salary_mode === 'exact_amount') {
            $amount = $this->salary_min > 0 ? $this->salary_min : $this->salary_max;
            if ($amount > 0) {
                return '₹' . number_format($amount) . ' ' . $rate;
            }
        }

        // Fallback to legacy salary_range if specified
        if (!empty($this->salary_range)) {
            $range = trim($this->salary_range);
            // Ensure rupee symbol is present if it's purely numbers
            if (!str_contains($range, '₹') && preg_match('/\d/', $range)) {
                $range = '₹' . $range;
            }
            if (!str_contains($range, 'per month') && !str_contains($range, 'month') && !str_contains($range, 'year')) {
                $range .= ' per month';
            }
            return $range;
        }

        return 'Competitive / As per industry standards';
    }

    /**
     * Get masked or revealed school name depending on eligibility
     */
    public function getMaskedSchoolName(?User $user = null): string
    {
        if ($this->canUserViewProtectedDetails($user)) {
            return $this->school_name ?: 'Reputed School';
        }
        return 'School Name Confidential';
    }

    /**
     * Get masked or revealed city
     */
    public function getMaskedCity(?User $user = null): ?string
    {
        if ($this->canUserViewProtectedDetails($user)) {
            return $this->city?->name;
        }
        return null;
    }

    /**
     * Get masked or revealed location (City, State if unlocked; State only if locked)
     */
    public function getMaskedLocation(?User $user = null): string
    {
        $stateName = $this->state?->name ?? 'India';
        if ($this->canUserViewProtectedDetails($user)) {
            $cityName = $this->city?->name;
            return $cityName ? ($cityName . ', ' . $stateName) : $stateName;
        }
        return $stateName;
    }

    /**
     * Get school image URL or null if locked
     */
    public function getMaskedSchoolImage(?User $user = null): ?string
    {
        if ($this->canUserViewProtectedDetails($user)) {
            if (!empty($this->school_image)) {
                return str_starts_with($this->school_image, 'http') ? $this->school_image : asset('storage/' . $this->school_image);
            }
            return null;
        }
        return null;
    }
}

