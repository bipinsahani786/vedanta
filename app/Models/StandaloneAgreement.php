<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class StandaloneAgreement extends Model
{
    protected $fillable = [
        'agreement_code',
        'token',
        'recipient_email',
        'recipient_name',
        'recipient_phone',
        'subject',
        'admin_note',
        'status',
        'sent_by',
        'sent_at',
        'last_sent_at',
        'resent_count',
        'resent_at',
        'expires_at',
        'signed_at',
        'signer_name',
        'signature_type',
        'signature_data',
        'terms_accepted',
        'signer_ip',
        'signer_user_agent',
        'pdf_path',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'resent_at' => 'datetime',
        'expires_at' => 'datetime',
        'signed_at' => 'datetime',
        'terms_accepted' => 'boolean',
        'resent_count' => 'integer',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public static function generateUniqueToken(): string
    {
        do {
            $token = Str::random(64);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    public static function generateAgreementCode(): string
    {
        $year = now('Asia/Kolkata')->format('Y');
        $nextSeq = ((int) (self::max('id') ?? 0)) + 1;

        do {
            $code = sprintf('VPA-SA-%s-%04d', $year, $nextSeq);
            $nextSeq++;
        } while (self::where('agreement_code', $code)->exists());

        return $code;
    }

    public function isSigned(): bool
    {
        return $this->status === 'signed';
    }

    public function isExpired(): bool
    {
        if ($this->isSigned()) {
            return false;
        }

        if ($this->status === 'expired') {
            return true;
        }

        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isResent(): bool
    {
        return !$this->isSigned() && !$this->isExpired() && (int) $this->resent_count > 0;
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->isSigned()) {
            return 'Signed';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        if ($this->isResent()) {
            return 'Resent';
        }

        return 'Pending';
    }

    public function getDisplayStatusKeyAttribute(): string
    {
        return strtolower($this->display_status);
    }

    public function getSigningUrlAttribute(): string
    {
        return route('standalone-agreement.show', ['token' => $this->token]);
    }

    public static function formatIst(?CarbonInterface $dt): ?string
    {
        return $dt ? $dt->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ' IST' : null;
    }

    public function scopeFilterByStatus(Builder $query, ?string $status): Builder
    {
        $status = strtolower(trim((string) $status));

        return match ($status) {
            'signed' => $query->where('status', 'signed'),
            'expired' => $query->where('status', '!=', 'signed')
                ->where(function (Builder $q) {
                    $q->where('status', 'expired')
                        ->orWhere(function (Builder $sub) {
                            $sub->whereNotNull('expires_at')->where('expires_at', '<', now());
                        });
                }),
            'resent' => $query->where('status', 'pending')
                ->where('resent_count', '>', 0)
                ->where(function (Builder $q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                }),
            'pending' => $query->where('status', 'pending')
                ->where('resent_count', 0)
                ->where(function (Builder $q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                }),
            default => $query,
        };
    }
}
