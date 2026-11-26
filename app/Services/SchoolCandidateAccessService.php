<?php

namespace App\Services;

use App\Models\CandidateAccessLog;
use App\Models\CandidateAccessRequest;
use App\Models\CandidateProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SchoolCandidateAccessService
{
    public function isFeatureReady(): bool
    {
        return Schema::hasColumn('candidate_profiles', 'school_discovery_visible')
            && Schema::hasTable('candidate_access_requests')
            && Schema::hasTable('candidate_access_logs');
    }

    /**
     * Query ONLY candidates that Admin has explicitly marked eligible/visible for school discovery.
     */
    public function buildEligibleCandidatesQuery(array $filters, int $schoolUserId): Builder
    {
        $query = User::query()
            ->where('role', 'candidate')
            ->where('is_active', true)
            ->whereHas('profile', function (Builder $pq) use ($filters) {
                $pq->where('school_discovery_visible', true);

                if (! empty($filters['category_id'])) {
                    $pq->where('category_id', (int) $filters['category_id']);
                }

                if (! empty($filters['subject_id'])) {
                    $pq->where('subject_id', (int) $filters['subject_id']);
                }

                if (! empty($filters['qualification_id'])) {
                    $pq->where('highest_qualification_id', (int) $filters['qualification_id']);
                }

                if (isset($filters['min_experience']) && $filters['min_experience'] !== '' && is_numeric($filters['min_experience'])) {
                    $pq->where('experience_years', '>=', (int) $filters['min_experience']);
                }

                if (! empty($filters['state_id'])) {
                    $pq->where('preferred_state_id', (int) $filters['state_id']);
                }

                if (! empty($filters['city_id'])) {
                    $pq->where('preferred_city_id', (int) $filters['city_id']);
                }

                if (($filters['verified_status'] ?? '') === 'verified') {
                    $pq->where('is_verified', true);
                } elseif (($filters['verified_status'] ?? '') === 'unverified') {
                    $pq->where('is_verified', false);
                }

                if (! empty($filters['curated'])) {
                    if ($filters['curated'] === 'verified') {
                        $pq->where('is_verified', true);
                    } elseif ($filters['curated'] === 'best') {
                        $pq->where('is_best_profile', true);
                    }
                }

                if (! empty($filters['availability'])) {
                    $this->applyAvailabilityFilter($pq, (string) $filters['availability']);
                }
            });

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $query->where(function (Builder $q) use ($keyword, $schoolUserId) {
                $q->whereHas('profile', function (Builder $pq) use ($keyword) {
                    $pq->where('vpa_id', 'like', "%{$keyword}%")
                        ->orWhere('other_qualifications', 'like', "%{$keyword}%")
                        ->orWhere('availability_to_join', 'like', "%{$keyword}%")
                        ->orWhereHas('subject', fn (Builder $sq) => $sq->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('category', fn (Builder $cq) => $cq->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('specialization', fn (Builder $spq) => $spq->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('highestQualification', fn (Builder $qq) => $qq->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('preferredCity', fn (Builder $ctq) => $ctq->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('preferredState', fn (Builder $stq) => $stq->where('name', 'like', "%{$keyword}%"));
                });

                // Only match real name if admin configured full_name display OR this school already has full_profile dashboard unlock.
                $q->orWhere(function (Builder $nq) use ($keyword, $schoolUserId) {
                    $nq->where('users.name', 'like', "%{$keyword}%")
                        ->where(function (Builder $allow) use ($schoolUserId) {
                            $allow->whereHas('profile', fn (Builder $p) => $p->where('school_display_mode', 'full_name'))
                                ->orWhereExists(function ($sub) use ($schoolUserId) {
                                    $sub->selectRaw('1')
                                        ->from('candidate_access_requests')
                                        ->whereColumn('candidate_access_requests.candidate_user_id', 'users.id')
                                        ->where('candidate_access_requests.school_user_id', $schoolUserId)
                                        ->whereIn('candidate_access_requests.status', [
                                            CandidateAccessRequest::STATUS_APPROVED,
                                            CandidateAccessRequest::STATUS_PARTIALLY_APPROVED,
                                        ])
                                        ->where('candidate_access_requests.can_view_full_profile', true)
                                        ->whereIn('candidate_access_requests.delivery_method', [
                                            CandidateAccessRequest::DELIVERY_DASHBOARD,
                                            CandidateAccessRequest::DELIVERY_BOTH,
                                        ]);
                                });
                        });
                });
            });
        }

        return $query;
    }

    protected function applyAvailabilityFilter(Builder $pq, string $bucket): void
    {
        $bucket = strtolower(trim($bucket));

        if ($bucket === 'immediate') {
            $pq->where(function (Builder $aq) {
                $aq->whereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%immediat%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%immidiat%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%imidiat%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%asap%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%within 7%']);
            });
        } elseif ($bucket === 'within_15_days') {
            $pq->where(function (Builder $aq) {
                $aq->whereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%15%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%7 day%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%10 day%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%immediat%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%immidiat%']);
            });
        } elseif ($bucket === 'within_1_month') {
            $pq->where(function (Builder $aq) {
                $aq->whereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%1 month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%1month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%one month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%i month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%30%']);
            });
        } elseif ($bucket === 'within_2_months') {
            $pq->where(function (Builder $aq) {
                $aq->whereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%2 month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%two month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%60%']);
            });
        } elseif ($bucket === '3_months_plus') {
            $pq->where(function (Builder $aq) {
                $aq->whereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%3 month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%3month%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%90%'])
                    ->orWhereRaw('LOWER(COALESCE(availability_to_join, "")) LIKE ?', ['%6 month%']);
            });
        } else {
            $pq->where('availability_to_join', 'like', "%{$bucket}%");
        }
    }

    public function buildSafeCandidateCard(User $candidate, ?CandidateAccessRequest $accessRequest = null): array
    {
        $profile = $candidate->profile;
        $allowsDashboard = $accessRequest ? $accessRequest->allowsDashboardView() : false;
        $canViewPhoto = $accessRequest ? $accessRequest->canViewFieldOnDashboard('photo') : false;
        $canViewFullProfile = $accessRequest ? $accessRequest->canViewFieldOnDashboard('full_profile') : false;
        $canViewCv = $accessRequest ? $accessRequest->canViewFieldOnDashboard('cv') : false;
        $canViewContact = $accessRequest ? $accessRequest->canViewFieldOnDashboard('contact') : false;

        $displayName = $profile
            ? $profile->resolveSchoolDisplayName($canViewFullProfile, $candidate->name)
            : ('VPA-CAN-' . str_pad((string) $candidate->id, 4, '0', STR_PAD_LEFT));

        $photoPath = $this->resolvePrimaryPhotoPath($profile);
        $hasPhoto = $photoPath !== null && Storage::disk('public')->exists($photoPath);
        $hasResume = ! empty($profile?->resume_path) && Storage::disk('public')->exists($profile->resume_path);

        $locationParts = array_filter([
            $profile?->preferredCity?->name,
            $profile?->preferredState?->name,
        ]);

        return [
            'id' => $candidate->id,
            'display_name' => $displayName,
            'vpa_id' => $profile?->vpa_id ?: ('VPA-CAN-' . str_pad((string) $candidate->id, 4, '0', STR_PAD_LEFT)),
            'has_photo' => $hasPhoto,
            'photo_url' => route('employer.talent.photo', $candidate->id),
            'photo_unlocked' => $canViewPhoto && $hasPhoto,
            'is_verified' => (bool) ($profile?->is_verified ?? false),
            'is_best_profile' => (bool) ($profile?->is_best_profile ?? false),
            'category' => $profile?->category?->name ?? 'General',
            'subject' => $profile?->subject?->name ?? 'General',
            'specialization' => $profile?->specialization?->name ?? null,
            'qualification' => $profile?->highestQualification?->name ?? 'Not Specified',
            'experience_years' => (int) ($profile?->experience_years ?? 0),
            'current_salary' => ! empty($profile?->current_salary) ? $profile->current_salary : 'Not Disclosed',
            'availability_to_join' => ! empty($profile?->availability_to_join) ? $profile->availability_to_join : 'On Inquiry',
            'preferred_location' => ! empty($locationParts) ? implode(', ', $locationParts) : 'Flexible',
            'has_resume' => $hasResume,

            // Access status & safe permission flags (no unapproved PII included!)
            'access_request_id' => $accessRequest?->id,
            'access_status' => $accessRequest?->status ?? 'none',
            'access_status_label' => $accessRequest ? $accessRequest->statusLabel() : 'Locked',
            'delivery_method' => $accessRequest?->delivery_method,
            'allows_dashboard' => $allowsDashboard,
            'can_view_photo' => $canViewPhoto,
            'can_view_full_profile' => $canViewFullProfile,
            'can_view_cv' => $canViewCv,
            'can_view_contact' => $canViewContact,
            'granted_permissions' => $accessRequest ? $accessRequest->grantedPermissionLabels() : [],
            'email_shared_at' => $accessRequest?->email_sent_at?->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ($accessRequest?->email_sent_at ? ' IST' : ''),
            'admin_notes' => $accessRequest?->admin_notes,
            'requested_at' => $accessRequest?->created_at?->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
            'reviewed_at' => $accessRequest?->reviewed_at?->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A'),
        ];
    }

    /**
     * Build the preview payload strictly filtered by Admin-approved field-level permissions.
     */
    public function buildCandidatePreviewPayload(User $candidate, User $school, ?CandidateAccessRequest $accessRequest = null): array
    {
        $payload = $this->buildSafeCandidateCard($candidate, $accessRequest);
        $profile = $candidate->profile;

        $payload['school_email'] = $school->email;
        $payload['request_history'] = $accessRequest
            ? $accessRequest->logs->map(fn (CandidateAccessLog $log) => [
                'action' => ucwords(str_replace('_', ' ', $log->action)),
                'status' => ucwords(str_replace('_', ' ', $log->status)),
                'permissions' => $log->granted_permissions ?? [],
                'delivery_method' => $log->delivery_method ? ucfirst($log->delivery_method) : null,
                'note' => $log->note,
                'at' => $log->created_at?->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ' IST',
            ])->values()->all()
            : [];

        $payload['unlocked_full_profile'] = null;
        $payload['unlocked_cv'] = null;
        $payload['unlocked_contact'] = null;

        if ($accessRequest && $accessRequest->allowsDashboardView()) {
            if ($accessRequest->can_view_full_profile) {
                $payload['unlocked_full_profile'] = [
                    'full_name' => $candidate->name,
                    'gender' => $profile?->gender ?? 'N/A',
                    'current_school' => $profile?->current_school ?: 'N/A',
                    'expected_salary' => $profile?->expected_salary ?: 'N/A',
                    'other_qualifications' => $profile?->other_qualifications ?: 'N/A',
                    'english_fluency' => $profile?->english_fluency ? ucfirst($profile->english_fluency) : 'N/A',
                    'residential_preference' => $profile?->residential_preference ? ucwords(str_replace('_', ' ', $profile->residential_preference)) : 'N/A',
                    'address' => $profile?->address ?: 'N/A',
                ];
            }

            if ($accessRequest->can_view_cv) {
                $hasResume = ! empty($profile?->resume_path) && Storage::disk('public')->exists($profile->resume_path);
                $payload['unlocked_cv'] = [
                    'available' => $hasResume,
                    'url' => $hasResume ? route('employer.talent.cv', $candidate->id) : null,
                ];
            }

            if ($accessRequest->can_view_contact) {
                $payload['unlocked_contact'] = [
                    'email' => $candidate->email,
                    'phone' => $candidate->phone,
                ];
            }
        }

        return $payload;
    }

    public function resolvePrimaryPhotoPath(?CandidateProfile $profile): ?string
    {
        if (! $profile) {
            return null;
        }

        return $profile->profile_photo_path
            ?: ($profile->live_photo_path ?: $profile->passport_photo_path);
    }

    /**
     * Serve clear original image ONLY if photo access is unlocked for Dashboard;
     * otherwise return a server-side pixel-blurred low-res preview or anonymous placeholder.
     */
    public function streamGatedCandidatePhoto(User $candidate, ?CandidateAccessRequest $accessRequest = null): Response
    {
        $profile = $candidate->profile;
        $path = $this->resolvePrimaryPhotoPath($profile);
        $unlocked = $accessRequest && $accessRequest->canViewFieldOnDashboard('photo');

        if ($path && Storage::disk('public')->exists($path)) {
            $bytes = Storage::disk('public')->get($path);

            if ($unlocked) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'jpeg');
                $mime = match ($ext) {
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                    default => 'image/jpeg',
                };

                return response($bytes, 200, [
                    'Content-Type' => $mime,
                    'Cache-Control' => 'private, no-store, max-age=0',
                ]);
            }

            $blurredJpeg = $this->createServerSideBlurredPreview($bytes);
            if ($blurredJpeg !== null) {
                return response($blurredJpeg, 200, [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'private, no-store, max-age=0',
                ]);
            }
        }

        $svg = $this->buildLockedSilhouetteSvg($unlocked);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function streamUnlockedCv(User $candidate): Response
    {
        $path = (string) ($candidate->profile?->resume_path ?? '');
        abort_if($path === '' || ! Storage::disk('public')->exists($path), 404, 'Resume not found.');

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf');
        $mime = match ($ext) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => 'application/octet-stream',
        };

        $safeVpa = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) ($candidate->profile?->vpa_id ?: 'Candidate_' . $candidate->id));
        $fileName = "{$safeVpa}_CV.{$ext}";

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function recordAccessLog(
        CandidateAccessRequest $accessRequest,
        string $action,
        ?int $actorUserId,
        ?string $note = null
    ): CandidateAccessLog {
        return CandidateAccessLog::create([
            'access_request_id' => $accessRequest->id,
            'school_user_id' => $accessRequest->school_user_id,
            'candidate_user_id' => $accessRequest->candidate_user_id,
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'status' => $accessRequest->status,
            'granted_permissions' => $accessRequest->grantedPermissionLabels(),
            'delivery_method' => $accessRequest->delivery_method,
            'note' => $note,
        ]);
    }

    private function createServerSideBlurredPreview(string $rawBytes): ?string
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }

        try {
            $src = @imagecreatefromstring($rawBytes);
            if (! $src) {
                return null;
            }

            $srcW = imagesx($src);
            $srcH = imagesy($src);
            if ($srcW <= 0 || $srcH <= 0) {
                imagedestroy($src);
                return null;
            }

            // Downsample sharply first so facial identity is irreversibly lost in the preview
            $tiny = imagecreatetruecolor(18, 18);
            imagecopyresampled($tiny, $src, 0, 0, 0, 0, 18, 18, $srcW, $srcH);
            imagedestroy($src);

            $out = imagecreatetruecolor(160, 160);
            imagecopyresampled($out, $tiny, 0, 0, 0, 0, 160, 160, 18, 18);
            imagedestroy($tiny);

            if (defined('IMG_FILTER_GAUSSIAN_BLUR')) {
                for ($i = 0; $i < 6; $i++) {
                    imagefilter($out, IMG_FILTER_GAUSSIAN_BLUR);
                }
            }

            ob_start();
            imagejpeg($out, null, 72);
            $result = ob_get_clean();
            imagedestroy($out);

            return is_string($result) && $result !== '' ? $result : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function buildLockedSilhouetteSvg(bool $unlocked): string
    {
        $bg = $unlocked ? '#1e293b' : '#0f172a';
        $fg = $unlocked ? '#fbbf24' : '#64748b';

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" width="160" height="160">
  <rect width="160" height="160" rx="20" fill="{$bg}"/>
  <circle cx="80" cy="58" r="28" fill="{$fg}" opacity="0.45"/>
  <path d="M32 140c6-26 24-40 48-40s42 14 48 40" fill="{$fg}" opacity="0.45"/>
</svg>
SVG;
    }
}
