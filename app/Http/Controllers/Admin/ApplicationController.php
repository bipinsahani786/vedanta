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
            'remarks' => 'nullable|string',
            'interview_date' => 'nullable|date',
            'interview_link' => 'nullable|string|max:255',
        ]);

        $application = JobApplication::findOrFail($id);
        
        $oldStatus = $application->status;
        $application->status = $request->status;
        
        if ($request->has('remarks')) {
            $application->remarks = $request->remarks;
        }

        if ($request->has('interview_date') && !empty($request->interview_date)) {
            $application->interview_date = $request->interview_date;
            $application->interview_link = $request->interview_link;
        } else if ($request->status === 'applied') {
            // Optional: Clear schedule if status is set back to applied
            $application->interview_date = null;
            $application->interview_link = null;
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
