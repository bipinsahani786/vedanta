<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\EmployerProfile;
use App\Models\User;
use App\Notifications\EmployerAgreementUploaded;
use App\Services\EmployerAgreementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    public function __construct(private readonly EmployerAgreementService $agreementService)
    {
    }

    public function downloadTemplate()
    {
        $user = auth()->user();
        $profile = EmployerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'school_name' => $user->name,
                'contact_person' => $user->name,
            ]
        );

        return $this->agreementService->buildTemplatePdfResponse($user, $profile);
    }

    public function downloadUploaded(Request $request)
    {
        $user = auth()->user();
        $profile = EmployerProfile::where('user_id', $user->id)->first();

        if (!$profile || empty($profile->agreement_path) || !Storage::disk('public')->exists($profile->agreement_path)) {
            return back()->with('error', 'No uploaded agreement file found.');
        }

        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return $this->agreementService->streamUploadedAgreement($profile, $disposition);
    }

    public function upload(Request $request)
    {
        if (!Schema::hasColumn('employer_profiles', 'agreement_path')) {
            return back()->with('error', 'Agreement database columns are missing. Please contact support to run migrations.');
        }

        $user = auth()->user();
        $profile = EmployerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'school_name' => $user->name,
                'contact_person' => $user->name,
            ]
        );

        if ($profile->agreement_status === 'approved' && ! empty($profile->agreement_path) && Storage::disk('public')->exists($profile->agreement_path)) {
            return redirect()
                ->route('employer.dashboard')
                ->with('error', 'Your institution agreement is already approved and cannot be replaced.');
        }

        $request->validate([
            'agreement_file' => 'required|file|extensions:pdf,jpg,jpeg,png|max:10240',
        ], [
            'agreement_file.required' => 'Please select a signed agreement file (PDF, JPG, or PNG).',
            'agreement_file.extensions' => 'Only PDF, JPG, JPEG, or PNG files are accepted.',
            'agreement_file.max' => 'Agreement file must not exceed 10MB.',
        ]);

        $newPath = $request->file('agreement_file')->store('employer_agreements', 'public');
        $oldPath = $profile->agreement_path;

        $profile->update([
            'agreement_path' => $newPath,
            'agreement_status' => 'pending',
            'agreement_uploaded_at' => now(),
            'agreement_reviewed_at' => null,
            'agreement_reviewed_by' => null,
            'agreement_rejection_reason' => null,
        ]);

        if ($oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        try {
            User::where('role', 'admin')->get()->each(function (User $admin) use ($user, $profile): void {
                $admin->notify(new EmployerAgreementUploaded($user, $profile));
            });
        } catch (\Throwable $e) {
            Log::warning('Failed to notify admins about employer agreement upload: ' . $e->getMessage());
        }

        return redirect()
            ->route('employer.dashboard')
            ->with('success', 'Signed institution agreement uploaded successfully and submitted for Admin approval.');
    }
}
