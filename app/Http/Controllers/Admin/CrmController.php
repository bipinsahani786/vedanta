<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmFollowUp;
use App\Models\JobApplication;
use App\Models\ServiceChargeInvoice;
use App\Models\User;
use App\Models\CandidateRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrmController extends Controller
{
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

        if ($locationId = $request->input('location_id')) {
            $query->whereHas('profile', function($q) use ($locationId) {
                $q->where('preferred_location_id', $locationId);
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
        $locations = \App\Models\Location::all();

        return view('admin.crm.index', compact('candidates', 'sortField', 'sortDirection', 'subjects', 'qualifications', 'locations'));
    }

    public function show($id)
    {
        $candidate = User::where('role', 'candidate')
            ->with(['profile', 'applications.jobPost'])
            ->findOrFail($id);

        $followUps = CrmFollowUp::where('candidate_id', $id)->with('admin')->orderBy('created_at', 'desc')->get();
        $invoices = ServiceChargeInvoice::where('candidate_id', $id)->with('jobApplication.jobPost')->orderBy('created_at', 'desc')->get();
        $rating = CandidateRating::where('candidate_id', $id)->first();

        return view('admin.crm.show', compact('candidate', 'followUps', 'invoices', 'rating'));
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

        $invoice->status = $request->status;
        if ($request->status === 'paid' && $invoice->getOriginal('status') !== 'paid') {
            $invoice->payment_date = now();
            
            $candidate = User::find($invoice->candidate_id);
            if ($candidate && $candidate->profile) {
                $candidate->profile->decrement('pending_amount', $invoice->amount);
                $candidate->profile->increment('paid_amount', $invoice->amount);
            }
        }
        $invoice->save();

        return back()->with('success', 'Invoice status updated.');
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
        
        session(['admin_id' => auth()->id()]);
        
        Auth::login($candidate);
        return redirect()->route('candidate.wizard');
    }

    public function create()
    {
        return view('admin.crm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'candidate',
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->profile()->create([]);

        $adminId = auth()->id();
        Auth::login($user);
        session(['admin_id' => $adminId]);

        return redirect()->route('candidate.wizard')->with('success', 'Candidate registered. Please complete their profile details below.');
    }

    public function switchBack()
    {
        if (session()->has('admin_id')) {
            $adminId = session()->pull('admin_id');
            Auth::loginUsingId($adminId);
            return redirect()->route('admin.crm.index')->with('success', 'Returned to Admin Portal.');
        }
        return redirect('/');
    }
}
