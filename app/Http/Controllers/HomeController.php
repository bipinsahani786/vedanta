<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Category;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\ClientLogo;
use App\Models\Subject;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $recentJobs = JobPost::with(['category', 'subject', 'state', 'city', 'qualification'])
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();
            
        $categories = Category::where('is_active', true)->withCount(['jobs' => function ($query) {
            $query->where('status', 'approved');
        }])->get();

        $services = Service::where('is_active', true)->get();
        $testimonials = Testimonial::where('is_active', true)->latest()->get();
        $clients = ClientLogo::where('is_active', true)->latest()->get();

        $totalJobs = JobPost::where('status', 'approved')->count();
        $totalApplications = \App\Models\JobApplication::count();
        $totalEmployers = \App\Models\User::where('role', 'employer')->count();

        return view('welcome', compact('recentJobs', 'categories', 'services', 'testimonials', 'clients', 'totalJobs', 'totalApplications', 'totalEmployers'));
    }
    public function categoryJobs($id)
    {
        $category = Category::findOrFail($id);
        $jobs = JobPost::with([
                'category',
                'subject',
                'state',
                'city',
                'qualification'
            ])
            ->where('category_id', $id)
            ->where('status', 'approved')
            ->latest()
            ->get();
            
        $activeSubjectIds = $jobs->pluck('subject_id')->filter()->unique()->toArray();
        $subjects = Subject::whereIn('id', $activeSubjectIds)->where('is_active', true)->get();
        return view('category-jobs', compact('category', 'subjects', 'jobs'));
    }

    public function serviceDetails($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        return view('service-details', compact('service'));
    }


    public function jobs(\Illuminate\Http\Request $request)
    {
        $query = JobPost::with(['category', 'subject', 'state', 'city', 'qualification'])
            ->where('status', 'approved');

        if ($request->filled('q') || $request->filled('search')) {
            $searchTerm = trim($request->input('q') ?? $request->input('search'));
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('school_name', 'like', "%{$searchTerm}%")
                  ->orWhereHas('subject', function($sq) use ($searchTerm) {
                      $sq->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('category', function($cq) use ($searchTerm) {
                      $cq->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('city', function($cty) use ($searchTerm) {
                      $cty->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('state', function($st) use ($searchTerm) {
                      $st->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('qualification', function($qq) use ($searchTerm) {
                      $qq->where('name', 'like', "%{$searchTerm}%");
                  });

                if (preg_match('/\d+/', $searchTerm, $numMatches)) {
                    $idNum = (int)$numMatches[0];
                    $q->orWhere('id', $idNum);
                    if ($idNum > 1000) {
                        $q->orWhere('id', $idNum - 1000);
                    }
                }
            });
        }

        if ($request->filled('state')) {
            $query->where('state_id', $request->state);
        }

        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }

        if ($request->filled('class')) {
            $query->where('category_id', $request->class);
        }

        if ($request->filled('categories') && is_array($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'salary_high':
                $query->orderByRaw('COALESCE(salary_max, salary_min, 0) DESC');
                break;
            case 'salary_low':
                $query->orderByRaw('COALESCE(salary_min, salary_max, 0) ASC');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $jobs = $query->paginate(12)->withQueryString();

        $states = \App\Models\State::where('is_active', true)->orderBy('name')->get();
        $subjects = \App\Models\Subject::where('is_active', true)->orderBy('name')->get();
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
            
        return view('jobs', compact('jobs', 'states', 'subjects', 'categories'));
    }

    public function jobSuggestions(\Illuminate\Http\Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['jobs' => []]);
        }

        $jobs = JobPost::where('status', 'approved')
            ->where(function($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhere('school_name', 'like', "%{$q}%")
                      ->orWhereHas('subject', fn($sq) => $sq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('city', fn($cty) => $cty->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('state', fn($st) => $st->where('name', 'like', "%{$q}%"));

                if (preg_match('/\d+/', $q, $numMatches)) {
                    $idNum = (int)$numMatches[0];
                    $query->orWhere('id', $idNum);
                    if ($idNum > 1000) {
                        $query->orWhere('id', $idNum - 1000);
                    }
                }
            })
            ->with(['city', 'subject'])
            ->latest()
            ->take(6)
            ->get()
            ->map(function($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title ?? 'Teaching Opportunity',
                    'subject' => $job->subject?->name,
                    'city' => $job->city?->name,
                    'url' => route('jobs.show', $job->id)
                ];
            });

        return response()->json(['jobs' => $jobs]);
    }

    public function storeContact(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string'
        ]);

        \App\Models\ContactLead::create($request->only(['name', 'email', 'phone', 'message']));

        // Notify Admin of new lead
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if ($adminUser) {
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\NewContactLead',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $adminUser->id,
                'data' => json_encode([
                    'title' => 'New Contact Query',
                    'message' => $request->name . ' has submitted a new contact query.',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Thank you for your message. We will get back to you shortly.'
            ]);
        }

        return back()->with('success', 'Thank you for your message. We will get back to you shortly.');
    }
   

        public function services()
        {
            $services = Service::where('is_active', true)->latest()->get();
            return view('services', compact('services'));
        }

        public function getSubjects($categoryId)
    {
        $category = \App\Models\Category::findOrFail($categoryId);
        return response()->json($category->subjects()->where('is_active', true)->orderBy('name')->get());
    }

    public function getSpecializations($subjectId)
    {
        $subject = \App\Models\Subject::findOrFail($subjectId);
        return response()->json($subject->specializations()->where('is_active', true)->orderBy('name')->get());
    }
}
