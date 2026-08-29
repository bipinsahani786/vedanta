<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referee_id',
        'referral_code_used',
        'source',
        'stage',
        'points_earned',
        'points_pending',
        'status',
        'completed_at',
        'verified_at',
        'interview_at',
        'selected_at',
        'joined_at',
    ];

    protected $casts = [
        'points_earned' => 'decimal:2',
        'points_pending' => 'decimal:2',
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
        'interview_at' => 'datetime',
        'selected_at' => 'datetime',
        'joined_at' => 'datetime',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ReferralWalletTransaction::class, 'referral_id');
    }

    /**
     * Get visual badge and label based on current stage
     */
    public function getStageBadgeAttribute(): array
    {
        return match ($this->stage) {
            'registered' => [
                'label' => 'Registered',
                'class' => 'bg-blue-50 text-blue-600 border-blue-200',
                'icon' => 'fa-user-check',
                'progress' => 17,
            ],
            'profile_completed' => [
                'label' => 'Profile Completed',
                'class' => 'bg-purple-50 text-purple-600 border-purple-200',
                'icon' => 'fa-id-card',
                'progress' => 33,
            ],
            'verified' => [
                'label' => 'Profile Verified',
                'class' => 'bg-cyan-50 text-cyan-600 border-cyan-200',
                'icon' => 'fa-check-double',
                'progress' => 50,
            ],
            'interview_scheduled' => [
                'label' => 'Interview',
                'class' => 'bg-amber-50 text-amber-600 border-amber-200',
                'icon' => 'fa-calendar-alt',
                'progress' => 67,
            ],
            'selected' => [
                'label' => 'Selected',
                'class' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                'icon' => 'fa-user-check',
                'progress' => 83,
            ],
            'joined', 'placed' => [
                'label' => 'Successfully Joined',
                'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'icon' => 'fa-trophy',
                'progress' => 100,
            ],
            default => [
                'label' => ucfirst(str_replace('_', ' ', $this->stage)),
                'class' => 'bg-slate-50 text-slate-600 border-slate-200',
                'icon' => 'fa-circle',
                'progress' => 10,
            ],
        };
    }

    /**
     * Get progress percentage (20% to 100%)
     */
    public function getProgressPercentAttribute(): int
    {
        return $this->stage_badge['progress'] ?? 20;
    }

    /**
     * Get Source icon and badge
     */
    public function getSourceBadgeAttribute(): array
    {
        return match (strtolower($this->source ?? 'other')) {
            'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'fa-whatsapp', 'class' => 'text-emerald-600 bg-emerald-50'],
            'email' => ['label' => 'Email Invite', 'icon' => 'fa-envelope', 'class' => 'text-purple-600 bg-purple-50'],
            'telegram' => ['label' => 'Telegram', 'icon' => 'fa-telegram', 'class' => 'text-sky-600 bg-sky-50'],
            'facebook' => ['label' => 'Facebook', 'icon' => 'fa-facebook', 'class' => 'text-blue-600 bg-blue-50'],
            'copy_link' => ['label' => 'Direct Link', 'icon' => 'fa-link', 'class' => 'text-indigo-600 bg-indigo-50'],
            default => ['label' => 'Direct / Other', 'icon' => 'fa-globe', 'class' => 'text-slate-600 bg-slate-50'],
        };
    }
}
