<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SchoolSharingFileMail;
use App\Models\SchoolSharingCategory;
use App\Models\SchoolSharingFile;
use App\Models\SchoolSharingFileCandidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class SchoolSharingFileController extends Controller
{
    public function isFeatureReady(): bool
    {
        return Schema::hasTable('school_sharing_files')
            && Schema::hasTable('school_sharing_categories')
            && Schema::hasTable('school_sharing_file_candidates');
    }

    public function index(Request $request)
    {
        $schools = User::where('role', 'employer')
            ->with('employerProfile')
            ->orderBy('name')
            ->get();

        if (! $this->isFeatureReady()) {
            return view('admin.sharing-files.index', [
                'featureReady' => false,
                'files' => new LengthAwarePaginator([], 0, 15),
                'schools' => $schools,
            ]);
        }

        $query = SchoolSharingFile::with([
            'school.employerProfile',
            'categories' => fn ($q) => $q->withCount('entries'),
        ])->withCount(['categories', 'entries']);

        if ($schoolId = $request->input('school_user_id')) {
            $query->where('school_user_id', (int) $schoolId);
        }

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('school', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('employerProfile', fn ($ep) => $ep->where('school_name', 'like', "%{$search}%"));
                    })
                    ->orWhereHas('categories', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $files = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.sharing-files.index', [
            'featureReady' => true,
            'files' => $files,
            'schools' => $schools,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($this->isFeatureReady(), 503);

        $validated = $request->validate([
            'school_user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file_password' => 'nullable|string|min:4|max:50',
            'separate_password_share' => 'nullable|boolean',
            'initial_categories' => 'nullable|string|max:1000',
        ]);

        $school = User::where('role', 'employer')->findOrFail($validated['school_user_id']);

        $file = DB::transaction(function () use ($request, $validated, $school) {
            $file = new SchoolSharingFile([
                'school_user_id' => $school->id,
                'created_by' => auth()->id(),
                'title' => trim($validated['title']),
                'description' => ! empty($validated['description']) ? trim($validated['description']) : null,
                'share_token' => Str::random(40),
                'is_active' => true,
                'separate_password_share' => $request->boolean('separate_password_share'),
                'allow_qualifications' => $request->boolean('allow_qualifications', true),
                'allow_date_of_birth' => $request->boolean('allow_date_of_birth', true),
                'allow_current_salary' => $request->boolean('allow_current_salary', true),
                'allow_expected_salary' => $request->boolean('allow_expected_salary', true),
                'allow_experience' => $request->boolean('allow_experience', true),
                'allow_availability' => $request->boolean('allow_availability', true),
                'allow_cv_view' => $request->boolean('allow_cv_view', true),
                'allow_photo' => $request->boolean('allow_photo', false),
                'allow_contact' => $request->boolean('allow_contact', false),
            ]);

            if (! empty($validated['file_password'])) {
                $file->setFilePassword($validated['file_password']);
            }
            $file->save();

            $categoryNames = array_filter(array_unique(array_map(
                'trim',
                explode(',', (string) ($validated['initial_categories'] ?? ''))
            )));

            $sort = 1;
            foreach ($categoryNames as $catName) {
                SchoolSharingCategory::create([
                    'sharing_file_id' => $file->id,
                    'name' => Str::limit($catName, 120, ''),
                    'sort_order' => $sort++,
                ]);
            }

            return $file;
        });

        return redirect()
            ->route('admin.sharing-files.show', $file->id)
            ->with('success', 'School sharing file created successfully.');
    }

    public function show(Request $request, $id)
    {
        abort_unless($this->isFeatureReady(), 503);

        $sharingFile = SchoolSharingFile::with([
            'school.employerProfile',
            'creator',
            'categories' => fn ($q) => $q->withCount('entries'),
        ])->findOrFail($id);

        $selectedCategoryId = $request->input('category_id');
        if ($selectedCategoryId === null && $sharingFile->categories->isNotEmpty()) {
            $selectedCategoryId = $sharingFile->categories->first()->id;
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

        $entries = $entriesQuery->get();

        // Build safe sharing URLs & WhatsApp messages (Combined OR Safer Separate-Password mode)
        $schoolName = $sharingFile->school?->employerProfile?->school_name ?: ($sharingFile->school?->name ?? 'School');
        $shareUrl = $sharingFile->shareUrl();
        $plainPassword = $sharingFile->decryptSharePassword();
        $waPhone = preg_replace('/[^0-9]/', '', (string) ($sharingFile->school?->phone ?? ''));
        if (strlen($waPhone) === 10) {
            $waPhone = '91' . $waPhone;
        }
        $waBase = $waPhone !== '' ? "https://wa.me/{$waPhone}" : 'https://wa.me/';

        $waMessageCombined = "Hello {$schoolName},\n\nYour candidate sharing file *{$sharingFile->title}* from Vedanta Placement Agency is ready.\nOpen File Link: {$shareUrl}";
        if ($sharingFile->hasPassword() && $plainPassword) {
            $waMessageCombined .= "\nAccess Password: {$plainPassword}";
        }

        $waMessageLinkOnly = "Hello {$schoolName},\n\nYour candidate sharing file *{$sharingFile->title}* from Vedanta Placement Agency is ready.\nOpen File Link: {$shareUrl}";
        if ($sharingFile->hasPassword()) {
            $waMessageLinkOnly .= "\n(Password-protected: access password will be shared separately for security.)";
        }

        $waMessagePasswordOnly = $sharingFile->hasPassword() && $plainPassword
            ? "Access Password for *{$sharingFile->title}* ({$schoolName}): {$plainPassword}"
            : null;

        $waUrlCombined = $waBase . '?text=' . urlencode($waMessageCombined);
        $waUrlLinkOnly = $waBase . '?text=' . urlencode($waMessageLinkOnly);
        $waUrlPasswordOnly = $waMessagePasswordOnly ? ($waBase . '?text=' . urlencode($waMessagePasswordOnly)) : null;

        return view('admin.sharing-files.show', compact(
            'sharingFile',
            'selectedCategory',
            'entries',
            'schoolName',
            'shareUrl',
            'plainPassword',
            'waMessageCombined',
            'waMessageLinkOnly',
            'waMessagePasswordOnly',
            'waUrlCombined',
            'waUrlLinkOnly',
            'waUrlPasswordOnly'
        ));
    }

    public function update(Request $request, $id)
    {
        abort_unless($this->isFeatureReady(), 503);

        $sharingFile = SchoolSharingFile::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'nullable|boolean',
        ]);

        $sharingFile->update([
            'title' => trim($validated['title']),
            'description' => ! empty($validated['description']) ? trim($validated['description']) : null,
            'is_active' => $request->boolean('is_active'),
            'separate_password_share' => $request->boolean('separate_password_share'),
            'allow_qualifications' => $request->boolean('allow_qualifications'),
            'allow_date_of_birth' => $request->boolean('allow_date_of_birth'),
            'allow_current_salary' => $request->boolean('allow_current_salary'),
            'allow_expected_salary' => $request->boolean('allow_expected_salary'),
            'allow_experience' => $request->boolean('allow_experience'),
            'allow_availability' => $request->boolean('allow_availability'),
            'allow_cv_view' => $request->boolean('allow_cv_view'),
            'allow_photo' => $request->boolean('allow_photo'),
            'allow_contact' => $request->boolean('allow_contact'),
        ]);

        return back()->with('success', 'Sharing file settings & field permissions updated successfully.');
    }

    public function updatePassword(Request $request, $id)
    {
        abort_unless($this->isFeatureReady(), 503);

        $sharingFile = SchoolSharingFile::findOrFail($id);
        $action = $request->input('password_action', 'set');

        if ($action === 'remove') {
            $sharingFile->setFilePassword(null);
            $sharingFile->separate_password_share = $request->boolean('separate_password_share');
            $sharingFile->save();

            return back()->with('success', 'File password removed cleanly. Anyone with the link can now open this file.');
        }

        $validated = $request->validate([
            'file_password' => 'required|string|min:4|max:50',
        ], [
            'file_password.required' => 'Please enter a password (minimum 4 characters).',
            'file_password.min' => 'File password must be at least 4 characters.',
        ]);

        $sharingFile->setFilePassword($validated['file_password']);
        $sharingFile->separate_password_share = $request->boolean('separate_password_share');
        $sharingFile->save();

        return back()->with('success', 'File password updated successfully. Any previously unlocked browser sessions now require the new password.');
    }

    public function shareEmail(Request $request, $id)
    {
        abort_unless($this->isFeatureReady(), 503);

        $sharingFile = SchoolSharingFile::with('school.employerProfile')->findOrFail($id);

        $validated = $request->validate([
            'recipient_email' => 'required|email|max:255',
            'custom_note' => 'nullable|string|max:1000',
            'include_password' => 'nullable|boolean',
        ]);

        $schoolName = $sharingFile->school?->employerProfile?->school_name ?: ($sharingFile->school?->name ?? 'School');
        $includePassword = $request->boolean('include_password');
        $plainPassword = ($includePassword && $sharingFile->hasPassword())
            ? $sharingFile->decryptSharePassword()
            : null;

        try {
            Mail::to($validated['recipient_email'])->send(new SchoolSharingFileMail(
                $sharingFile,
                $schoolName,
                $plainPassword,
                ! empty($validated['custom_note']) ? trim($validated['custom_note']) : null
            ));

            $sharingFile->update([
                'last_shared_at' => now(),
                'last_shared_email' => $validated['recipient_email'],
            ]);

            $pwdNote = $sharingFile->hasPassword()
                ? ($plainPassword ? ' (password included in email)' : ' (safer mode: link sent without password)')
                : '';

            return back()->with('success', "Sharing file link emailed successfully to {$validated['recipient_email']}{$pwdNote}.");
        } catch (Throwable $e) {
            Log::error('Failed sending school sharing file email', [
                'sharing_file_id' => $sharingFile->id,
                'recipient' => $validated['recipient_email'],
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to send sharing email: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        abort_unless($this->isFeatureReady(), 503);

        $sharingFile = SchoolSharingFile::findOrFail($id);
        $title = $sharingFile->title;
        $sharingFile->delete();

        return redirect()
            ->route('admin.sharing-files.index')
            ->with('success', "Sharing file '{$title}' deleted permanently.");
    }

    public function storeCategory(Request $request, $id)
    {
        abort_unless($this->isFeatureReady(), 503);

        $sharingFile = SchoolSharingFile::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:120',
        ]);

        $catName = trim($validated['name']);
        $exists = SchoolSharingCategory::where('sharing_file_id', $sharingFile->id)
            ->whereRaw('LOWER(name) = ?', [strtolower($catName)])
            ->first();

        if ($exists) {
            return redirect()
                ->route('admin.sharing-files.show', ['id' => $sharingFile->id, 'category_id' => $exists->id])
                ->with('error', "Category '{$catName}' already exists in this file.");
        }

        $nextOrder = ((int) SchoolSharingCategory::where('sharing_file_id', $sharingFile->id)->max('sort_order')) + 1;
        $cat = SchoolSharingCategory::create([
            'sharing_file_id' => $sharingFile->id,
            'name' => $catName,
            'sort_order' => $nextOrder,
        ]);

        return redirect()
            ->route('admin.sharing-files.show', ['id' => $sharingFile->id, 'category_id' => $cat->id])
            ->with('success', "Category '{$catName}' created inside sharing file.");
    }

    public function destroyCategory($id, $categoryId)
    {
        abort_unless($this->isFeatureReady(), 503);

        $category = SchoolSharingCategory::where('sharing_file_id', $id)->findOrFail($categoryId);
        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.sharing-files.show', $id)
            ->with('success', "Category '{$name}' and its assignments removed from file.");
    }

    public function moveCandidateCategory(Request $request, $id, $entryId)
    {
        abort_unless($this->isFeatureReady(), 503);

        $validated = $request->validate([
            'target_category_id' => 'required|integer',
        ]);

        $entry = SchoolSharingFileCandidate::where('sharing_file_id', $id)->findOrFail($entryId);
        $targetCat = SchoolSharingCategory::where('sharing_file_id', $id)->findOrFail($validated['target_category_id']);

        if ((int) $entry->category_id === (int) $targetCat->id) {
            return back();
        }

        $alreadyInTarget = SchoolSharingFileCandidate::where('category_id', $targetCat->id)
            ->where('candidate_user_id', $entry->candidate_user_id)
            ->first();

        if ($alreadyInTarget) {
            $entry->delete();
        } else {
            $entry->update(['category_id' => $targetCat->id]);
        }

        return redirect()
            ->route('admin.sharing-files.show', ['id' => $id, 'category_id' => $targetCat->id])
            ->with('success', "Candidate moved to '{$targetCat->name}'.");
    }

    public function removeCandidate($id, $entryId)
    {
        abort_unless($this->isFeatureReady(), 503);

        $entry = SchoolSharingFileCandidate::where('sharing_file_id', $id)->findOrFail($entryId);
        $categoryId = $entry->category_id;
        $entry->delete();

        return redirect()
            ->route('admin.sharing-files.show', ['id' => $id, 'category_id' => $categoryId])
            ->with('success', 'Candidate removed from category.');
    }
}
