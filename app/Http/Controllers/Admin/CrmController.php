<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmFollowUp;
use App\Models\JobApplication;
use App\Models\ServiceChargeInvoice;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\CandidateRating;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CrmController extends Controller
{
    public function create()
    {
        $categories = \App\Models\Category::all();
        $subjects = \App\Models\Subject::all();
        $qualifications = \App\Models\Qualification::all();
        $states = \App\Models\State::where('is_active', true)->get();
        $cities = \App\Models\City::where('is_active', true)->get();

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
            // 1. Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'candidate',
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // 2. Handle File Uploads
            $resumePath = $request->hasFile('resume') ? $request->file('resume')->store('resumes', 'public') : null;
            $profilePhotoPath = $request->hasFile('profile_photo') ? $request->file('profile_photo')->store('profile_photos', 'public') : null;
            $livePhotoPath = $request->hasFile('live_photo') ? $request->file('live_photo')->store('live_photos', 'public') : null;
            $salarySlipPath = $request->hasFile('salary_slip') ? $request->file('salary_slip')->store('salary_slips', 'public') : null;
            $offerLetterPath = $request->hasFile('offer_letter') ? $request->file('offer_letter')->store('offer_letters', 'public') : null;
            $agreementPdfPath = $request->hasFile('agreement_pdf') ? $request->file('agreement_pdf')->store('agreements', 'public') : null;

            $paymentId = $request->payment_method . '-ADMIN-' . strtoupper(uniqid());

            // 3. Create Candidate Profile
            $profile = CandidateProfile::create([
                'user_id' => $user->id,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'category_id' => $request->category_id,
                'subject_id' => $request->subject_id,
                'highest_qualification_id' => $request->highest_qualification_id,
                'experience_years' => $request->experience_years,
                'current_salary' => $request->current_salary,
                'expected_salary' => $request->expected_salary,
                'preferred_state_id' => $request->preferred_state_id,
                'preferred_city_id' => $request->preferred_city_id,
                'english_fluency' => $request->english_fluency,
                'residential_preference' => $request->residential_preference,
                'availability_to_join' => $request->availability_to_join,
                'current_school' => $request->current_school,
                
                'resume_path' => $resumePath,
                'profile_photo_path' => $profilePhotoPath,
                'live_photo_path' => $livePhotoPath,
                'salary_slip_path' => $salarySlipPath,
                'offer_letter_path' => $offerLetterPath,
                'agreement_pdf_path' => $agreementPdfPath,

                'is_profile_complete' => true,
                'is_fee_paid' => true,
                'plan_type' => $request->plan_type,
                'plan_started_at' => now(),
                'payment_id' => $paymentId,
                'registration_completed_at' => now(),
                
                'is_terms_agreed' => true,
                'is_agreement_signed' => $agreementPdfPath ? true : false,
            ]);

            // 4. Create Payment Transaction
            PaymentTransaction::create([
                'candidate_id' => $user->id,
                'transaction_id' => $paymentId,
                'amount' => $request->payment_amount,
                'type' => 'registration_fee',
                'status' => 'success',
                'gateway_response' => [
                    'note' => 'Manually collected by Admin', 
                    'admin_notes' => $request->payment_notes,
                    'payment_method' => $request->payment_method
                ],
            ]);

            return redirect()->route('admin.crm.show', $user->id)->with('success', 'Candidate manually onboarded successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Manual Onboard Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to onboard candidate: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $user = User::where('role', 'candidate')->findOrFail($id);
        $profile = $user->profile;

        $categories = \App\Models\Category::all();
        $subjects = \App\Models\Subject::all();
        $qualifications = \App\Models\Qualification::all();
        $states = \App\Models\State::where('is_active', true)->get();
        $cities = \App\Models\City::where('is_active', true)->get();

        return view('admin.crm.edit', compact('user', 'profile', 'categories', 'subjects', 'qualifications', 'states', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'candidate')->findOrFail($id);
        $profile = $user->profile;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
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
        ]);

        try {
            // Update User
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            $user->update($userData);

            // Handle File Uploads (only update if a new file is provided)
            $updates = [
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'category_id' => $request->category_id,
                'subject_id' => $request->subject_id,
                'highest_qualification_id' => $request->highest_qualification_id,
                'experience_years' => $request->experience_years,
                'current_salary' => $request->current_salary,
                'expected_salary' => $request->expected_salary,
                'preferred_state_id' => $request->preferred_state_id,
                'preferred_city_id' => $request->preferred_city_id,
                'english_fluency' => $request->english_fluency,
                'residential_preference' => $request->residential_preference,
                'availability_to_join' => $request->availability_to_join,
                'current_school' => $request->current_school,
            ];

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
            }

            $profile->update($updates);

            return redirect()->route('admin.crm.show', $user->id)->with('success', 'Candidate profile updated successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Profile Update Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to update candidate profile: ' . $e->getMessage()]);
        }
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'candidate')
            ->with(['profile', 'applications.jobPost', 'applications' => function($q) {
                $q->where('status', 'hired');
            }]);

        // Search text
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Advanced Filters
        if ($subjectId = $request->input('subject_id')) {
            $query->whereHas('profile', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }

        if ($experience = $request->input('experience')) {
            $query->whereHas('profile', function($q) use ($experience) {
                $q->where('experience_years', '>=', $experience);
            });
        }

        if ($qualificationId = $request->input('qualification_id')) {
            $query->whereHas('profile', function($q) use ($qualificationId) {
                $q->where('highest_qualification_id', $qualificationId);
            });
        }

        if ($stateId = $request->input('state_id')) {
            $query->whereHas('profile', function($q) use ($stateId) {
                $q->where('preferred_state_id', $stateId);
            });
        }

        if ($cityId = $request->input('city_id')) {
            $query->whereHas('profile', function($q) use ($cityId) {
                $q->where('preferred_city_id', $cityId);
            });
        }

        if ($gender = $request->input('gender')) {
            $query->whereHas('profile', function($q) use ($gender) {
                $q->where('gender', $gender);
            });
        }

        if ($englishFluency = $request->input('english_fluency')) {
            $query->whereHas('profile', function($q) use ($englishFluency) {
                $q->where('english_fluency', $englishFluency);
            });
        }

        if ($availability = $request->input('availability')) {
            $query->whereHas('profile', function($q) use ($availability) {
                $q->where('availability', $availability);
            });
        }

        if ($salary = $request->input('salary')) {
            $query->whereHas('profile', function($q) use ($salary) {
                $q->where('expected_salary', 'like', "%{$salary}%")
                  ->orWhere('current_salary', 'like', "%{$salary}%");
            });
        }

        if ($planAmount = $request->input('plan_amount')) {
            $query->whereHas('profile', function($q) use ($planAmount) {
                $q->where('paid_amount', $planAmount);
            });
        }

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
        $subjects = \App\Models\Subject::all();
        $qualifications = \App\Models\Qualification::all();
        $states = \App\Models\State::all();
        $cities = \App\Models\City::all();

        return view('admin.crm.index', compact('candidates', 'sortField', 'sortDirection', 'subjects', 'qualifications', 'states', 'cities'));
    }

    public function show($id)
    {
        $candidate = User::where('role', 'candidate')
            ->with(['profile', 'applications.jobPost'])
            ->findOrFail($id);

        $followUps = CrmFollowUp::where('candidate_id', $id)->with('admin')->orderBy('created_at', 'desc')->get();
        $invoices = ServiceChargeInvoice::where('candidate_id', $id)->with('jobApplication.jobPost')->orderBy('created_at', 'desc')->get();
        $rating = CandidateRating::where('candidate_id', $id)->first();
        $payments = \App\Models\PaymentTransaction::where('candidate_id', $id)->where('status', 'success')->get();

        $availableJobs = \App\Models\JobPost::where('status', 'published')
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
            'color' => 'bg-blue-500'
        ]);

        // 2. Payments
        foreach ($payments as $payment) {
            $history->push([
                'date' => $payment->created_at,
                'type' => 'payment',
                'title' => 'Payment Received',
                'description' => 'Payment of ₹' . number_format($payment->amount, 2) . ' was successful.',
                'icon' => 'fas fa-rupee-sign',
                'color' => 'bg-green-500'
            ]);
        }

        // 3. Job Applications
        foreach ($candidate->applications as $app) {
            $history->push([
                'date' => $app->created_at,
                'type' => 'job_applied',
                'title' => 'Applied for Job',
                'description' => 'Applied for ' . ($app->jobPost->title ?? 'a job') . ' at ' . ($app->jobPost->school_name ?? 'a school') . '.',
                'icon' => 'fas fa-briefcase',
                'color' => 'bg-indigo-500'
            ]);
            
            if ($app->status === 'hired') {
                 $history->push([
                    'date' => $app->updated_at,
                    'type' => 'job_hired',
                    'title' => 'Hired',
                    'description' => 'Candidate was hired for ' . ($app->jobPost->title ?? 'a job') . '.',
                    'icon' => 'fas fa-check-circle',
                    'color' => 'bg-emerald-500'
                ]);
            } elseif ($app->status === 'waitlisted') {
                $history->push([
                    'date' => $app->updated_at,
                    'type' => 'job_waitlisted',
                    'title' => 'Waitlisted',
                    'description' => 'Candidate was waitlisted for ' . ($app->jobPost->title ?? 'a job') . '.',
                    'icon' => 'fas fa-hourglass-half',
                    'color' => 'bg-amber-500'
                ]);
            }
        }

        // 4. Follow-ups
        foreach ($followUps as $fu) {
            $history->push([
                'date' => $fu->created_at,
                'type' => 'follow_up',
                'title' => 'Follow-up Added',
                'description' => 'Notes: ' . $fu->notes,
                'icon' => 'fas fa-phone-alt',
                'color' => 'bg-yellow-500'
            ]);
        }

        // 5. Invoices
        foreach ($invoices as $invoice) {
            $history->push([
                'date' => $invoice->created_at,
                'type' => 'invoice_generated',
                'title' => 'Invoice Generated',
                'description' => 'Invoice for ₹' . number_format($invoice->amount, 2) . ' generated.',
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => 'bg-purple-500'
            ]);
            if ($invoice->status === 'paid' && $invoice->payment_date) {
                $history->push([
                    'date' => $invoice->payment_date,
                    'type' => 'invoice_paid',
                    'title' => 'Invoice Paid',
                    'description' => 'Service charge invoice for ₹' . number_format($invoice->amount, 2) . ' was paid.',
                    'icon' => 'fas fa-check-double',
                    'color' => 'bg-green-600'
                ]);
            }
        }

        $history = $history->sortByDesc('date')->values();

        return view('admin.crm.show', compact('candidate', 'followUps', 'invoices', 'rating', 'history', 'availableJobs'));
    }

    public function uploadAgreement(Request $request, $id)
    {
        $request->validate([
            'agreement_pdf' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $candidate = User::findOrFail($id);
        $profile = $candidate->profile;

        if (!$profile) {
            return back()->with('error', 'Candidate profile not found.');
        }

        if ($request->hasFile('agreement_pdf')) {
            $file = $request->file('agreement_pdf');
            $fileName = 'agreements/admin_uploaded_' . $candidate->id . '_' . time() . '.pdf';
            
            // Store the file in public disk
            \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, file_get_contents($file));

            // Update profile
            $profile->update([
                'is_agreement_signed' => true,
                'agreement_pdf_path' => $fileName,
            ]);

            return back()->with('success', 'Agreement PDF uploaded successfully. The candidate can now view and download it.');
        }

        return back()->with('error', 'Failed to upload agreement.');
    }

    public function storeFollowUp(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
            'follow_up_date' => 'nullable|date',
            'status' => 'required|in:open,closed'
        ]);

        CrmFollowUp::create([
            'candidate_id' => $id,
            'notes' => $request->notes,
            'follow_up_date' => $request->follow_up_date,
            'status' => $request->status,
            'created_by' => auth()->id()
        ]);

        return back()->with('success', 'Follow-up added successfully.');
    }

    public function storeInvoice(Request $request, $id)
    {
        $request->validate([
            'job_application_id' => 'required|exists:job_applications,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date'
        ]);

        $invoice = ServiceChargeInvoice::create([
            'candidate_id' => $id,
            'job_application_id' => $request->job_application_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'pending'
        ]);

        $candidate = User::findOrFail($id);
        $candidate->profile->increment('pending_amount', $request->amount);

        // Notify Candidate
        \Illuminate\Support\Facades\DB::table('notifications')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\ServiceChargeInvoiceGenerated',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $id,
            'data' => json_encode([
                'title' => 'New Service Charge Invoice',
                'message' => 'An invoice for ₹' . number_format($request->amount, 2) . ' has been generated for your recent job placement.',
                'amount' => $request->amount,
                'invoice_id' => $invoice->id
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Invoice created successfully.');
    }

    public function updateInvoiceStatus(Request $request, $invoiceId)
    {
        $invoice = ServiceChargeInvoice::findOrFail($invoiceId);
        
        $request->validate([
            'status' => 'required|in:pending,paid,overdue'
        ]);

        $invoice->update([
            'status' => $request->status,
            'payment_date' => $request->status === 'paid' ? now() : null
        ]);

        if ($request->status === 'paid' && $invoice->getOriginal('status') !== 'paid') {
            $candidate = User::find($invoice->candidate_id);
            if ($candidate && $candidate->profile) {
                $candidate->profile->decrement('pending_amount', $invoice->amount);
                $candidate->profile->increment('paid_amount', $invoice->amount);
            }
        }
        $invoice->save();

        return back()->with('success', 'Invoice status updated.');
    }

    public function adjustInvoice(Request $request, $invoiceId)
    {
        $invoice = ServiceChargeInvoice::findOrFail($invoiceId);
        
        $request->validate([
            'deduction' => 'required|numeric|min:0|max:' . $invoice->late_fee
        ]);

        $deduction = $request->deduction;
        
        if ($deduction > 0) {
            $invoice->update([
                'late_fee' => $invoice->late_fee - $deduction
            ]);

            $candidate = User::find($invoice->candidate_id);
            if ($candidate && $candidate->profile) {
                // Ensure we don't drop pending amount below 0 artificially
                $newPending = max(0, $candidate->profile->pending_amount - $deduction);
                $candidate->profile->update(['pending_amount' => $newPending]);
            }
        }

        return back()->with('success', 'Invoice adjusted successfully. ₹' . $deduction . ' waived from late fine.');
    }

    public function assignJob(Request $request, $id)
    {
        $request->validate([
            'job_post_id' => 'required|exists:job_posts,id'
        ]);

        $candidate = User::findOrFail($id);

        if (\App\Models\JobApplication::where('job_post_id', $request->job_post_id)->where('candidate_id', $candidate->id)->exists()) {
            return back()->with('error', 'Candidate is already applied to this job.');
        }

        \App\Models\JobApplication::create([
            'job_post_id' => $request->job_post_id,
            'candidate_id' => $candidate->id,
            'status' => 'applied',
            'match_score' => 0 // Manually assigned by admin
        ]);

        return back()->with('success', 'Job application assigned successfully.');
    }

    public function toggleVerification($id)
    {
        $candidate = User::findOrFail($id);
        if ($candidate->profile) {
            $candidate->profile->is_verified = !$candidate->profile->is_verified;
            $candidate->profile->save();
        }
        return back()->with('success', 'Candidate verification status updated.');
    }

    public function rateCandidate(Request $request, $id)
    {
        $request->validate([
            'communication' => 'required|integer|min:1|max:5',
            'subject_knowledge' => 'required|integer|min:1|max:5',
            'demo_performance' => 'required|integer|min:1|max:5',
            'english_fluency' => 'required|integer|min:1|max:5',
            'discipline' => 'required|integer|min:1|max:5',
            'remarks' => 'nullable|string'
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
                'rated_by' => auth()->id()
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
}
