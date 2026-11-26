<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Candidate\AgreementController;
use App\Http\Controllers\Controller;
use App\Jobs\SendCandidateBulkNotificationChunkJob;
use App\Mail\AgreementLinkMail;
use App\Mail\CandidateReminderMail;
use App\Mail\DynamicTemplateMail;
use App\Mail\InvoiceUpdatedMail;
use App\Mail\PaymentReceiptMail;
use App\Mail\PaymentReminderMail;
use App\Mail\ProfileApprovedMail;
use App\Mail\RegistrationSuccessMail;
use App\Mail\ServiceChargeInvoiceMail;
use App\Mail\TemporaryPasswordMail;
use App\Models\CandidateProfile;
use App\Models\CandidateRating;
use App\Models\Category;
use App\Models\City;
use App\Models\CrmFollowUp;
use App\Models\EmailTemplate;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\PaymentTransaction;
use App\Models\Qualification;
use App\Models\ServiceChargeInvoice;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use App\Services\PaymentFulfillmentService;
use App\Services\ReferralService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CrmController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $subjects = Subject::all();
        $qualifications = Qualification::all();
        $states = State::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();

        return view('admin.crm.create', compact('categories', 'subjects', 'qualifications', 'states', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'highest_qualification_id' => 'required|exists:qualifications,id',
            'experience_years' => 'required|integer|min:0',
            'current_salary' => 'nullable|string',
            'expected_salary' => 'nullable|string',
            'preferred_state_id' => 'required|exists:states,id',
            'preferred_city_id' => 'required|exists:cities,id',
            'english_fluency' => 'nullable|string',
            'residential_preference' => 'nullable|string',
            'availability_to_join' => 'nullable|string',
            'current_school' => 'nullable|string',
            'plan_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_amount' => 'required|numeric|min:0',
            'payment_notes' => 'nullable|string',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'profile_photo' => 'nullable|image|max:5120',
            'live_photo' => 'nullable|image|max:5120',
            'salary_slip' => 'nullable|mimes:pdf,jpg,png,jpeg|max:5120',
            'offer_letter' => 'nullable|mimes:pdf,jpg,png,jpeg|max:5120',
            'agreement_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        try {
            // Handle File Uploads first (outside DB transaction — filesystem operations cannot be rolled back)
            $resumePath       = $request->hasFile('resume')        ? $request->file('resume')->store('resumes', 'public')              : null;
            $profilePhotoPath = $request->hasFile('profile_photo') ? $request->file('profile_photo')->store('profile_photos', 'public') : null;
            $livePhotoPath    = $request->hasFile('live_photo')    ? $request->file('live_photo')->store('live_photos', 'public')       : null;
            $salarySlipPath   = $request->hasFile('salary_slip')   ? $request->file('salary_slip')->store('salary_slips', 'public')    : null;
            $offerLetterPath  = $request->hasFile('offer_letter')  ? $request->file('offer_letter')->store('offer_letters', 'public')  : null;
            $agreementPdfPath = $request->hasFile('agreement_pdf') ? $request->file('agreement_pdf')->store('agreements', 'public')    : null;

            $otherQualsArray = is_array($request->other_qualifications) ? $request->other_qualifications : ($request->other_qualifications ? explode(',', $request->other_qualifications) : []);
            if ($request->filled('custom_qualification')) {
                $otherQualsArray[] = trim($request->custom_qualification);
            }
            $otherQualsStr = implode(', ', array_unique(array_filter(array_map('trim', $otherQualsArray))));

            // BUG FIX: Only mark fee as paid when actual money (amount > 0) was collected.
            // Previously this was always true, giving free access even on ₹0 entries.
            $isFeePaid = (float) $request->payment_amount > 0;
            $paymentId = $isFeePaid ? ($request->payment_method . '-ADMIN-' . strtoupper(uniqid())) : null;

            $user = null;

            // BUG FIX: Wrap all DB writes in a single transaction so a failure in any step
            // leaves no orphan User/Profile records in the database.
            DB::transaction(function () use (
                $request, &$user,
                $resumePath, $profilePhotoPath, $livePhotoPath,
                $salarySlipPath, $offerLetterPath, $agreementPdfPath,
                $otherQualsStr, $isFeePaid, $paymentId
            ) {
                // 1. Create User
                $user = User::create([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'phone'             => $request->phone,
                    'role'              => 'candidate',
                    'password'          => Hash::make($request->password),
                    'email_verified_at' => now(),
                ]);

                // 2. Create Candidate Profile
                CandidateProfile::create([
                    'user_id'                  => $user->id,
                    'gender'                   => $request->gender,
                    'date_of_birth'            => $request->date_of_birth,
                    'address'                  => $request->address,
                    'category_id'              => $request->category_id,
                    'subject_id'               => $request->subject_id,
                    'highest_qualification_id' => $request->highest_qualification_id,
                    'other_qualifications'     => $otherQualsStr,
                    'experience_years'         => $request->experience_years,
                    'current_salary'           => $request->current_salary,
                    'expected_salary'          => $request->expected_salary,
                    'preferred_state_id'       => $request->preferred_state_id,
                    'preferred_city_id'        => $request->preferred_city_id,
                    'english_fluency'          => $request->english_fluency,
                    'residential_preference'   => $request->residential_preference,
                    'availability_to_join'     => $request->availability_to_join,
                    'current_school'           => $request->current_school ?: 'Fresher',

                    'resume_path'        => $resumePath,
                    'profile_photo_path' => $profilePhotoPath,
                    'live_photo_path'    => $livePhotoPath,
                    'salary_slip_path'   => $salarySlipPath,
                    'offer_letter_path'  => $offerLetterPath,
                    'agreement_pdf_path' => $agreementPdfPath,

                    // Payment-aware fields: only truthy when money was actually collected
                    'is_profile_complete'        => true,
                    'is_fee_paid'                => $isFeePaid,
                    'paid_amount'                => $isFeePaid ? (float) $request->payment_amount : 0,
                    'plan_type'                  => $request->plan_type,
                    'total_allowed_applications' => $request->plan_type === 'standard' ? 2 : 3,
                    'plan_started_at'            => $isFeePaid ? now() : null,
                    'payment_id'                 => $paymentId,
                    'registration_completed_at'  => $isFeePaid ? now() : null,

                    'is_terms_agreed'     => true,
                    'is_agreement_signed' => $request->has('is_agreement_signed') ? $request->boolean('is_agreement_signed') : true,
                    'signature_date_time' => ($request->has('is_agreement_signed') ? $request->boolean('is_agreement_signed') : true) ? now() : null,
                ]);

                // 3. Create Payment Transaction ONLY when actual money was collected (amount > 0).
                // BUG FIX: Previously a ₹0 "success" transaction was always inserted.
                if ($isFeePaid) {
                    PaymentTransaction::create([
                        'candidate_id'     => $user->id,
                        'transaction_id'   => $paymentId,
                        'amount'           => $request->payment_amount,
                        'type'             => 'registration_fee',
                        'status'           => 'success',
                        'gateway_response' => [
                            'note'           => 'Manually collected by Admin',
                            'admin_notes'    => $request->payment_notes,
                            'payment_method' => $request->payment_method,
                        ],
                    ]);
                }
            });

            // 4. Send Welcome Email (outside transaction — mail failures must not roll back DB changes)
            try {
                Mail::to($user->email)->send(new RegistrationSuccessMail($user));
            } catch (Exception $mailException) {
                Log::error('Manual Onboard Mail Error: ' . $mailException->getMessage());
                // Proceed without breaking if email fails
            }

            $successMsg = $isFeePaid
                ? 'Candidate manually onboarded successfully and welcome email sent.'
                : 'Candidate profile created. Note: No payment recorded as amount was zero or not provided.';

            return redirect()->route('admin.crm.show', $user->id)->with('success', $successMsg);

        } catch (Exception $e) {
            Log::error('Manual Onboard Error: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Failed to onboard candidate: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $user = User::where('role', 'candidate')->findOrFail($id);
        $profile = $user->profile ?? new CandidateProfile;

        $categories = Category::all();
        $subjects = Subject::all();
        $qualifications = Qualification::all();
        $states = State::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();

        return view('admin.crm.edit', compact('user', 'profile', 'categories', 'subjects', 'qualifications', 'states', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'candidate')->findOrFail($id);
        $profile = $user->profile;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:20|unique:users,phone,'.$user->id,
            'password' => 'nullable|string|min:6',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'highest_qualification_id' => 'required|exists:qualifications,id',
            'experience_years' => 'required|integer|min:0',
            'current_salary' => 'nullable|string',
            'expected_salary' => 'nullable|string',
            'preferred_state_id' => 'required|exists:states,id',
            'preferred_city_id' => 'required|exists:cities,id',
            'english_fluency' => 'nullable|string',
            'residential_preference' => 'nullable|string',
            'availability_to_join' => 'nullable|string',
            'current_school' => 'nullable|string',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'profile_photo' => 'nullable|image|max:5120',
            'live_photo' => 'nullable|image|max:5120',
            'salary_slip' => 'nullable|mimes:pdf,jpg,png,jpeg|max:5120',
            'offer_letter' => 'nullable|mimes:pdf,jpg,png,jpeg|max:5120',
            'agreement_pdf' => 'nullable|mimes:pdf|max:5120',
            'plan_type' => 'nullable|in:standard,premium',
            'payment_method' => 'nullable|in:CASH,ONLINE_TRANSFER,CHEQUE,SPLIT_PAYMENT',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_notes' => 'nullable|string',
        ]);

        try {
            // Update User
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            $user->update($userData);

            if ($request->filled('password')) {
                if ($request->boolean('require_password_change', true)) {
                    $user->issueTemporaryPassword($request->password);
                } else {
                    $user->completePasswordUpdate($request->password);
                }
            }

            $otherQualsArray = is_array($request->other_qualifications) ? $request->other_qualifications : ($request->other_qualifications ? explode(',', $request->other_qualifications) : []);
            if ($request->filled('custom_qualification')) {
                $otherQualsArray[] = trim($request->custom_qualification);
            }

            // Handle File Uploads (only update if a new file is provided)
            $updates = [
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'category_id' => $request->category_id,
                'subject_id' => $request->subject_id,
                'highest_qualification_id' => $request->highest_qualification_id,
                'other_qualifications' => implode(', ', array_unique(array_filter(array_map('trim', $otherQualsArray)))),
                'experience_years' => $request->experience_years,
                'current_salary' => $request->current_salary,
                'expected_salary' => $request->expected_salary,
                'preferred_state_id' => $request->preferred_state_id,
                'preferred_city_id' => $request->preferred_city_id,
                'english_fluency' => $request->english_fluency,
                'residential_preference' => $request->residential_preference,
                'availability_to_join' => $request->availability_to_join,
                'current_school' => $request->filled('current_school') ? $request->current_school : ($profile?->current_school ?: 'Fresher'),
            ];

            // BUG FIX: Pre-compute whether a payment is being collected in this same request.
            // This is used below to guard registration_completed_at against being set without a payment.
            $isPayingNow = ! ($profile ? $profile->is_fee_paid : false)
                && $request->filled('payment_amount')
                && (float) $request->payment_amount > 0
                && $request->filled('payment_method')
                && $request->filled('plan_type');

            // Determine if profile should be marked complete:
            // Explicitly checked by Admin OR mandatory core fields are filled
            $hasSubmittedFullDetails = ! empty($request->date_of_birth) 
                && ! empty($request->address) 
                && ! empty($request->highest_qualification_id);

            $isComplete = $request->boolean('is_profile_complete') 
                || ($hasSubmittedFullDetails && ! empty($request->category_id) && ! empty($request->subject_id));

            $updates['is_profile_complete'] = $isComplete;

            // BUG FIX: Only stamp registration_completed_at when profile details are complete AND fee is already paid OR is being
            // paid right now. Previously it was stamped on every profile save, bypassing the payment gate.
            $feeIsPaidOrBeingPaid = ($profile && $profile->is_fee_paid) || $isPayingNow;
            if ($isComplete && $feeIsPaidOrBeingPaid && (! $profile || empty($profile->registration_completed_at))) {
                $updates['registration_completed_at'] = now();
            }

            // Agreement status
            if ($request->has('is_agreement_signed')) {
                $updates['is_agreement_signed'] = $request->boolean('is_agreement_signed');
                if ($updates['is_agreement_signed']) {
                    $updates['is_terms_agreed'] = true;
                    $updates['signature_date_time'] = $profile?->signature_date_time ?? now();
                }
            }

            if ($request->has('plan_type')) {
                $updates['plan_type'] = $request->plan_type;
                $updates['total_allowed_applications'] = $request->plan_type === 'standard' ? 2 : 3;
                // BUG FIX: is_fee_paid is intentionally NOT set here.
                // It is only set via the manual payment block below when actual money (amount > 0) is collected.
                // Previously selecting Premium in the dropdown alone would mark fee as paid with no payment.
            }

            if ($request->hasFile('resume')) {
                $updates['resume_path'] = $request->file('resume')->store('resumes', 'public');
            }
            if ($request->hasFile('profile_photo')) {
                $updates['profile_photo_path'] = $request->file('profile_photo')->store('profile_photos', 'public');
            }
            if ($request->hasFile('live_photo')) {
                $updates['live_photo_path'] = $request->file('live_photo')->store('live_photos', 'public');
            }
            if ($request->hasFile('salary_slip')) {
                $updates['salary_slip_path'] = $request->file('salary_slip')->store('salary_slips', 'public');
            }
            if ($request->hasFile('offer_letter')) {
                $updates['offer_letter_path'] = $request->file('offer_letter')->store('offer_letters', 'public');
            }
            if ($request->hasFile('agreement_pdf')) {
                $updates['agreement_pdf_path'] = $request->file('agreement_pdf')->store('agreements', 'public');
                $updates['is_agreement_signed'] = true;
                $updates['is_terms_agreed'] = true;
                $updates['signature_date_time'] = $profile?->signature_date_time ?? now();
            }

            // Handle Manual Payment Collection
            $isFeePaid = $profile ? $profile->is_fee_paid : false;

            // BUG FIX: Added (float) $request->payment_amount > 0 guard.
            // Previously a ₹0 amount with filled fields would also create a fake success transaction.
            if (! $isFeePaid && $request->filled('payment_amount') && (float) $request->payment_amount > 0 && $request->filled('payment_method') && $request->filled('plan_type')) {
                $paymentId = $request->payment_method . '-ADMIN-' . strtoupper(uniqid());

                $updates['initial_fee_paid']           = true;
                $updates['is_fee_paid']                = true;
                $updates['paid_amount']                = (float) $request->payment_amount;
                $updates['plan_type']                  = $request->plan_type;
                $updates['total_allowed_applications'] = $request->plan_type === 'standard' ? 2 : 3;
                $updates['plan_started_at']            = now();
                $updates['payment_id']                 = $paymentId;
                if ($isComplete || ($profile && $profile->hasCompletedProfileDetails())) {
                    $updates['is_profile_complete']        = true;
                    $updates['registration_completed_at']  = now();
                }

                PaymentTransaction::create([
                    'candidate_id'     => $user->id,
                    'transaction_id'   => $paymentId,
                    'amount'           => (float) $request->payment_amount,
                    'type'             => 'registration_fee',
                    'status'           => 'success',
                    'gateway_response' => [
                        'note'           => 'Manually collected by Admin',
                        'admin_notes'    => $request->payment_notes,
                        'payment_method' => $request->payment_method,
                    ],
                ]);
            }

            if ($profile) {
                $profile->update($updates);
            } else {
                // BUG FIX: Added safe defaults for all required/important columns so a fresh profile
                // created via the edit route never has unexpected null values.
                $updates['user_id']             = $user->id;
                $updates['is_profile_complete'] = $updates['is_profile_complete'] ?? false;
                $updates['is_fee_paid']         = $updates['is_fee_paid'] ?? false;
                $updates['is_terms_agreed']     = $updates['is_terms_agreed'] ?? false;
                $updates['is_agreement_signed'] = $updates['is_agreement_signed'] ?? false;
                $updates['paid_amount']         = $updates['paid_amount'] ?? 0;
                $updates['pending_amount']      = $updates['pending_amount'] ?? 0;
                CandidateProfile::create($updates);
            }

            return redirect()->route('admin.crm.show', $user->id)->with('success', 'Candidate profile updated successfully.');

        } catch (Exception $e) {
            Log::error('Profile Update Error: '.$e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Failed to update candidate profile: '.$e->getMessage()]);
        }
    }

    /**
     * Build the filtered candidate query so list views and "Select All Matching" bulk actions stay identical.
     */
    protected function buildFilteredCandidatesQuery(Request $request, bool $applyStatusFilter = true)
    {
        $query = User::where('role', 'candidate');

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($pq) use ($search) {
                        $pq->where('vpa_id', 'like', "%{$search}%");
                    });

                $cleanNumeric = ltrim($search, '#');
                if (is_numeric($cleanNumeric)) {
                    $q->orWhere('users.id', (int) $cleanNumeric);
                }
            });
        }

        if ($subjectId = $request->input('subject_id')) {
            $query->whereHas('profile', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }

        if ($experience = $request->input('experience')) {
            $query->whereHas('profile', function ($q) use ($experience) {
                $q->where('experience_years', '>=', $experience);
            });
        }

        if ($qualificationId = $request->input('qualification_id')) {
            $query->whereHas('profile', function ($q) use ($qualificationId) {
                $q->where('highest_qualification_id', $qualificationId);
            });
        }

        if ($stateId = $request->input('state_id')) {
            $query->whereHas('profile', function ($q) use ($stateId) {
                $q->where('preferred_state_id', $stateId);
            });
        }

        if ($cityId = $request->input('city_id')) {
            $query->whereHas('profile', function ($q) use ($cityId) {
                $q->where('preferred_city_id', $cityId);
            });
        }

        if ($gender = $request->input('gender')) {
            $query->whereHas('profile', function ($q) use ($gender) {
                $q->where('gender', $gender);
            });
        }

        if ($englishFluency = $request->input('english_fluency')) {
            $query->whereHas('profile', function ($q) use ($englishFluency) {
                $q->where('english_fluency', $englishFluency);
            });
        }

        if ($availability = $request->input('availability')) {
            $query->whereHas('profile', function ($q) use ($availability) {
                $q->where('availability_to_join', $availability);
            });
        }

        if ($salary = $request->input('salary')) {
            $query->whereHas('profile', function ($q) use ($salary) {
                $q->where('expected_salary', 'like', "%{$salary}%")
                    ->orWhere('current_salary', 'like', "%{$salary}%");
            });
        }

        if ($planAmount = $request->input('plan_amount')) {
            $query->whereHas('profile', function ($q) use ($planAmount) {
                $q->where('paid_amount', $planAmount);
            });
        }

        if ($applyStatusFilter && ($status = $request->input('status'))) {
            $this->applyCandidateStatusFilter($query, (string) $status);
        }

        return $query;
    }

    protected function applyCandidateStatusFilter($query, string $status): void
    {
        match (strtolower(trim($status))) {
            'completed' => $query->whereHas('profile', fn ($q) => $q->registrationCompleted()),
            'pending' => $query->whereDoesntHave('profile', fn ($q) => $q->registrationCompleted()),
            'pending_agreement', 'unsigned_agreement' => $query->whereDoesntHave('profile', fn ($q) => $q->withSignedAgreement()),
            'pending_fee', 'unpaid_payment' => $query->whereDoesntHave('profile', fn ($q) => $q->withPaidPlan()),
            'pending_profile', 'incomplete_profile' => $query->whereDoesntHave('profile', fn ($q) => $q->withCompletedDetails()),
            'active_paid' => $query->whereHas('profile', fn ($q) => $q->where('is_fee_paid', true)),
            'signed' => $query->whereHas('profile', fn ($q) => $q->where('is_fee_paid', false)->where('is_agreement_signed', true)),
            'pending_dues' => $query->whereHas('profile', fn ($q) => $q->where('pending_amount', '>', 0)),
            'incomplete' => $query->whereDoesntHave('profile', function ($pq) {
                $pq->where('is_fee_paid', true)
                    ->orWhere(function ($q2) {
                        $q2->where('is_fee_paid', false)->where('is_agreement_signed', true);
                    });
            }),
            'verified' => $query->whereHas('profile', fn ($q) => $q->where('is_verified', true)),
            'unverified' => $query->whereDoesntHave('profile', fn ($q) => $q->where('is_verified', true)),
            'has_resume' => $query->whereHas('profile', fn ($q) => $q->whereNotNull('resume_path')->where('resume_path', '!=', '')),
            'missing_resume' => $query->whereDoesntHave('profile', fn ($q) => $q->whereNotNull('resume_path')->where('resume_path', '!=', '')),
            'active_account' => $query->where('users.is_active', true),
            'inactive_account' => $query->where('users.is_active', false),
            default => null,
        };
    }

    public function index(Request $request)
    {
        $baseQuery = $this->buildFilteredCandidatesQuery($request, false);

        // Analytics based on base query (before status filter)
        $onboardingSummary = CandidateProfile::onboardingSummary($baseQuery);
        $stats = [
            'total' => $onboardingSummary['total'],
            'onboarding_complete' => $onboardingSummary['completed'],
            'onboarding_pending' => $onboardingSummary['pending'],
            'pending_agreement' => $onboardingSummary['pending_agreement'],
            'pending_fee' => $onboardingSummary['pending_fee'],
            'pending_profile' => $onboardingSummary['pending_profile'],
            'active_paid' => (clone $baseQuery)->whereHas('profile', function ($q) {
                $q->where('is_fee_paid', true);
            })->count(),
            'signed' => (clone $baseQuery)->whereHas('profile', function ($q) {
                $q->where('is_fee_paid', false)->where('is_agreement_signed', true);
            })->count(),
            'pending_dues' => (clone $baseQuery)->whereHas('profile', function ($q) {
                $q->where('pending_amount', '>', 0);
            })->count(),
        ];
        $stats['incomplete'] = $stats['total'] - $stats['active_paid'] - $stats['signed'];

        $query = $this->buildFilteredCandidatesQuery($request, true)
            ->with([
                'profile.category',
                'profile.subject',
                'profile.specialization',
                'profile.highestQualification',
                'profile.preferredState',
                'profile.preferredCity',
                'referredBy',
                'latestFollowUp',
                'applications.jobPost',
                'applications' => function ($q) {
                    $q->where('status', 'hired');
                },
            ])
            ->withCount('openFollowUps');

        // Sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('order', 'desc');

        $allowedFields = ['id', 'name', 'email', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $candidates = $query->with('rating')->paginate(15)->withQueryString();

        // Pass master data for filters
        $subjects = Subject::all();
        $qualifications = Qualification::all();
        $states = State::all();
        $cities = City::all();
        $lastBulkBatch = session('crm_last_bulk_batch_id')
            ? Cache::get('crm_bulk_batch:' . session('crm_last_bulk_batch_id'))
            : null;

        return view('admin.crm.index', compact('candidates', 'stats', 'sortField', 'sortDirection', 'subjects', 'qualifications', 'states', 'cities', 'lastBulkBatch'));
    }

    public function show($id)
    {
        $candidate = User::where('role', 'candidate')
            ->with([
                'profile.category',
                'profile.subject',
                'profile.specialization',
                'profile.highestQualification',
                'profile.preferredState',
                'profile.preferredCity',
                'referredBy',
                'applications.jobPost',
            ])
            ->findOrFail($id);

        $followUps = CrmFollowUp::where('candidate_id', $id)
            ->with('admin')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
        $invoices = ServiceChargeInvoice::where('candidate_id', $id)->with('jobApplication.jobPost')->orderBy('created_at', 'desc')->get();
        $rating = CandidateRating::where('candidate_id', $id)->first();
        $payments = PaymentTransaction::where('candidate_id', $id)->where('status', 'success')->get();

        $availableJobs = JobPost::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $history = collect();

        // 1. Profile Creation
        $history->push([
            'date' => $candidate->created_at,
            'type' => 'profile_created',
            'title' => 'Profile Created',
            'description' => 'Candidate registered on the platform.',
            'icon' => 'fas fa-user-plus',
            'color' => 'bg-blue-500',
        ]);

        // 2. Payments
        foreach ($payments as $payment) {
            // BUG FIX: store() generates IDs like "CASH-ADMIN-XXXXX" (dash format), not "MANUAL_" prefix.
            // Both patterns must be recognised as manual/offline payments.
            $isManual = str_starts_with($payment->transaction_id ?? '', 'MANUAL_')
                || str_contains($payment->transaction_id ?? '', '-ADMIN-');
            $modeLabel = $isManual ? 'Manual / Offline' : 'Online / Auto';
            $isServiceCharge = ($payment->type === 'service_charge' || $payment->type === 'placement_fee' || str_contains($payment->transaction_id ?? '', 'SC_'));
            $title = $isServiceCharge ? "Service Charge Paid ({$modeLabel})" : "Payment Received ({$modeLabel})";

            $history->push([
                'date' => $payment->created_at,
                'type' => 'payment',
                'title' => $title,
                'description' => 'Payment of ₹'.number_format($payment->amount, 2)." recorded via {$modeLabel}. (Txn: {$payment->transaction_id})",
                'icon' => $isManual ? 'fas fa-user-check' : 'fas fa-globe',
                'color' => $isManual ? 'bg-purple-600' : 'bg-green-600',
            ]);
        }

        // 3. Job Applications
        foreach ($candidate->applications as $app) {
            $history->push([
                'date' => $app->created_at,
                'type' => 'job_applied',
                'title' => 'Applied for Job',
                'description' => 'Applied for '.($app->jobPost->title ?? 'a job').' at '.($app->jobPost->school_name ?? 'a school').'.',
                'icon' => 'fas fa-briefcase',
                'color' => 'bg-indigo-500',
            ]);

            if ($app->status === 'hired') {
                $history->push([
                    'date' => $app->updated_at,
                    'type' => 'job_hired',
                    'title' => 'Hired',
                    'description' => 'Candidate was hired for '.($app->jobPost->title ?? 'a job').'.',
                    'icon' => 'fas fa-check-circle',
                    'color' => 'bg-emerald-500',
                ]);
            } elseif ($app->status === 'waitlisted') {
                $history->push([
                    'date' => $app->updated_at,
                    'type' => 'job_waitlisted',
                    'title' => 'Waitlisted',
                    'description' => 'Candidate was waitlisted for '.($app->jobPost->title ?? 'a job').'.',
                    'icon' => 'fas fa-hourglass-half',
                    'color' => 'bg-amber-500',
                ]);
            }
        }

        // 4. Follow-ups
        foreach ($followUps as $fu) {
            $descParts = ['Notes: '.$fu->notes];
            if (! empty($fu->next_action)) {
                $descParts[] = 'Next Action: '.$fu->next_action;
            }
            if ($fu->scheduled_date_time) {
                $descParts[] = 'Scheduled: '.$fu->scheduled_date_time->copy()->timezone('Asia/Kolkata')->format('M d, Y h:i A').' IST';
            }
            $descParts[] = 'Status: '.ucfirst($fu->status);

            $history->push([
                'date' => $fu->created_at,
                'type' => 'follow_up',
                'title' => 'Follow-up Logged'.(! empty($fu->next_action) ? ' ('.$fu->next_action.')' : ''),
                'description' => implode(' · ', $descParts),
                'icon' => 'fas fa-phone-alt',
                'color' => $fu->status === 'closed' ? 'bg-green-500' : 'bg-yellow-500',
            ]);
        }

        // 5. Invoices
        foreach ($invoices as $invoice) {
            $history->push([
                'date' => $invoice->created_at,
                'type' => 'invoice_generated',
                'title' => 'Invoice Generated',
                'description' => 'Invoice for ₹'.number_format($invoice->amount, 2).' generated.',
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => 'bg-purple-500',
            ]);

            // Avoid duplicate entry if payment transaction is already recorded in history above
            $hasMatchingPayment = $payments->contains(function ($p) use ($invoice) {
                return $p->transaction_id === 'MANUAL_SC_'.$invoice->id || str_starts_with($p->transaction_id ?? '', 'SC_'.$invoice->id.'_');
            });

            if ($invoice->status === 'paid' && $invoice->payment_date && ! $hasMatchingPayment) {
                $history->push([
                    'date' => $invoice->payment_date,
                    'type' => 'invoice_paid',
                    'title' => 'Invoice Paid',
                    'description' => 'Service charge invoice for ₹'.number_format($invoice->amount, 2).' was paid.',
                    'icon' => 'fas fa-check-double',
                    'color' => 'bg-green-600',
                ]);
            }
        }

        $history = $history->sortByDesc('date')->values();
        $emailTemplates = EmailTemplate::orderBy('name', 'asc')->get();

        return view('admin.crm.show', compact('candidate', 'followUps', 'invoices', 'payments', 'rating', 'history', 'availableJobs', 'emailTemplates'));
    }

    public function uploadAgreement(Request $request, $id)
    {
        $request->validate([
            'agreement_pdf' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $candidate = User::findOrFail($id);
        $profile = $candidate->profile;

        if (! $profile) {
            return back()->with('error', 'Candidate profile not found.');
        }

        if ($request->hasFile('agreement_pdf')) {
            $file = $request->file('agreement_pdf');
            $fileName = 'agreements/admin_uploaded_'.$candidate->id.'_'.time().'.pdf';

            // Store the file in public disk
            Storage::disk('public')->put($fileName, file_get_contents($file));

            // Delete old uploaded manual file if it existed
            if ($profile->manual_agreement_path && Storage::disk('public')->exists($profile->manual_agreement_path) && $profile->manual_agreement_path !== $fileName) {
                Storage::disk('public')->delete($profile->manual_agreement_path);
            }

            // Update profile
            $profile->update([
                'is_agreement_signed' => true,
                'agreement_pdf_path' => $fileName,
                'manual_agreement_path' => $fileName,
                'agreement_signed_at' => now(),
            ]);

            // Ensure unique Candidate and Agreement IDs are linked
            $profile->ensureIdsAssigned();

            // Send notification / email with updated agreement to candidate
            try {
                Mail::to($candidate->email)->send(new RegistrationSuccessMail($candidate));
            } catch (Throwable $e) {
                Log::warning('Email delivery failed during manual agreement upload: '.$e->getMessage());
            }

            return back()->with('success', 'Agreement PDF uploaded successfully and sent to candidate.');
        }

        return back()->with('error', 'Failed to upload agreement.');
    }

    public function restoreAgreement($id)
    {
        $candidate = User::findOrFail($id);
        $profile = $candidate->profile;

        if (! $profile || ! $profile->manual_agreement_path || ! Storage::disk('public')->exists($profile->manual_agreement_path)) {
            return back()->with('error', 'No previously uploaded custom agreement found to restore.');
        }

        $profile->update([
            'is_agreement_signed' => true,
            'agreement_pdf_path' => $profile->manual_agreement_path,
        ]);

        return back()->with('success', 'Your custom uploaded agreement has been restored successfully as active.');
    }

    public function sendAgreementLink($id)
    {
        $candidate = User::where('role', 'candidate')->findOrFail($id);

        if (! $candidate->email) {
            return back()->with('error', 'Candidate does not have a valid email address.');
        }

        $signUrl = route('candidate.agreement.show', ['sign' => 1]);

        try {
            Mail::to($candidate->email)->send(
                new AgreementLinkMail($candidate, $signUrl)
            );

            return back()->with('success', 'Agreement link has been emailed to '.$candidate->name.' ('.$candidate->email.').');
        } catch (\Throwable $e) {
            Log::error('Send Agreement Link Error: '.$e->getMessage());

            return back()->with('error', 'Failed to send email. Please check your email configuration.');
        }
    }

    public function downloadAgreement(Request $request, $id)
    {
        try {
            $candidate = User::findOrFail($id);
            $profile = $candidate->profile;

            if (! $profile) {
                return back()->with('error', 'Candidate profile not found.');
            }

            // If manual agreement uploaded by admin exists, download it directly
            if ($profile->agreement_pdf_path && Storage::disk('public')->exists($profile->agreement_pdf_path) && $profile->is_manual_agreement && ! $request->has('regenerate')) {
                $fullFilePath = Storage::disk('public')->path($profile->agreement_pdf_path);
                $cleanName = str_replace(' ', '_', $candidate->name ?? 'Candidate');

                return response()->download($fullFilePath, 'Candidate_Agreement_'.$cleanName.'.pdf');
            }

            // Always generate a fresh PDF (same as what the candidate agreement section/preview shows)
            $profile->ensureIdsAssigned();
            $sigInfo = AgreementController::getSignatureDataForPdf($candidate, $profile);

            $date = $profile->signature_date_time
                ? \Carbon\Carbon::parse($profile->signature_date_time)->format('d M Y')
                : \Carbon\Carbon::now()->format('d M Y');

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.candidate-agreement', [
                'user'           => $candidate,
                'profile'        => $profile,
                'date'           => $date,
                'signature'      => $sigInfo['signature'],
                'signature_type' => $sigInfo['type'],
            ]);

            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'DejaVu Sans',
            ]);

            $cleanName = str_replace(' ', '_', $candidate->name ?? 'Candidate');

            return $pdf->download('Candidate_Agreement_'.$cleanName.'.pdf');
        } catch (Throwable $e) {
            Log::error("Admin agreement download failed for Candidate ID {$id}: ".$e->getMessage()."\nTrace: ".$e->getTraceAsString());

            return back()->with('error', 'Could not generate or download agreement PDF: '.$e->getMessage());
        }
    }


    public function previewAgreement($id)
    {
        try {
            $candidate = User::findOrFail($id);
            $profile = $candidate->profile;

            if (! $profile) {
                return back()->with('error', 'Candidate profile not found.');
            }

            return AgreementController::renderAgreementStream($candidate, $profile);
        } catch (Throwable $e) {
            Log::error("Admin agreement preview failed for Candidate ID {$id}: ".$e->getMessage());

            return back()->with('error', 'Could not preview agreement PDF: '.$e->getMessage());
        }
    }

    public function storeFollowUp(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
            'next_action' => 'nullable|string|max:255',
            'follow_up_at' => 'nullable|date',
            'follow_up_date' => 'nullable|date',
            'tz_offset' => 'nullable|integer|min:-840|max:840',
            'status' => 'required|in:open,closed',
        ]);

        User::where('role', 'candidate')->findOrFail($id);

        $scheduledAt = null;
        $scheduledDate = null;
        if ($request->filled('follow_up_at')) {
            if ($request->filled('tz_offset')) {
                $eastMinutes = -(int) $request->input('tz_offset');
                $sign = $eastMinutes < 0 ? '-' : '+';
                $absMinutes = abs($eastMinutes);
                $clientTz = sprintf('%s%02d:%02d', $sign, intdiv($absMinutes, 60), $absMinutes % 60);
                $localTime = Carbon::parse($request->input('follow_up_at'), $clientTz);
                $scheduledDate = $localTime->toDateString();
                $scheduledAt = $localTime->copy()->setTimezone(config('app.timezone'));
            } else {
                $scheduledAt = Carbon::parse($request->input('follow_up_at'));
                $scheduledDate = $scheduledAt->toDateString();
            }
        } elseif ($request->filled('follow_up_date')) {
            $scheduledDate = Carbon::parse($request->input('follow_up_date'))->toDateString();
            $scheduledAt = Carbon::parse($scheduledDate)->setTime(9, 0);
        }

        $payload = [
            'candidate_id' => $id,
            'notes' => $request->input('notes'),
            'follow_up_date' => $scheduledDate,
            'status' => $request->input('status'),
            'created_by' => auth()->id(),
        ];

        if (CrmFollowUp::hasExtendedColumns()) {
            $payload['next_action'] = $request->filled('next_action') ? trim($request->input('next_action')) : null;
            $payload['follow_up_at'] = $scheduledAt;
        }

        $followUp = CrmFollowUp::create($payload);

        if ($followUp->status === 'open' && $followUp->scheduled_date_time && $followUp->scheduled_date_time->lte(now())) {
            CrmFollowUp::dispatchDueReminders();
        }

        return back()->with('success', 'Follow-up recorded successfully.');
    }

    public function updateFollowUpStatus(Request $request, $followUpId)
    {
        $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        $followUp = CrmFollowUp::findOrFail($followUpId);
        $followUp->update([
            'status' => $request->input('status'),
        ]);

        return back()->with('success', 'Follow-up status updated.');
    }

    public function storeInvoice(Request $request, $id)
    {
        $request->validate([
            'job_application_id' => 'required|exists:job_applications,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $application = JobApplication::findOrFail($request->job_application_id);
        if ($application->status !== 'hired') {
            $application->update(['status' => 'hired']);
        }

        $invoice = ServiceChargeInvoice::create([
            'candidate_id' => $id,
            'job_application_id' => $request->job_application_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        $candidate = User::findOrFail($id);
        $candidate->profile?->increment('pending_amount', $request->amount);

        // Notify Candidate
        DB::table('notifications')->insert([
            'id' => Str::uuid()->toString(),
            'type' => 'App\Notifications\ServiceChargeInvoiceGenerated',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $id,
            'data' => json_encode([
                'title' => 'New Service Charge Invoice',
                'message' => 'An invoice for ₹'.number_format($request->amount, 2).' has been generated for your recent job placement.',
                'amount' => $request->amount,
                'invoice_id' => $invoice->id,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send Email
        Mail::to($candidate->email)->send(new ServiceChargeInvoiceMail($invoice));

        return back()->with('success', 'Invoice created successfully.');
    }

    public function updateInvoiceStatus(Request $request, $invoiceId)
    {
        $invoice = ServiceChargeInvoice::findOrFail($invoiceId);

        $request->validate([
            'status' => 'required|in:pending,paid,overdue',
        ]);

        $oldStatus = $invoice->status;

        $invoice->update([
            'status' => $request->status,
            'payment_date' => $request->status === 'paid' ? now() : null,
        ]);

        $candidate = User::find($invoice->candidate_id);

        if ($request->status === 'paid' && $oldStatus !== 'paid') {
            if ($candidate && $candidate->profile) {
                $candidate->profile->decrement('pending_amount', $invoice->amount);

                // Anti-duplicate check: if an online payment already succeeded for this invoice, don't create MANUAL_SC_
                $existingSuccessTxn = PaymentTransaction::where('candidate_id', $candidate->id)
                    ->where('transaction_id', 'LIKE', 'SC_'.$invoice->id.'_%')
                    ->where('status', 'success')
                    ->first();

                if (! $existingSuccessTxn) {
                    // Create transaction log for exact match on Transactions Page & Dashboard
                    PaymentTransaction::updateOrCreate(
                        [
                            'transaction_id' => 'MANUAL_SC_'.$invoice->id,
                        ],
                        [
                            'candidate_id' => $candidate->id,
                            'amount' => $invoice->amount,
                            'type' => 'placement_fee',
                            'status' => 'success',
                            'gateway_response' => [
                                'note' => 'Marked as Paid manually by Admin',
                                'invoice_id' => $invoice->id,
                                'payment_mode' => 'manual',
                            ],
                        ]
                    );

                    // Dispatch Invoice Email
                    Mail::to($candidate->email)->send(
                        new PaymentReceiptMail($candidate, 'MANUAL_SC_'.$invoice->id, $invoice->amount, 'Service Charge Invoice Payment (Manual)')
                    );
                }
            }
        } elseif ($request->status !== 'paid' && $oldStatus === 'paid') {
            if ($candidate && $candidate->profile) {
                $candidate->profile->increment('pending_amount', $invoice->amount);
            }
            PaymentTransaction::where('transaction_id', 'MANUAL_SC_'.$invoice->id)->delete();
        }

        return back()->with('success', 'Invoice status updated.');
    }

    public function updateInvoiceDetails(Request $request, $invoiceId)
    {
        $invoice = ServiceChargeInvoice::findOrFail($invoiceId);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue',
            'late_fee' => 'nullable|numeric|min:0',
        ]);

        $newAmount = $request->amount;
        $newLateFee = $request->late_fee ?? 0;
        $newStatus = $request->status;

        $invoice->update([
            'amount' => $newAmount,
            'due_date' => $request->due_date,
            'status' => $newStatus,
            'late_fee' => $newLateFee,
            'payment_date' => $newStatus === 'paid' ? ($invoice->payment_date ?? now()) : null,
        ]);

        // Create or remove PaymentTransaction log
        if ($newStatus === 'paid') {
            $existingSuccessTxn = PaymentTransaction::where('candidate_id', $invoice->candidate_id)
                ->where('transaction_id', 'LIKE', 'SC_'.$invoice->id.'_%')
                ->where('status', 'success')
                ->first();

            if (! $existingSuccessTxn) {
                PaymentTransaction::updateOrCreate(
                    [
                        'transaction_id' => 'MANUAL_SC_'.$invoice->id,
                    ],
                    [
                        'candidate_id' => $invoice->candidate_id,
                        'amount' => $newAmount,
                        'type' => 'placement_fee',
                        'status' => 'success',
                        'gateway_response' => [
                            'note' => 'Invoice payment updated by Admin',
                            'invoice_id' => $invoice->id,
                            'payment_mode' => 'manual',
                        ],
                    ]
                );
            }
        } else {
            PaymentTransaction::where('transaction_id', 'MANUAL_SC_'.$invoice->id)->delete();
        }

        // Sync candidate profile pending amount
        $candidate = User::find($invoice->candidate_id);
        if ($candidate && $candidate->profile) {
            $totalPending = ServiceChargeInvoice::where('candidate_id', $candidate->id)
                ->where('status', '!=', 'paid')
                ->get()
                ->sum(fn ($inv) => $inv->amount + $inv->late_fee);

            $candidate->profile->update([
                'pending_amount' => $totalPending,
            ]);
        }

        $invoice->load('candidate');
        try {
            Mail::to($invoice->candidate->email)->send(
                new InvoiceUpdatedMail($invoice)
            );
        } catch (Exception $e) {
            Log::error('Failed to send Invoice Updated mail: '.$e->getMessage());
        }

        return back()->with('success', 'Invoice details updated successfully and candidate notified.');
    }

    public function sendInvoiceReminder(Request $request, $invoiceId)
    {
        $invoice = ServiceChargeInvoice::with(['candidate'])->findOrFail($invoiceId);

        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot send reminder for a paid invoice.');
        }

        $candidate = $invoice->candidate;
        if ($candidate) {
            Mail::to($candidate->email)->send(new PaymentReminderMail($candidate, $invoice));
        }

        return back()->with('success', 'Payment reminder sent successfully to the candidate.');
    }

    public function adjustInvoice(Request $request, $invoiceId)
    {
        $invoice = ServiceChargeInvoice::findOrFail($invoiceId);
        $request->validate([
            'deduction' => 'required|numeric|min:0|max:'.$invoice->late_fee,
        ]);

        $deduction = $request->deduction;

        if ($deduction > 0) {
            $invoice->update([
                'late_fee' => $invoice->late_fee - $deduction,
            ]);

            $candidate = User::find($invoice->candidate_id);
            if ($candidate && $candidate->profile) {
                $candidate->profile->decrement('pending_amount', $deduction);
            }
        }

        return back()->with('success', 'Late fee adjusted successfully.');
    }

    public function toggleVerification($id)
    {
        $candidate = User::where('role', 'candidate')->findOrFail($id);
        $profile = $candidate->profile;

        if (! $profile) {
            return back()->with('error', 'Profile not found.');
        }

        $profile->is_verified = ! $profile->is_verified;
        $profile->save();

        if ($profile->is_verified) {
            // DB Notification
            DB::table('notifications')->insert([
                'id' => Str::uuid()->toString(),
                'type' => 'App\Notifications\ProfileVerified',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $candidate->id,
                'data' => json_encode([
                    'title' => 'Profile Verified!',
                    'message' => 'Congratulations! Your profile has been officially verified by our team. You now have the Verified Badge.',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Email Notification
            Mail::to($candidate->email)->send(new ProfileApprovedMail($candidate));

            // Advance referral funnel to 'verified' stage (+100 pts for referrer)
            ReferralService::advanceStage($candidate, 'verified');

            return back()->with('success', 'Candidate profile has been verified and notified.');
        }

        return back()->with('success', 'Candidate verification removed.');
    }

    /**
     * Update candidate profile photo directly from the Admin CRM panel.
     */
    public function updatePhoto(Request $request, $id)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'profile_photo.required' => 'Please select a photo to upload.',
            'profile_photo.image' => 'The selected file must be a valid image (JPG, PNG, WEBP).',
            'profile_photo.mimes' => 'Only JPG, JPEG, PNG, and WEBP images are allowed.',
            'profile_photo.max' => 'Profile photo size must not exceed 5MB.',
        ]);

        $candidate = User::where('role', 'candidate')->findOrFail($id);
        $profile = $candidate->profile ?: $candidate->profile()->firstOrCreate([]);

        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        $profile->update(['profile_photo_path' => $path]);

        return back()->with('success', 'Candidate profile photo updated successfully.');
    }

    public function assignJob(Request $request, $id)
    {
        $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
        ]);

        $candidate = User::findOrFail($id);

        if (JobApplication::where('job_post_id', $request->job_post_id)->where('candidate_id', $candidate->id)->exists()) {
            return back()->with('error', 'Candidate is already applied to this job.');
        }

        JobApplication::create([
            'job_post_id' => $request->job_post_id,
            'candidate_id' => $candidate->id,
            'status' => 'applied',
            'match_score' => 0, // Manually assigned by admin
        ]);

        if ($candidate->profile) {
            $candidate->profile->increment('used_applications');
        }

        return back()->with('success', 'Job application assigned successfully.');
    }

    public function rateCandidate(Request $request, $id)
    {
        $request->validate([
            'communication' => 'required|integer|min:1|max:5',
            'subject_knowledge' => 'required|integer|min:1|max:5',
            'demo_performance' => 'required|integer|min:1|max:5',
            'english_fluency' => 'required|integer|min:1|max:5',
            'discipline' => 'required|integer|min:1|max:5',
            'remarks' => 'nullable|string',
        ]);

        $overall = ($request->communication + $request->subject_knowledge + $request->demo_performance + $request->english_fluency + $request->discipline) / 5;

        CandidateRating::updateOrCreate(
            ['candidate_id' => $id],
            [
                'communication' => $request->communication,
                'subject_knowledge' => $request->subject_knowledge,
                'demo_performance' => $request->demo_performance,
                'english_fluency' => $request->english_fluency,
                'discipline' => $request->discipline,
                'overall_rating' => $overall,
                'remarks' => $request->remarks,
                'rated_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Candidate rating updated successfully.');
    }

    public function magicLogin($id)
    {
        $candidate = User::where('role', 'candidate')->findOrFail($id);

        // Store admin id in session so they can switch back if needed (optional)
        session(['admin_id' => auth()->id()]);

        Auth::login($candidate);

        return redirect()->route('candidate.dashboard');
    }

    public function sendOnboardingReminder($id)
    {
        $user = User::where('role', 'candidate')->findOrFail($id);
        $profile = $user->profile;

        $reason = $profile ? $profile->pending_reason : 'Pending Profile Completion';
        $actionUrl = $profile ? $profile->pending_action_url : route('candidate.wizard');

        if ($reason === 'Completed') {
            return back()->with('error', 'Candidate onboarding is already completed.');
        }

        Mail::to($user->email)->send(new CandidateReminderMail($user, $reason, $actionUrl));

        return back()->with('success', 'Reminder email sent successfully for: '.$reason);
    }

    public function sendBulkOnboardingReminder(Request $request)
    {
        $request->validate([
            'select_all_matching' => 'nullable|boolean',
            'candidate_ids' => 'nullable|array',
            'candidate_ids.*' => 'integer|exists:users,id',
            'excluded_ids' => 'nullable|array',
            'excluded_ids.*' => 'integer',
            'excluded_ids_csv' => 'nullable|string',
            'notification_type' => 'required|in:status_reminder,custom_email',
            'custom_subject' => 'nullable|required_if:notification_type,custom_email|string|max:255',
            'custom_message' => 'nullable|required_if:notification_type,custom_email|string',
        ]);

        $selectAllMatching = $request->boolean('select_all_matching');
        $manualIds = array_values(array_unique(array_map('intval', (array) $request->input('candidate_ids', []))));

        if (!$selectAllMatching && empty($manualIds)) {
            return back()->with('error', 'Please select at least one candidate or click Select All Matching Candidates.');
        }

        if ($selectAllMatching) {
            $targetQuery = $this->buildFilteredCandidatesQuery($request, true);

            $excludedIds = array_map('intval', (array) $request->input('excluded_ids', []));
            if ($request->filled('excluded_ids_csv')) {
                $csvIds = array_filter(array_map('intval', explode(',', (string) $request->input('excluded_ids_csv'))));
                $excludedIds = array_merge($excludedIds, $csvIds);
            }
            $excludedIds = array_values(array_unique(array_filter($excludedIds)));

            if (!empty($excludedIds)) {
                $targetQuery->whereNotIn('users.id', $excludedIds);
            }
        } else {
            $targetQuery = User::where('role', 'candidate')->whereIn('id', $manualIds);
        }

        $targetQuery->whereNotNull('email')->where('email', '!=', '');

        $totalMatched = (clone $targetQuery)->count();
        if ($totalMatched === 0) {
            return back()->with('warning', 'No matching candidates with a valid email address were found for the current selection.');
        }

        $chunkSize = 50;
        $totalChunks = (int) ceil($totalMatched / $chunkSize);
        $batchKey = (string) Str::uuid();

        Cache::put("crm_bulk_batch:{$batchKey}", [
            'batch_key' => $batchKey,
            'status' => 'queued',
            'notification_type' => $request->notification_type,
            'total' => $totalMatched,
            'total_chunks' => $totalChunks,
            'chunks_done' => 0,
            'processed' => 0,
            'sent' => 0,
            'skipped' => 0,
            'failed' => 0,
            'queued_at' => now('Asia/Kolkata')->format('d M Y, h:i A') . ' IST',
        ], now()->addHours(12));

        $dispatchedChunks = 0;
        (clone $targetQuery)
            ->select('users.id')
            ->orderBy('users.id')
            ->chunkById($chunkSize, function ($rows) use ($request, $batchKey, &$dispatchedChunks) {
                $ids = $rows->pluck('id')->all();
                if (!empty($ids)) {
                    SendCandidateBulkNotificationChunkJob::dispatch(
                        $ids,
                        (string) $request->notification_type,
                        $request->custom_subject,
                        $request->custom_message,
                        $batchKey
                    );
                    $dispatchedChunks++;
                }
            }, 'users.id', 'id');

        return back()
            ->with('crm_last_bulk_batch_id', $batchKey)
            ->with('success', "Queued notifications for {$totalMatched} matching candidate(s) across {$dispatchedChunks} background job batch(es). Emails are being processed asynchronously in the background.");
    }

    public function manualPaymentFulfill(Request $request, $id)
    {
        @set_time_limit(120);

        $request->validate([
            'plan_type' => 'required|in:standard,premium',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'admin_notes' => 'nullable|string',
        ]);

        $user = User::with('profile')->findOrFail($id);
        $profile = $user->profile;

        // Anti-duplicate protection 1: check if a manual payment was already processed for this user in the last 5 minutes
        $recentTxn = PaymentTransaction::where('candidate_id', $user->id)
            ->where('transaction_id', 'LIKE', 'MANUAL_%')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->where('status', 'success')
            ->latest()
            ->first();

        if ($recentTxn) {
            return back()->with('warning', 'A manual payment for this candidate was already processed recently ('.$recentTxn->transaction_id.'). Duplicate submission prevented.');
        }

        // Anti-duplicate protection 2: If candidate already has an active paid plan of this type and not expired
        if ($profile && $profile->is_fee_paid && $profile->plan_type === $request->plan_type && ! $profile->is_plan_expired) {
            return back()->with('info', "Candidate {$user->name} already has an active {$request->plan_type} plan with payment completed.");
        }

        $transactionId = 'MANUAL_'.strtoupper($request->payment_method).'_'.$user->id.'_'.time();

        $result = PaymentFulfillmentService::fulfill(
            $transactionId,
            true,
            (float) $request->amount,
            [
                'note' => 'Manually processed by Admin from CRM',
                'admin_notes' => $request->admin_notes,
                'payment_method' => $request->payment_method,
            ],
            $transactionId,
            $request->plan_type
        );

        if ($result['success']) {
            return back()->with('success', 'Plan upgraded and payment marked successfully for candidate: '.$user->name);
        }

        return back()->with('error', 'Failed to process payment fulfillment.');
    }

    public function destroyInvoice($invoiceId)
    {
        $invoice = ServiceChargeInvoice::findOrFail($invoiceId);
        $candidate = User::find($invoice->candidate_id);

        if ($invoice->status !== 'paid' && $candidate && $candidate->profile) {
            $candidate->profile->pending_amount = max(0, $candidate->profile->pending_amount - $invoice->amount);
            $candidate->profile->save();
        }

        // Delete associated MANUAL_SC_ payment transaction if exists
        PaymentTransaction::where('transaction_id', 'MANUAL_SC_'.$invoice->id)->delete();

        $invoice->delete();

        return back()->with('success', 'Service charge invoice deleted successfully.');
    }

    public function sendCandidateEmail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'invoice_id' => 'nullable|integer',
        ]);

        $candidate = User::with(['profile.category', 'profile.subject', 'applications.jobPost'])->findOrFail($id);

        if (! $candidate->email) {
            return back()->with('error', 'Candidate does not have a registered email address.');
        }

        $latestInv = null;
        if ($request->filled('invoice_id')) {
            $latestInv = ServiceChargeInvoice::where('candidate_id', $candidate->id)->find($request->invoice_id);
        }
        if (! $latestInv) {
            $latestInv = ServiceChargeInvoice::where('candidate_id', $candidate->id)->latest()->first();
        }

        $latestPay = null;
        if ($latestInv) {
            $latestPay = PaymentTransaction::where('candidate_id', $candidate->id)
                ->where('transaction_id', 'MANUAL_SC_'.$latestInv->id)
                ->first();
        }
        if (! $latestPay) {
            $latestPay = PaymentTransaction::where('candidate_id', $candidate->id)->where('status', 'success')->latest()->first();
        }

        $rawInvAmt = $latestInv ? (float) $latestInv->amount : 0;
        $rawPaidAmt = $candidate->profile ? (float) $candidate->profile->paid_amount : 0;
        $rawPayTxnAmt = $latestPay ? (float) $latestPay->amount : 0;

        $computedAmt = $rawInvAmt > 0 ? $rawInvAmt : ($rawPayTxnAmt > 0 ? $rawPayTxnAmt : ($rawPaidAmt > 0 ? $rawPaidAmt : 500));
        $formattedAmt = number_format($computedAmt, 2);

        $invNum = $latestInv ? 'INV-SC-'.str_pad($latestInv->id, 5, '0', STR_PAD_LEFT) : ($latestPay ? ($latestPay->transaction_id ?? 'INV-PAY') : ($candidate->profile?->payment_id ?? 'INV-VPA'));
        $invDueDate = $latestInv && $latestInv->due_date ? Carbon::parse($latestInv->due_date)->format('M d, Y') : now()->addDays(7)->format('M d, Y');
        $invStatus = $latestInv ? ucfirst($latestInv->status) : ($candidate->profile?->is_fee_paid ? 'Paid' : 'Pending');

        $replacements = [
            '{name}' => $candidate->name,
            '[name]' => $candidate->name,
            '{candidate_name}' => $candidate->name,
            '[candidate_name]' => $candidate->name,
            '{email}' => $candidate->email,
            '[email]' => $candidate->email,
            '{phone}' => $candidate->phone ?? '',
            '[phone]' => $candidate->phone ?? '',
            '{category}' => $candidate->profile?->category?->name ?? 'Teaching',
            '[category]' => $candidate->profile?->category?->name ?? 'Teaching',
            '{subject}' => $candidate->profile?->subject?->name ?? 'Faculty',
            '[subject]' => $candidate->profile?->subject?->name ?? 'Faculty',
            '{plan_type}' => ucfirst($candidate->profile?->plan_type ?? 'Standard'),
            '[plan_type]' => ucfirst($candidate->profile?->plan_type ?? 'Standard'),
            '{payment_amount}' => $formattedAmt,
            '[payment_amount]' => $formattedAmt,
            '{amount}' => $formattedAmt,
            '[amount]' => $formattedAmt,
            '{invoice_amount}' => $formattedAmt,
            '[invoice_amount]' => $formattedAmt,
            '{service_charge}' => $formattedAmt,
            '[service_charge]' => $formattedAmt,
            '{service_charge_amount}' => $formattedAmt,
            '[service_charge_amount]' => $formattedAmt,
            '{pending_amount}' => $latestInv ? number_format($latestInv->amount + $latestInv->late_fee, 2) : number_format($candidate->profile?->pending_amount ?? 0, 2),
            '[pending_amount]' => $latestInv ? number_format($latestInv->amount + $latestInv->late_fee, 2) : number_format($candidate->profile?->pending_amount ?? 0, 2),
            '{invoice_number}' => $invNum,
            '[invoice_number]' => $invNum,
            '{due_date}' => $invDueDate,
            '[due_date]' => $invDueDate,
            '{status}' => $invStatus,
            '[status]' => $invStatus,
            '{job_title}' => $candidate->applications->first()?->jobPost?->title ?? 'Teaching Position',
            '[job_title]' => $candidate->applications->first()?->jobPost?->title ?? 'Teaching Position',
            '{school_name}' => $candidate->applications->first()?->jobPost?->school_name ?? 'Partner Educational Institution',
            '[school_name]' => $candidate->applications->first()?->jobPost?->school_name ?? 'Partner Educational Institution',
            '{action_url}' => route('candidate.dashboard'),
            '[action_url]' => route('candidate.dashboard'),
        ];

        $subject = str_replace(array_keys($replacements), array_values($replacements), $request->subject);
        $body = str_replace(array_keys($replacements), array_values($replacements), $request->body);

        try {
            Mail::to($candidate->email)->send(
                new DynamicTemplateMail($subject, $body)
            );

            // Log communication in Follow-ups
            CrmFollowUp::create([
                'candidate_id' => $candidate->id,
                'created_by' => auth()->id(),
                'status' => 'closed',
                'notes' => 'Sent Email: '.$subject,
                'follow_up_date' => null,
            ]);

            return back()->with('success', 'Email sent successfully to '.$candidate->email);
        } catch (Exception $e) {
            Log::error('Failed to send candidate email: '.$e->getMessage());

            return back()->with('error', 'Failed to send email: '.$e->getMessage());
        }
    }

    /**
     * Trigger a self-service password reset email link for the candidate (never exposes or stores plain password).
     */
    public function sendPasswordResetLink($id)
    {
        $candidate = User::where('role', 'candidate')->findOrFail($id);

        if (!$candidate->email) {
            return back()->with('error', 'Candidate does not have a registered email address.');
        }

        try {
            $status = Password::broker()->sendResetLink(['email' => $candidate->email]);

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('success', "Self-service password reset link sent to {$candidate->email}. Existing passwords are encrypted and never visible.");
            }

            return back()->with('error', __($status));
        } catch (Throwable $e) {
            Log::error('Admin password reset link failed', [
                'candidate_id' => $candidate->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to send password reset email: ' . $e->getMessage());
        }
    }

    /**
     * Issue a one-time temporary password (stored hashed only) and force change on next login.
     */
    public function issueTemporaryPassword(Request $request, $id)
    {
        $candidate = User::where('role', 'candidate')->findOrFail($id);

        $validated = $request->validate([
            'mode' => 'nullable|in:auto,manual',
            'temporary_password' => 'required_if:mode,manual|nullable|string|min:8|max:64',
            'send_email' => 'nullable|boolean',
        ], [
            'temporary_password.required_if' => 'Please enter a temporary password or switch to Auto-Generate.',
            'temporary_password.min' => 'Temporary password must be at least 8 characters.',
        ]);

        $mode = $validated['mode'] ?? 'auto';
        $plainTemporary = $mode === 'manual'
            ? (string) $validated['temporary_password']
            : self::generateTemporaryPassword();

        // Only a one-way hash is saved; readable password is never stored anywhere in DB or logs.
        $candidate->issueTemporaryPassword($plainTemporary);

        $shouldEmail = $request->boolean('send_email', true) && !empty($candidate->email);
        $emailSent = false;
        $emailError = null;

        if ($shouldEmail) {
            try {
                Mail::to($candidate->email)->send(new TemporaryPasswordMail($candidate, $plainTemporary));
                $emailSent = true;
            } catch (Throwable $e) {
                Log::warning('Failed to email temporary password notice', [
                    'candidate_id' => $candidate->id,
                    'error' => $e->getMessage(),
                ]);
                $emailError = $e->getMessage();
            }
        }

        session()->flash('issued_temp_password', [
            'candidate_id' => $candidate->id,
            'password' => $plainTemporary,
            'emailed' => $emailSent,
            'email_error' => $emailError,
        ]);

        $msg = "Temporary password issued for {$candidate->name}. They will be required to change it on next login.";
        if ($emailSent) {
            $msg .= " Notification sent to {$candidate->email}.";
        } elseif ($emailError) {
            $msg .= ' (Email delivery failed — please share the one-time password shown below securely.)';
        }

        return back()->with('success', $msg);
    }

    private static function generateTemporaryPassword(): string
    {
        $digits = (string) random_int(1000, 9999);
        $letters = '';
        $alphabet = 'abcdefghjkmnpqrstuvwxyz';
        for ($i = 0; $i < 4; $i++) {
            $letters .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return "Vpa@{$digits}{$letters}";
    }
}
