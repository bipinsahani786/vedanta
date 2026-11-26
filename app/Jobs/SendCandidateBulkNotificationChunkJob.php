<?php

namespace App\Jobs;

use App\Mail\CandidateReminderMail;
use App\Mail\DynamicTemplateMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCandidateBulkNotificationChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 85;

    public array $candidateIds;

    public string $notificationType;

    public ?string $customSubject;

    public ?string $customMessage;

    public string $batchKey;

    public function __construct(
        array $candidateIds,
        string $notificationType,
        ?string $customSubject = null,
        ?string $customMessage = null,
        string $batchKey = ''
    ) {
        $this->candidateIds = array_values(array_unique(array_map('intval', $candidateIds)));
        $this->notificationType = $notificationType;
        $this->customSubject = $customSubject;
        $this->customMessage = $customMessage;
        $this->batchKey = $batchKey;
    }

    public function handle(): void
    {
        if (empty($this->candidateIds)) {
            return;
        }

        $candidates = User::where('role', 'candidate')
            ->whereIn('id', $this->candidateIds)
            ->with('profile')
            ->get();

        $sent = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($candidates as $user) {
            if (empty($user->email)) {
                $skipped++;
                continue;
            }

            try {
                if ($this->notificationType === 'custom_email') {
                    $replacements = [
                        '{name}' => $user->name ?? '',
                        '[name]' => $user->name ?? '',
                        '{email}' => $user->email ?? '',
                        '[email]' => $user->email ?? '',
                        '{phone}' => $user->phone ?? '',
                        '[phone]' => $user->phone ?? '',
                    ];

                    $subject = str_replace(
                        array_keys($replacements),
                        array_values($replacements),
                        (string) $this->customSubject
                    );
                    $plainBody = str_replace(
                        array_keys($replacements),
                        array_values($replacements),
                        (string) $this->customMessage
                    );
                    $htmlBody = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937;">'
                        . nl2br(e($plainBody))
                        . '</div>';

                    Mail::to($user->email)->sendNow(new DynamicTemplateMail($subject, $htmlBody));
                    $sent++;
                } else {
                    $profile = $user->profile;
                    $reason = $profile ? $profile->pending_reason : 'Pending Profile Completion';

                    if ($reason === 'Completed') {
                        $skipped++;
                        continue;
                    }

                    $actionUrl = $profile ? $profile->pending_action_url : route('candidate.wizard');
                    Mail::to($user->email)->sendNow(new CandidateReminderMail($user, $reason, $actionUrl));
                    $sent++;
                }
            } catch (\Throwable $e) {
                $failed++;
                Log::warning('Bulk candidate notification chunk item failed', [
                    'batch' => $this->batchKey,
                    'candidate_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->updateBatchProgress(count($candidates), $sent, $skipped, $failed);
    }

    private function updateBatchProgress(int $processedDelta, int $sentDelta, int $skippedDelta, int $failedDelta): void
    {
        if ($this->batchKey === '') {
            return;
        }

        $cacheKey = "crm_bulk_batch:{$this->batchKey}";

        try {
            $updater = function () use ($cacheKey, $processedDelta, $sentDelta, $skippedDelta, $failedDelta): void {
                $state = Cache::get($cacheKey);
                if (!is_array($state)) {
                    return;
                }

                $state['processed'] = (int) ($state['processed'] ?? 0) + $processedDelta;
                $state['sent'] = (int) ($state['sent'] ?? 0) + $sentDelta;
                $state['skipped'] = (int) ($state['skipped'] ?? 0) + $skippedDelta;
                $state['failed'] = (int) ($state['failed'] ?? 0) + $failedDelta;
                $state['chunks_done'] = (int) ($state['chunks_done'] ?? 0) + 1;

                if ($state['processed'] >= (int) ($state['total'] ?? 0) || $state['chunks_done'] >= (int) ($state['total_chunks'] ?? 1)) {
                    $state['status'] = 'completed';
                    $state['completed_at'] = now('Asia/Kolkata')->format('d M Y, h:i A') . ' IST';
                } else {
                    $state['status'] = 'processing';
                }

                Cache::put($cacheKey, $state, now()->addHours(12));
            };

            Cache::lock("crm_bulk_batch_lock:{$this->batchKey}", 5)->block(3, $updater);
        } catch (\Throwable $e) {
            Log::warning('Bulk batch progress cache update skipped: ' . $e->getMessage());
        }
    }
}
