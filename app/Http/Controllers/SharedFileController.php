<?php

namespace App\Http\Controllers;

use App\Models\SchoolSharingFile;
use App\Models\SchoolSharingFileCandidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SharedFileController extends Controller
{
    public function show(Request $request, string $token)
    {
        abort_unless(Schema::hasTable('school_sharing_files'), 404);

        $sharingFile = SchoolSharingFile::where('share_token', $token)
            ->with([
                'school.employerProfile',
                'categories' => fn ($q) => $q->withCount('entries'),
            ])
            ->firstOrFail();

        if (! $sharingFile->is_active) {
            abort(403, 'This shared candidate file has been deactivated by the administrator.');
        }

        $schoolName = $sharingFile->school?->employerProfile?->school_name ?: ($sharingFile->school?->name ?? 'School');

        if ($sharingFile->hasPassword() && ! $this->isSessionUnlocked($request, $sharingFile)) {
            return response()
                ->view('shared.unlock', compact('sharingFile', 'schoolName'))
                ->header('Cache-Control', 'private, no-store, max-age=0');
        }

        $countedKey = "shared_file_counted:{$sharingFile->id}";
        if (! $request->session()->has($countedKey)) {
            $sharingFile->increment('views_count');
            $request->session()->put($countedKey, true);
        }

        $selectedCategoryId = $request->input('category_id');
        if ($selectedCategoryId === null && $sharingFile->categories->isNotEmpty()) {
            $firstWithEntries = $sharingFile->categories->first(fn ($c) => $c->entries_count > 0);
            $selectedCategoryId = $firstWithEntries?->id ?? $sharingFile->categories->first()->id;
        }

        $selectedCategory = $selectedCategoryId
            ? $sharingFile->categories->firstWhere('id', (int) $selectedCategoryId)
            : null;

        $entriesQuery = SchoolSharingFileCandidate::where('sharing_file_id', $sharingFile->id)
            ->with([
                'category',
                'candidate.profile.category',
                'candidate.profile.subject',
                'candidate.profile.highestQualification',
                'candidate.profile.preferredState',
                'candidate.profile.preferredCity',
            ])
            ->latest('id');

        if ($selectedCategory) {
            $entriesQuery->where('category_id', $selectedCategory->id);
        }

        $entries = $entriesQuery->get()->filter(fn ($e) => $e->candidate !== null);

        $cards = $entries->map(function (SchoolSharingFileCandidate $entry) use ($sharingFile) {
            $cand = $entry->candidate;
            $p = $cand->profile;

            $photoPath = $p?->profile_photo_path ?: ($p?->live_photo_path ?: $p?->passport_photo_path);
            $hasPhoto = $sharingFile->allow_photo && $photoPath && Storage::disk('public')->exists($photoPath);
            $hasCv = $sharingFile->allow_cv_view && ! empty($p?->resume_path) && Storage::disk('public')->exists($p->resume_path);
            $cvExt = $hasCv ? strtolower(pathinfo((string) $p->resume_path, PATHINFO_EXTENSION) ?: 'pdf') : null;

            $qualParts = array_filter([
                $p?->highestQualification?->name,
                ! empty($p?->other_qualifications) ? $p->other_qualifications : null,
            ]);

            return [
                'candidate_id' => $cand->id,
                'vpa_id' => $p?->vpa_id ?: ('VPA-CAN-' . str_pad((string) $cand->id, 4, '0', STR_PAD_LEFT)),
                'name' => $cand->name,
                'category_label' => $entry->category?->name ?? ($p?->category?->name ?? 'General'),
                'profile_category' => $p?->category?->name ?? 'General',
                'subject' => $p?->subject?->name ?? 'General',
                'is_verified' => (bool) ($p?->is_verified ?? false),
                'photo_url' => $hasPhoto ? route('shared-file.photo', ['token' => $sharingFile->share_token, 'candidate' => $cand->id]) : null,
                'qualifications' => $sharingFile->allow_qualifications
                    ? (! empty($qualParts) ? implode(' · ', $qualParts) : 'Not Provided')
                    : null,
                'date_of_birth' => $sharingFile->allow_date_of_birth
                    ? ($p?->date_of_birth ? $p->date_of_birth->format('d M Y') : 'Not Provided')
                    : null,
                'current_salary' => $sharingFile->allow_current_salary
                    ? ($p?->current_salary ?: 'Not Disclosed')
                    : null,
                'expected_salary' => $sharingFile->allow_expected_salary
                    ? ($p?->expected_salary ?: 'Not Disclosed')
                    : null,
                'experience_years' => $sharingFile->allow_experience ? (int) ($p?->experience_years ?? 0) : null,
                'availability' => $sharingFile->allow_availability ? ($p?->availability_to_join ?: 'On Inquiry') : null,
                'location' => trim(($p?->preferredCity?->name ?? '') . ($p?->preferredState?->name ? ', ' . $p->preferredState->name : ''), ', ') ?: 'Flexible',
                'email' => $sharingFile->allow_contact ? $cand->email : null,
                'phone' => $sharingFile->allow_contact ? $cand->phone : null,
                'can_view_cv' => $hasCv,
                'cv_url' => $hasCv ? route('shared-file.cv', ['token' => $sharingFile->share_token, 'candidate' => $cand->id]) : null,
                'cv_ext' => $cvExt,
                'admin_note' => $entry->admin_note,
            ];
        })->values();

        return response()
            ->view('shared.file', compact('sharingFile', 'schoolName', 'selectedCategory', 'cards'))
            ->header('Cache-Control', 'private, no-store, max-age=0');
    }

    public function unlock(Request $request, string $token)
    {
        abort_unless(Schema::hasTable('school_sharing_files'), 404);

        $sharingFile = SchoolSharingFile::where('share_token', $token)->firstOrFail();

        if (! $sharingFile->is_active) {
            abort(403, 'This shared candidate file is inactive.');
        }

        if (! $sharingFile->hasPassword()) {
            return redirect()->route('shared-file.show', $token);
        }

        $validated = $request->validate([
            'password' => 'required|string',
        ]);

        if (! $sharingFile->verifyPassword($validated['password'])) {
            return back()->withErrors([
                'password' => 'Incorrect password. Please check the password provided by Vedanta Placement Agency.',
            ]);
        }

        $request->session()->put("shared_file_unlocked:{$sharingFile->id}", $sharingFile->unlockSessionDigest());

        return redirect()->route('shared-file.show', $token);
    }

    public function lock(Request $request, string $token)
    {
        $sharingFile = SchoolSharingFile::where('share_token', $token)->first();
        if ($sharingFile) {
            $request->session()->forget("shared_file_unlocked:{$sharingFile->id}");
        }

        return redirect()->route('shared-file.show', $token);
    }

    public function viewCv(Request $request, string $token, int $candidate): Response
    {
        abort_unless(Schema::hasTable('school_sharing_files'), 404);

        $sharingFile = SchoolSharingFile::where('share_token', $token)->firstOrFail();
        abort_unless($sharingFile->is_active, 403);
        abort_unless(! $sharingFile->hasPassword() || $this->isSessionUnlocked($request, $sharingFile), 403, 'File is password-locked.');
        abort_unless($sharingFile->allow_cv_view, 403, 'CV viewing is disabled for this file.');

        $assigned = SchoolSharingFileCandidate::where('sharing_file_id', $sharingFile->id)
            ->where('candidate_user_id', $candidate)
            ->exists();
        abort_unless($assigned, 404);

        $candidateUser = User::where('role', 'candidate')->with('profile')->findOrFail($candidate);
        $path = (string) ($candidateUser->profile?->resume_path ?? '');
        abort_if($path === '' || ! Storage::disk('public')->exists($path), 404, 'CV file not found.');

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf');
        $mime = match ($ext) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => 'application/octet-stream',
        };

        // Always stream inline with no-store headers; never Content-Disposition: attachment.
        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="cv_view_only.' . $ext . '"',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function viewPhoto(Request $request, string $token, int $candidate): Response
    {
        abort_unless(Schema::hasTable('school_sharing_files'), 404);

        $sharingFile = SchoolSharingFile::where('share_token', $token)->firstOrFail();
        abort_unless($sharingFile->is_active, 403);
        abort_unless(! $sharingFile->hasPassword() || $this->isSessionUnlocked($request, $sharingFile), 403);
        abort_unless($sharingFile->allow_photo, 403);

        $assigned = SchoolSharingFileCandidate::where('sharing_file_id', $sharingFile->id)
            ->where('candidate_user_id', $candidate)
            ->exists();
        abort_unless($assigned, 404);

        $candidateUser = User::where('role', 'candidate')->with('profile')->findOrFail($candidate);
        $p = $candidateUser->profile;
        $path = (string) ($p?->profile_photo_path ?: ($p?->live_photo_path ?: ($p?->passport_photo_path ?? '')));
        abort_if($path === '' || ! Storage::disk('public')->exists($path), 404);

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'jpeg');
        $mime = match ($ext) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    private function isSessionUnlocked(Request $request, SchoolSharingFile $sharingFile): bool
    {
        $sessionDigest = (string) $request->session()->get("shared_file_unlocked:{$sharingFile->id}", '');

        return $sessionDigest !== '' && hash_equals($sharingFile->unlockSessionDigest(), $sessionDigest);
    }
}
