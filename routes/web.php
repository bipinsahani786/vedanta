<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/jobs', [\App\Http\Controllers\HomeController::class, 'jobs'])->name('jobs');
Route::get('/jobs/{job}', [\App\Http\Controllers\JobController::class, 'show'])->name('jobs.show');

Route::view('/about', 'about')->name('about');
Route::view('/services', 'services')->name('services');
Route::view('/hiring-process', 'hiring')->name('hiring');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [\App\Http\Controllers\HomeController::class, 'storeContact'])->name('contact.store');
Route::view('/apply', 'apply')->name('apply');
Route::get('/post-job', [\App\Http\Controllers\JobController::class, 'showPostJobForm'])->name('post-job');
Route::post('/post-job', [\App\Http\Controllers\JobController::class, 'storeJobQuery'])->name('post-job.store');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/media', 'media')->name('media');

// Resume Builder (Public)
Route::get('/resume-builder', [\App\Http\Controllers\ResumeBuilderController::class, 'index'])->name('resume.builder');
Route::post('/resume-builder/download', [\App\Http\Controllers\ResumeBuilderController::class, 'download'])->name('resume.builder.download');

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

// OTP Login Routes
Route::get('/login/otp', [\App\Http\Controllers\AuthController::class, 'showOtpForm'])->name('login.otp');
Route::post('/login/otp/send', [\App\Http\Controllers\AuthController::class, 'sendOtp'])->name('login.otp.send');
Route::post('/login/otp/verify', [\App\Http\Controllers\AuthController::class, 'verifyOtp'])->name('login.otp.verify');

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    if (auth()->user()->role === 'employer') return redirect('/employer/dashboard');
    return redirect('/candidate/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Candidate Auth Routes
Route::get('/register', [\App\Http\Controllers\CandidateAuthController::class, 'showRegistrationForm'])->name('candidate.register');
Route::post('/register', [\App\Http\Controllers\CandidateAuthController::class, 'register'])->name('candidate.register.post');

// Candidate Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('candidate')->name('candidate.')->group(function () {
    // Registration Wizard
    Route::get('/wizard', [\App\Http\Controllers\Candidate\RegistrationWizardController::class, 'show'])->name('wizard');
    Route::post('/wizard/step1', [\App\Http\Controllers\Candidate\RegistrationWizardController::class, 'saveStep1'])->name('wizard.step1');
    Route::post('/wizard/step2', [\App\Http\Controllers\Candidate\RegistrationWizardController::class, 'saveStep2'])->name('wizard.step2');
    Route::post('/wizard/payment', [\App\Http\Controllers\Candidate\RegistrationWizardController::class, 'initiatePayment'])->name('wizard.payment');
    Route::match(['get', 'post'], '/wizard/callback', [\App\Http\Controllers\Candidate\RegistrationWizardController::class, 'callback'])->name('wizard.callback');

    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'candidate') return abort(403);
        $profile = auth()->user()->profile;
        if (!$profile->initial_fee_paid && !$profile->is_fee_paid) return redirect()->route('candidate.wizard');
        return view('candidate.dashboard', compact('profile'));
    })->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\Candidate\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\Candidate\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/password', [\App\Http\Controllers\Candidate\ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/agreement', [\App\Http\Controllers\Candidate\AgreementController::class, 'show'])->name('agreement.show');
    Route::post('/agreement/sign', [\App\Http\Controllers\Candidate\AgreementController::class, 'sign'])->name('agreement.sign');
    Route::get('/agreement/download', [\App\Http\Controllers\Candidate\AgreementController::class, 'download'])->name('agreement.download');

    Route::get('/payment', [\App\Http\Controllers\Candidate\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/process', [\App\Http\Controllers\Candidate\PaymentController::class, 'process'])->name('payment.process');
    Route::match(['get', 'post'], '/payment/callback', [\App\Http\Controllers\Candidate\PaymentController::class, 'callback'])->name('payment.callback');

    Route::get('/applications', [\App\Http\Controllers\Candidate\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/available', [\App\Http\Controllers\Candidate\ApplicationController::class, 'available'])->name('applications.available');
    Route::post('/applications/{job}/apply', [\App\Http\Controllers\Candidate\ApplicationController::class, 'apply'])->name('applications.apply');
});

// Employer Auth Routes
Route::get('/employer/register', [\App\Http\Controllers\EmployerAuthController::class, 'showRegistrationForm'])->name('employer.register');
Route::post('/employer/register', [\App\Http\Controllers\EmployerAuthController::class, 'register'])->name('employer.register.post');

// Employer Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'employer') return abort(403);
        return view('employer.dashboard');
    })->name('dashboard');

    Route::get('/applicants', [\App\Http\Controllers\Employer\ApplicantController::class, 'index'])->name('applicants.index');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Master Data
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('qualifications', \App\Http\Controllers\Admin\QualificationController::class);
    Route::resource('locations', \App\Http\Controllers\Admin\LocationController::class);

    // Job Posts
    Route::resource('jobs', \App\Http\Controllers\Admin\JobController::class);
    Route::post('jobs/{job}/approve', [\App\Http\Controllers\Admin\JobController::class, 'approve'])->name('jobs.approve');
    Route::post('jobs/{job}/reject', [\App\Http\Controllers\Admin\JobController::class, 'reject'])->name('jobs.reject');

    // CRM & Invoices
    Route::get('/crm', [\App\Http\Controllers\Admin\CrmController::class, 'index'])->name('crm.index');
    Route::get('/crm/candidate/{id}', [\App\Http\Controllers\Admin\CrmController::class, 'show'])->name('crm.show');
    Route::post('/crm/candidate/{id}/follow-up', [\App\Http\Controllers\Admin\CrmController::class, 'storeFollowUp'])->name('crm.followup.store');
    Route::post('/crm/candidate/{id}/invoice', [\App\Http\Controllers\Admin\CrmController::class, 'storeInvoice'])->name('crm.invoice.store');
    Route::put('/crm/invoice/{id}', [\App\Http\Controllers\Admin\CrmController::class, 'updateInvoiceStatus'])->name('crm.invoice.update');
    Route::post('/crm/candidate/{id}/toggle-verification', [\App\Http\Controllers\Admin\CrmController::class, 'toggleVerification'])->name('crm.candidate.verify');
    Route::post('/crm/candidate/{id}/rate', [\App\Http\Controllers\Admin\CrmController::class, 'rateCandidate'])->name('crm.candidate.rate');
    Route::get('/crm/candidate/{id}/magic-login', [\App\Http\Controllers\Admin\CrmController::class, 'magicLogin'])->name('crm.candidate.magic-login');

    // Applications & Transactions
    Route::get('/applications', [\App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/applications/{id}/status', [\App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('applications.status.update');
    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');

    // Contact Leads
    Route::get('/leads', [\App\Http\Controllers\Admin\ContactLeadController::class, 'index'])->name('leads.index');
    Route::put('/leads/{id}/status', [\App\Http\Controllers\Admin\ContactLeadController::class, 'updateStatus'])->name('leads.status.update');

    // Frontend Management
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->except(['create', 'show', 'edit']);
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->except(['create', 'show', 'edit']);
    Route::resource('clients', \App\Http\Controllers\Admin\ClientLogoController::class)->except(['create', 'show', 'edit'])->parameters(['clients' => 'clientLogo']);
});
