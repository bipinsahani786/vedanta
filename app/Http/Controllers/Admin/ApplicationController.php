<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationForwarded;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with(['candidate', 'jobPost.user']);

        if ($search = $request->input('search')) {
            $query->whereHas('candidate', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('jobPost', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.applications.index', compact('applications'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:applied,shortlisted,hired,rejected',
            'remarks' => 'nullable|string'
        ]);

        $application = JobApplication::findOrFail($id);
        
        $oldStatus = $application->status;
        $application->status = $request->status;
        
        if ($request->has('remarks')) {
            $application->remarks = $request->remarks;
        }

        if ($request->status === 'shortlisted' && $oldStatus !== 'shortlisted') {
            $application->is_forwarded = true;
            // Send email to candidate
            Mail::to($application->candidate->email)->send(new ApplicationForwarded($application));
        }

        $application->save();

        return back()->with('success', 'Application status updated successfully.');
    }
}
