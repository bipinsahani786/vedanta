<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateAccessRequest extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PARTIALLY_APPROVED = 'partially_approved';
    public const STATUS_DECLINED = 'declined';

    public const DELIVERY_DASHBOARD = 'dashboard';
    public const DELIVERY_EMAIL = 'email';
    public const DELIVERY_BOTH = 'both';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'requested_photo' => 'boolean',
            'requested_full_profile' => 'boolean',
            'requested_cv' => 'boolean',
            'requested_contact' => 'boolean',
            'can_view_photo' => 'boolean',
            'can_view_full_profile' => 'boolean',
            'can_view_cv' => 'boolean',
            'can_view_contact' => 'boolean',
            'email_sent_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(User::class, 'school_user_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CandidateAccessLog::class, 'access_request_id')->orderByDesc('created_at')->orderByDesc('id');
    }

    public function isUnlockGranted(): bool
    {
        return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_PARTIALLY_APPROVED], true);
    }

    public function allowsDashboardView(): bool
    {
        return $this->isUnlockGranted()
            && in_array($this->delivery_method, [self::DELIVERY_DASHBOARD, self::DELIVERY_BOTH], true);
    }

    public function canViewFieldOnDashboard(string $field): bool
    {
        if (! $this->allowsDashboardView()) {
            return false;
        }

        return match ($field) {
            'photo' => (bool) $this->can_view_photo,
            'full_profile' => (bool) $this->can_view_full_profile,
            'cv' => (bool) $this->can_view_cv,
            'contact' => (bool) $this->can_view_contact,
            default => false,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_PARTIALLY_APPROVED => 'Partially Approved',
            self::STATUS_DECLINED => 'Declined',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    public function grantedPermissionLabels(): array
    {
        $labels = [];
        if ($this->can_view_photo) {
            $labels[] = 'Photo';
        }
        if ($this->can_view_full_profile) {
            $labels[] = 'Full Profile';
        }
        if ($this->can_view_cv) {
            $labels[] = 'CV / Resume';
        }
        if ($this->can_view_contact) {
            $labels[] = 'Contact Details';
        }

        return $labels;
    }

    public function requestedPermissionLabels(): array
    {
        $labels = [];
        if ($this->requested_photo) {
            $labels[] = 'Photo';
        }
        if ($this->requested_full_profile) {
            $labels[] = 'Full Profile';
        }
        if ($this->requested_cv) {
            $labels[] = 'CV / Resume';
        }
        if ($this->requested_contact) {
            $labels[] = 'Contact Details';
        }

        return $labels;
    }
}
