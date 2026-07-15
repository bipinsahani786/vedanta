<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Category;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\ClientLogo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $recentJobs = JobPost::with(['category', 'subject', 'location', 'qualification'])
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

        return view('welcome', compact('recentJobs', 'categories', 'services', 'testimonials', 'clients'));
    }
    public function categoryJobs($id)
    {
        $category = Category::findOrFail($id);
        $subjects = $category->subjects()->where('is_active', true)->get();
        $jobs = JobPost::with([
                'category',
                'subject',
                'location',
                'qualification'
            ])
            ->where('category_id', $id)
            ->where('status', 'approved')
            ->latest()
            ->get();
        return view('category-jobs', compact('category', 'subjects', 'jobs'));
    }

    public function serviceDetails($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        return view('service-details', compact('service'));
    }


    public function jobs(\Illuminate\Http\Request $request)
    {
        $query = JobPost::with(['category', 'subject', 'location', 'qualification'])
            ->where('status', 'approved');

        if ($request->filled('state')) {
            $query->whereHas('location', function($q) use ($request) {
                $q->where('state', $request->state);
            });
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

        $jobs = $query->orderBy('created_at', 'desc')->paginate(12);

        $states = \App\Models\Location::whereNotNull('state')->where('state', '!=', '')->distinct()->orderBy('state')->pluck('state');
        $subjects = \App\Models\Subject::where('is_active', true)->orderBy('name')->get();
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
            
        return view('jobs', compact('jobs', 'states', 'subjects', 'categories'));
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

    public function getCities($state)
    {
        $cities = \App\Models\Location::where('state', $state)
            ->where('is_active', true)
            ->orderBy('city')
            ->get(['id', 'city']);
        return response()->json($cities);
    }
}
