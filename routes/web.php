<?php

use App\Http\Controllers\Admin\BulkEmailController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ClientLogoController;
use App\Http\Controllers\Admin\ContactLeadController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\QualificationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SystemAnomalyController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Candidate\AgreementController;
use App\Http\Controllers\Candidate\ApplicationController;
use App\Http\Controllers\Candidate\PaymentController;
use App\Http\Controllers\Candidate\ProfileController;
use App\Http\Controllers\Candidate\ReferralController;
use App\Http\Controllers\Candidate\RegistrationController;
use App\Http\Controllers\Candidate\RegistrationWizardController;
use App\Http\Controllers\Candidate\SavedJobController;
use App\Http\Controllers\Candidate\ServiceChargeController;
use App\Http\Controllers\CandidateAuthController;
use App\Http\Controllers\Employer\ApplicantController;
use App\Http\Controllers\Employer\DashboardController;
use App\Http\Controllers\EmployerAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PhonePeWebhookController;
use App\Http\Controllers\ResumeBuilderController;
use App\Models\JobPost;
use App\Models\PaymentTransaction;
use App\Models\ReferralWallet;
use App\Models\SavedJob;
use App\Models\State;
use App\Services\PaymentFulfillmentService;
use App\Services\PhonePeService;
use App\Services\ReferralService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [HomeController::class, 'jobs'])->name('jobs');
Route::get('/api/jobs/search-suggestions', [HomeController::class, 'jobSuggestions'])->name('api.jobs.suggestions');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/api/jobs/{job}/check-access', [JobController::class, 'checkSchoolAccess'])->name('api.jobs.check-access');
Route::get('/category/{id}/jobs', [HomeController::class, 'categoryJobs'])->name('category.jobs');

// Dynamic Subjects and Specializations
Route::get('/api/categories/{category}/subjects', [HomeController::class, 'getSubjects'])->name('api.category.subjects');
Route::get('/api/subjects/{subject}/specializations', [HomeController::class, 'getSpecializations'])->name('api.subject.specializations');
Route::get('/api/states/{state}/cities', function (State $state) {
    return $state->cities()->where('is_active', true)->get();
})->name('api.state.cities');

// PhonePe Webhook
Route::post('/webhook/phonepe', [PhonePeWebhookController::class, 'handle'])->name('webhook.phonepe');

Route::view('/about', 'about')->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/services/{slug}', [HomeController::class, 'serviceDetails'])->name('service.details');
Route::view('/hiring-process', 'hiring')->name('hiring');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');
Route::view('/apply', 'apply')->name('apply');
Route::get('/post-job', [JobController::class, 'showPostJobForm'])->name('post-job');
Route::post('/post-job', [JobController::class, 'storeJobQuery'])->name('post-job.store');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/media', 'media')->name('media');
Route::get('/refund', function () {
    return view('refund');
})->name('refund');
Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');
Route::get('/cookie', function () {
    return view('cookie');
})->name('cookie');
Route::get('/disclaimer', function () {
    return view('disclaimer');
})->name('disclaimer');
Route::get('/employer', function () {
    return view('employer');
})->name('employer');
Route::get('/candidate', function () {
    return view('candidate');
})->name('candidate');

// Resume Builder (Public)
Route::get('/resume-builder', [ResumeBuilderController::class, 'index'])->name('resume.builder');
Route::post('/resume-builder/download', [ResumeBuilderController::class, 'download'])->name('resume.builder.download');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Password Reset Routes
Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->middleware('guest')->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->middleware('guest')->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->middleware('guest')->name('password.update');

// OTP Login Routes
Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp');
Route::post('/login/otp/send', [AuthController::class, 'sendOtp'])->name('login.otp.send');
Route::post('/login/otp/verify', [AuthController::class, 'verifyOtp'])->name('login.otp.verify');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    if (auth()->user()->role === 'employer') {
        return redirect('/employer/dashboard');
    }

    return redirect('/candidate/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Candidate Auth Routes
Route::get('/register', [CandidateAuthController::class, 'showRegistrationForm'])->name('candidate.register');
Route::post('/register', [CandidateAuthController::class, 'register'])->name('candidate.register.post');

// Payment Callback Routes (OUTSIDE auth middleware — PhonePe redirect can lose session)
Route::prefix('candidate')->name('candidate.')->group(function () {
    Route::match(['get', 'post'], '/wizard/callback', [RegistrationWizardController::class, 'callback'])->name('wizard.callback');
    Route::match(['get', 'post'], '/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::match(['get', 'post'], '/service-charge/callback', [ServiceChargeController::class, 'callback'])->name('serviceCharge.callback');
});

// Candidate Routes (Unverified but Auth Required)
Route::middleware(['auth', 'candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    // Registration Wizard
    Route::get('/wizard', [RegistrationWizardController::class, 'show'])->name('wizard');
    Route::post('/wizard/step1', [RegistrationWizardController::class, 'saveStep1'])->name('wizard.step1');
    Route::post('/wizard/step2', [RegistrationWizardController::class, 'saveStep2'])->name('wizard.step2');
    Route::post('/wizard/step3', [RegistrationWizardController::class, 'saveStep3'])->name('wizard.step3');
    Route::post('/wizard/payment', [RegistrationWizardController::class, 'initiatePayment'])->name('wizard.payment');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $profile = $user ? ($user->profile ?: $user->profile()->firstOrCreate([])) : null;

        // Removed dashboard block to allow users to skip and access dashboard
        // without signing the agreement immediately.

        // Auto-heal pending payment transaction if any exists in last 2 hours
        if ($user) {
            $pendingTxn = PaymentTransaction::where('candidate_id', $user->id)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subHours(2))
                ->latest()
                ->first();

            if ($pendingTxn) {
                try {
                    $phonePe = new PhonePeService;
                    $statusResult = $phonePe->checkStatus($pendingTxn->transaction_id);
                    if ($statusResult['success']) {
                        PaymentFulfillmentService::fulfill(
                            $pendingTxn->transaction_id,
                            true,
                            ($statusResult['amount'] ?? 0) / 100,
                            $statusResult['raw'] ?? [],
                            $statusResult['transactionId'] ?? null
                        );
                        $profile = $user->fresh()->profile;
                    } elseif ($statusResult['is_failed'] ?? false) {
                        $pendingTxn->update(['status' => 'failed']);
                    }
                } catch (Throwable $e) {
                    Log::warning('Dashboard auto-heal check error: '.$e->getMessage());
                }
            }
        }

        // Dynamic metrics
        $applicationsCount = $user ? $user->applications()->count() : 0;
        $shortlistedCount = $user ? $user->applications()->where('status', 'shortlisted')->count() : 0;
        $interviewsCount = $user ? $user->applications()->where(function ($q) {
            $q->where('status', 'interviewed')->orWhereNotNull('interview_date');
        })->count() : 0;

        // Auto-heal candidate profile completion if admin filled core details or fee is paid
        if ($user && $profile) {
            $hasCoreDetails = ! empty($profile->category_id) && ! empty($profile->subject_id);
            $hasPayment = (bool) ($profile->initial_fee_paid || $profile->is_fee_paid || ($profile->paid_amount ?? 0) >= 500);

            $needsUpdate = false;
            $updates = [];

            if (! $profile->is_profile_complete && ($hasCoreDetails || $hasPayment || ! empty($profile->registration_completed_at))) {
                $updates['is_profile_complete'] = true;
                $needsUpdate = true;
            }

            if (empty($profile->current_school)) {
                $updates['current_school'] = 'Fresher';
                $needsUpdate = true;
            }

            // Ensure agreement is only considered signed if candidate actually signed or admin uploaded agreement
            if ($profile->is_manual_agreement && ! $profile->is_agreement_signed) {
                $updates['is_agreement_signed'] = true;
                $needsUpdate = true;
            }

            // If fee is paid and core details exist, ensure registration_completed_at
            if ($hasPayment && ($profile->is_profile_complete || ! empty($updates['is_profile_complete'])) && empty($profile->registration_completed_at)) {
                $updates['registration_completed_at'] = now();
                $needsUpdate = true;
            }

            if ($needsUpdate) {
                $profile->update($updates);
                $profile->refresh();
            }
        }

        // Profile Views (Dynamic from actual views recorded)
        $profileViews = $profile ? (int) ($profile->views_count ?? 0) : 0;

        // Referral Wallet & Points
        $wallet = $user ? ReferralService::getWallet($user) : null;
        $pointRate = ReferralService::getPointRate();
        $availablePoints = $wallet ? (float) $wallet->available_points : 0;
        $walletBalanceInr = round($availablePoints * $pointRate, 2);

        // Profile Strength
        $profileStrength = 0;
        if ($user && $profile) {
            if (! empty($user->name) && ! empty($user->email)) {
                $profileStrength += 20;
            }
            if (! empty($user->phone)) {
                $profileStrength += 10;
            }
            if (! empty($profile->profile_photo_path)) {
                $profileStrength += 15;
            }
            if ($profile->experience_years > 0 || ! empty($profile->category_id) || ! empty($profile->current_school)) {
                $profileStrength += 20;
            }
            if (! empty($profile->resume_path) || $profile->is_profile_complete) {
                $profileStrength += 15;
            }
            if ($profile->is_agreement_signed || ! empty($profile->signature_date_time) || ! empty($profile->agreement_signed_at)) {
                $profileStrength += 10;
            }
            if ($profile->is_fee_paid || $profile->initial_fee_paid || ($profile->paid_amount ?? 0) >= 500) {
                $profileStrength += 10;
            }
        }
        $profileStrength = min(100, max(20, $profileStrength));

        // Recommended Jobs
        $recommendedJobs = JobPost::with(['state', 'city', 'category', 'subject'])
            ->where('status', 'approved')
            ->latest()
            ->take(4)
            ->get();

        // Notifications
        $notifications = $user ? $user->notifications()->take(4)->get() : collect();

        // Leaderboard Top 5
        $leaderboard = collect();
        $realWallets = ReferralWallet::with('user.profile')
            ->where('available_points', '>', 0)
            ->orderByDesc('available_points')
            ->take(5)
            ->get();

        foreach ($realWallets as $w) {
            if ($w->user) {
                $leaderboard->push([
                    'name' => $w->user->name,
                    'points' => (int) $w->available_points,
                    'avatar' => $w->user->profile?->profile_photo_path ? asset('storage/'.$w->user->profile->profile_photo_path) : null,
                ]);
            }
        }

        $defaultLeaders = [
            ['name' => 'Amit Kumar', 'points' => 2450, 'avatar' => null],
            ['name' => 'Neha Sharma', 'points' => 1850, 'avatar' => null],
            ['name' => 'Pooja Singh', 'points' => 1250, 'avatar' => null],
            ['name' => 'Rohit Kumar', 'points' => 950, 'avatar' => null],
            ['name' => 'Sneha Patel', 'points' => 750, 'avatar' => null],
        ];
        foreach ($defaultLeaders as $dl) {
            if ($leaderboard->count() >= 5) {
                break;
            }
            if (! $leaderboard->pluck('name')->contains($dl['name'])) {
                $leaderboard->push($dl);
            }
        }

        // Saved Jobs for Candidate
        $savedJobIds = $user ? SavedJob::where('user_id', $user->id)->pluck('job_post_id')->toArray() : [];
        $savedJobsCount = count($savedJobIds);

        return view('candidate.dashboard', compact(
            'profile',
            'applicationsCount',
            'shortlistedCount',
            'interviewsCount',
            'profileViews',
            'wallet',
            'pointRate',
            'availablePoints',
            'walletBalanceInr',
            'profileStrength',
            'recommendedJobs',
            'notifications',
            'leaderboard',
            'savedJobIds',
            'savedJobsCount'
        ));
    })->name('dashboard');

    // Saved Jobs Routes
    Route::post('/jobs/{job}/toggle-save', [SavedJobController::class, 'toggle'])->name('jobs.toggleSave');
    Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('savedJobs.index');
});

// Candidate Routes (Protected & Verified)
Route::middleware(['auth', 'verified', 'candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/agreement', [AgreementController::class, 'show'])->name('agreement.show');
    Route::get('/agreement/preview', [AgreementController::class, 'preview'])->name('agreement.preview');
    Route::post('/agreement/sign', [AgreementController::class, 'sign'])->name('agreement.sign');
    Route::get('/agreement/download', [AgreementController::class, 'download'])->name('agreement.download');

    Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/invoice/{id}', [PaymentController::class, 'invoice'])->name('payment.invoice');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/available', [ApplicationController::class, 'available'])->name('applications.available');
    Route::post('/applications/{job}/apply', [ApplicationController::class, 'apply'])->name('applications.apply');

    Route::get('/registration', [RegistrationController::class, 'show'])->name('registration.show');
    Route::get('/service-charge', [ServiceChargeController::class, 'show'])->name('serviceCharge.show');
    Route::get('/service-charge/invoice/{id}/pdf', [ServiceChargeController::class, 'downloadInvoicePdf'])->name('serviceCharge.invoicePdf');
    Route::post('/service-charge/pay', [ServiceChargeController::class, 'process'])->name('serviceCharge.pay');

    // Refer & Earn Routes
    Route::get('/referral', [ReferralController::class, 'index'])->name('referral.index');
    Route::get('/referral/{id}', [ReferralController::class, 'show'])->name('referral.show');
    Route::post('/referral/invite', [ReferralController::class, 'sendInvite'])->name('referral.invite');
    Route::post('/referral/redeem', [ReferralController::class, 'redeem'])->name('referral.redeem');

    // Service Charge callback moved outside auth middleware group (see top of file)
    Route::view('/additional-feature', 'candidate.aditionalFeature.show')->name('aditionalFeature.show');
});

// Employer Auth Routes
Route::get('/employer/register', [EmployerAuthController::class, 'showRegistrationForm'])->name('employer.register');
Route::post('/employer/register', [EmployerAuthController::class, 'register'])->name('employer.register.post');

// Employer Routes (Protected)
Route::middleware(['auth', 'verified', 'employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('jobs', App\Http\Controllers\Employer\JobController::class);

    Route::get('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');
    Route::post('/candidate/{id}/track-view', [ApplicantController::class, 'trackView'])->name('candidate.trackView');
});

// Global Impersonation Leave Route
Route::middleware(['auth'])->get('/admin/impersonate/leave', [UserController::class, 'leaveImpersonate'])->name('admin.impersonate.leave');

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('qualifications', QualificationController::class);
    Route::resource('states', StateController::class);
    Route::resource('cities', CityController::class);

    // Job Posts
    Route::resource('jobs', App\Http\Controllers\Admin\JobController::class);
    Route::post('jobs/{job}/approve', [App\Http\Controllers\Admin\JobController::class, 'approve'])->name('jobs.approve');
    Route::post('jobs/{job}/reject', [App\Http\Controllers\Admin\JobController::class, 'reject'])->name('jobs.reject');
    Route::get('jobs/{job}/candidates/search', [App\Http\Controllers\Admin\JobController::class, 'searchCandidates'])->name('jobs.candidates.search');
    Route::post('jobs/{job}/send-message', [App\Http\Controllers\Admin\JobController::class, 'sendMessage'])->name('jobs.send-message');

    // Candidates CRM
    Route::get('/candidates/create', [CrmController::class, 'create'])->name('crm.create');
    Route::post('/candidates/store', [CrmController::class, 'store'])->name('crm.store');
    Route::get('/candidates/{id}/edit', [CrmController::class, 'edit'])->name('crm.edit');
    Route::put('/candidates/{id}', [CrmController::class, 'update'])->name('crm.update');
    Route::get('/candidates', [CrmController::class, 'index'])->name('crm.index');
    Route::get('/candidates/{id}', [CrmController::class, 'show'])->name('crm.show');
    Route::post('/crm/candidate/{id}/follow-up', [CrmController::class, 'storeFollowUp'])->name('crm.followup.store');
    Route::post('/crm/candidate/{id}/invoice', [CrmController::class, 'storeInvoice'])->name('crm.invoice.store');
    Route::post('/crm/candidate/{id}/assign-job', [CrmController::class, 'assignJob'])->name('crm.application.assign');
    Route::put('/crm/invoice/{id}', [CrmController::class, 'updateInvoiceStatus'])->name('crm.invoice.update');
    Route::put('/crm/invoice/{id}/edit-details', [CrmController::class, 'updateInvoiceDetails'])->name('crm.invoice.update-details');
    Route::delete('/crm/invoice/{id}', [CrmController::class, 'destroyInvoice'])->name('crm.invoice.destroy');
    Route::post('/crm/invoice/{id}/remind', [CrmController::class, 'sendInvoiceReminder'])->name('crm.invoice.remind');
    Route::post('/crm/invoice/{id}/adjust', [CrmController::class, 'adjustInvoice'])->name('crm.invoice.adjust');
    Route::post('/crm/candidate/{id}/toggle-verification', [CrmController::class, 'toggleVerification'])->name('crm.candidate.verify');
    Route::post('/crm/candidate/{id}/rate', [CrmController::class, 'rateCandidate'])->name('crm.candidate.rate');
    Route::get('/crm/candidate/{id}/magic-login', [CrmController::class, 'magicLogin'])->name('crm.candidate.magic-login');
    Route::post('/crm/candidate/{id}/remind', [CrmController::class, 'sendOnboardingReminder'])->name('crm.candidate.remind');
    Route::post('/crm/candidates/bulk-remind', [CrmController::class, 'sendBulkOnboardingReminder'])->name('crm.candidate.bulk-remind');
    Route::post('/crm/candidate/{id}/upload-agreement', [CrmController::class, 'uploadAgreement'])->name('crm.candidate.upload-agreement');
    Route::post('/crm/candidate/{id}/restore-agreement', [CrmController::class, 'restoreAgreement'])->name('crm.candidate.restore-agreement');
    Route::post('/crm/candidate/{id}/send-agreement-link', [CrmController::class, 'sendAgreementLink'])->name('crm.candidate.send-agreement-link');
    Route::get('/crm/candidate/{id}/download-agreement', [CrmController::class, 'downloadAgreement'])->name('crm.candidate.download-agreement');
    Route::get('/crm/candidate/{id}/preview-agreement', [CrmController::class, 'previewAgreement'])->name('crm.candidate.preview-agreement');
    Route::post('/crm/candidate/{id}/fulfill-payment', [CrmController::class, 'manualPaymentFulfill'])->name('crm.candidate.fulfill-payment');
    Route::post('/crm/candidate/{id}/send-email', [CrmController::class, 'sendCandidateEmail'])->name('crm.candidate.send-email');

    // Applications & Transactions
    Route::get('/applications', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/applications/{id}/status', [App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('applications.status.update');

    // Email Templates
    Route::resource('email-templates', EmailTemplateController::class);
    Route::get('email-templates/api/{id}', [EmailTemplateController::class, 'getTemplate'])->name('email-templates.api');

    // Bulk Email
    Route::get('/bulk-email', [BulkEmailController::class, 'index'])->name('bulk-email.index');
    Route::post('/bulk-email/send', [BulkEmailController::class, 'send'])->name('bulk-email.send');
    Route::get('/bulk-email/search-users', [BulkEmailController::class, 'searchUsers'])->name('bulk-email.search-users');
    Route::post('/applications/{id}/share-review', [App\Http\Controllers\Admin\ApplicationController::class, 'shareReview'])->name('applications.share-review');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // Contact Leads
    Route::get('/leads', [ContactLeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/{id}', [ContactLeadController::class, 'show'])->name('leads.show');
    Route::put('/leads/{id}/status', [ContactLeadController::class, 'updateStatus'])->name('leads.status.update');
    Route::post('/leads/{id}/follow-up', [ContactLeadController::class, 'storeFollowUp'])->name('leads.followup.store');
    Route::delete('/leads/{id}', [ContactLeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/bulk-delete', [ContactLeadController::class, 'bulkDestroy'])->name('leads.bulk-delete');

    // Frontend Management

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/{id}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');

    // Notification Management
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // System Anomalies Tool
    Route::get('/upgrade-anomalies', [SystemAnomalyController::class, 'index'])->name('anomalies.index');
    Route::post('/upgrade-anomalies/fix/{id}', [SystemAnomalyController::class, 'fix'])->name('anomalies.fix');

    Route::resource('services', ServiceController::class)->except(['create', 'show', 'edit']);
    Route::resource('testimonials', TestimonialController::class)->except(['create', 'show', 'edit']);
    Route::resource('clients', ClientLogoController::class)->except(['create', 'show', 'edit'])->parameters(['clients' => 'clientLogo']);

    // Refer & Earn Admin Management
    Route::get('/referrals/dashboard', [App\Http\Controllers\Admin\ReferralController::class, 'dashboard'])->name('referrals.dashboard');
    Route::get('/referrals', [App\Http\Controllers\Admin\ReferralController::class, 'index'])->name('referrals.index');
    Route::get('/referrals/leaderboard', [App\Http\Controllers\Admin\ReferralController::class, 'leaderboard'])->name('referrals.leaderboard');
    Route::get('/referrals/transactions', [App\Http\Controllers\Admin\ReferralController::class, 'transactions'])->name('referrals.transactions');
    Route::get('/referrals/wallets', [App\Http\Controllers\Admin\ReferralController::class, 'wallets'])->name('referrals.wallets');
    Route::post('/referrals/wallets/{id}/adjust', [App\Http\Controllers\Admin\ReferralController::class, 'adjustWallet'])->name('referrals.wallets.adjust');
    Route::post('/referrals/wallets/{id}/lock', [App\Http\Controllers\Admin\ReferralController::class, 'toggleLock'])->name('referrals.wallets.lock');
    Route::get('/referrals/milestones', [App\Http\Controllers\Admin\ReferralController::class, 'milestones'])->name('referrals.milestones');
    Route::post('/referrals/milestones', [App\Http\Controllers\Admin\ReferralController::class, 'updateMilestones'])->name('referrals.milestones.update');
    Route::get('/referrals/fraud', [App\Http\Controllers\Admin\ReferralController::class, 'fraud'])->name('referrals.fraud');
    Route::post('/referrals/{id}/approve', [App\Http\Controllers\Admin\ReferralController::class, 'approveReferral'])->name('referrals.approve');
    Route::post('/referrals/{id}/reject', [App\Http\Controllers\Admin\ReferralController::class, 'rejectReferral'])->name('referrals.reject');
    Route::get('/referrals/settings', [App\Http\Controllers\Admin\ReferralController::class, 'settings'])->name('referrals.settings');
    Route::post('/referrals/settings', [App\Http\Controllers\Admin\ReferralController::class, 'updateSettings'])->name('referrals.settings.update');
    Route::get('/referrals/redemptions', [App\Http\Controllers\Admin\ReferralController::class, 'redemptions'])->name('referrals.redemptions');
    Route::get('/referrals/email-preview', [App\Http\Controllers\Admin\ReferralController::class, 'emailPreview'])->name('referrals.email-preview');
    Route::post('/referrals/email-test', [App\Http\Controllers\Admin\ReferralController::class, 'emailTest'])->name('referrals.email-test');
    Route::get('/referrals/{id}', [App\Http\Controllers\Admin\ReferralController::class, 'show'])->name('referrals.show');
});

// Public Referral Shortlink & Token Routes
Route::get('/r/{code}', [ReferralController::class, 'handleShortLink'])->name('referral.shortlink');
Route::get('/invite/{token}', [ReferralController::class, 'handleInviteToken'])->name('referral.invite.token');
