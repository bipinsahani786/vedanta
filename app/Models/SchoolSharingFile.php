<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Throwable;

class SchoolSharingFile extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'password_hash',
        'password_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'separate_password_share' => 'boolean',
            'password_updated_at' => 'datetime',
            'allow_qualifications' => 'boolean',
            'allow_date_of_birth' => 'boolean',
            'allow_current_salary' => 'boolean',
            'allow_expected_salary' => 'boolean',
            'allow_experience' => 'boolean',
            'allow_availability' => 'boolean',
            'allow_cv_view' => 'boolean',
            'allow_photo' => 'boolean',
            'allow_contact' => 'boolean',
            'last_shared_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(User::class, 'school_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(SchoolSharingCategory::class, 'sharing_file_id')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(SchoolSharingFileCandidate::class, 'sharing_file_id')
            ->latest('id');
    }

    public function hasPassword(): bool
    {
        return ! empty($this->password_hash);
    }

    public function verifyPassword(string $plain): bool
    {
        if (! $this->hasPassword()) {
            return true;
        }

        return Hash::check($plain, (string) $this->password_hash);
    }

    public function unlockSessionDigest(): string
    {
        return hash('sha256', (string) ($this->password_hash ?? 'open') . '|' . $this->id);
    }

    public function setFilePassword(?string $plainPassword): void
    {
        $trimmed = $plainPassword !== null ? trim($plainPassword) : '';
        if ($trimmed === '') {
            $this->password_hash = null;
            $this->password_encrypted = null;
            $this->password_updated_at = now();
            return;
        }

        $this->password_hash = Hash::make($trimmed);
        $this->password_encrypted = Crypt::encryptString($trimmed);
        $this->password_updated_at = now();
    }

    public function decryptSharePassword(): ?string
    {
        if (! $this->hasPassword() || empty($this->password_encrypted)) {
            return null;
        }

        try {
            return Crypt::decryptString((string) $this->password_encrypted);
        } catch (Throwable) {
            return null;
        }
    }

    public function shareUrl(): string
    {
        return route('shared-file.show', $this->share_token);
    }

    public function approvedFieldLabels(): array
    {
        $fields = [];
        if ($this->allow_qualifications) {
            $fields[] = 'Qualifications';
        }
        if ($this->allow_date_of_birth) {
            $fields[] = 'Date of Birth';
        }
        if ($this->allow_current_salary) {
            $fields[] = 'Current Salary';
        }
        if ($this->allow_expected_salary) {
            $fields[] = 'Expected Salary';
        }
        if ($this->allow_experience) {
            $fields[] = 'Experience';
        }
        if ($this->allow_availability) {
            $fields[] = 'Availability';
        }
        if ($this->allow_cv_view) {
            $fields[] = 'CV (View Only)';
        }
        if ($this->allow_photo) {
            $fields[] = 'Photo';
        }
        if ($this->allow_contact) {
            $fields[] = 'Contact Details';
        }

        return $fields;
    }
}
