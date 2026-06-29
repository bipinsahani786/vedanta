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
            
        $categories = Category::withCount(['jobs' => function ($query) {
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
        return view('category-jobs', compact('category', 'jobs'));
    }

    public function jobs()
    {
        $jobs = JobPost::with(['category', 'subject', 'location', 'qualification'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('jobs', compact('jobs'));
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
}
