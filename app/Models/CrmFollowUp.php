<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CrmFollowUp extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'follow_up_date' => 'date',
        'follow_up_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function hasExtendedColumns(): bool
    {
        return Schema::hasColumn('crm_follow_ups', 'follow_up_at')
            && Schema::hasColumn('crm_follow_ups', 'next_action')
            && Schema::hasColumn('crm_follow_ups', 'reminder_sent_at');
    }

    public function getScheduledDateTimeAttribute(): ?Carbon
    {
        if (! empty($this->follow_up_at)) {
            return Carbon::parse($this->follow_up_at);
        }

        if (! empty($this->follow_up_date)) {
            return Carbon::parse($this->follow_up_date)->setTime(9, 0);
        }

        return null;
    }

    public function getIsDueAttribute(): bool
    {
        if ($this->status !== 'open' || ! $this->scheduled_date_time) {
            return false;
        }

        return $this->scheduled_date_time->lte(now());
    }

    /**
     * Dispatch admin reminder notifications for due open candidate follow-ups.
     */
    public static function dispatchDueReminders(): int
    {
        if (! static::hasExtendedColumns()) {
            return 0;
        }

        $now = now();
        $dueItems = static::with(['candidate.profile'])
            ->where('status', 'open')
            ->whereNull('reminder_sent_at')
            ->where(function ($q) use ($now) {
                $q->where(function ($sub) use ($now) {
                    $sub->whereNotNull('follow_up_at')
                        ->where('follow_up_at', '<=', $now);
                })->orWhere(function ($sub) use ($now) {
                    $sub->whereNull('follow_up_at')
                        ->whereNotNull('follow_up_date')
                        ->whereDate('follow_up_date', $now->toDateString());
                });
            })
            ->orderByRaw('COALESCE(follow_up_at, follow_up_date) asc')
            ->limit(25)
            ->get();

        if ($dueItems->isEmpty()) {
            return 0;
        }

        $admins = User::where('role', 'admin')->get(['id']);
        if ($admins->isEmpty()) {
            return 0;
        }

        $dispatched = 0;

        foreach ($dueItems as $followUp) {
            if (! $followUp->candidate) {
                continue;
            }

            $claimed = static::where('id', $followUp->id)
                ->where('status', 'open')
                ->whereNull('reminder_sent_at')
                ->update(['reminder_sent_at' => $now]);

            if ($claimed === 0) {
                continue;
            }

            $candidate = $followUp->candidate;
            $vpaId = $candidate->profile?->vpa_id ? " ({$candidate->profile->vpa_id})" : '';
            $actionText = $followUp->next_action ? "Next: {$followUp->next_action}" : 'Follow-up scheduled';
            $scheduledStr = $followUp->follow_up_at
                ? $followUp->follow_up_at->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ' IST'
                : ($followUp->follow_up_date ? $followUp->follow_up_date->format('d M Y') : '');
            $notesSnippet = Str::limit(trim((string) $followUp->notes), 90);

            $payload = json_encode([
                'title' => "Follow-Up Reminder: {$candidate->name}",
                'message' => "{$candidate->name}{$vpaId} — {$actionText}" . ($scheduledStr ? " ({$scheduledStr})" : '') . ($notesSnippet ? ": {$notesSnippet}" : ''),
                'candidate_id' => $candidate->id,
                'follow_up_id' => $followUp->id,
                'next_action' => $followUp->next_action,
                'scheduled_at' => $followUp->scheduled_date_time?->toIso8601String(),
                'action_url' => route('admin.crm.show', $candidate->id) . '#follow-ups-section',
            ]);

            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'id' => Str::uuid()->toString(),
                    'type' => 'App\Notifications\CandidateFollowUpReminder',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $admin->id,
                    'data' => $payload,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $dispatched++;
        }

        return $dispatched;
    }
}
