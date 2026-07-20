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

        // Analytics based on current filtered query
        $stats = [
            'total' => (clone $query)->count(),
            'applied' => (clone $query)->where('status', 'applied')->count(),
            'shortlisted' => (clone $query)->where('status', 'shortlisted')->count(),
            'hired' => (clone $query)->where('status', 'hired')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];

        return view('admin.applications.index', compact('applications', 'stats'));
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
            // Send ApplicationForwarded to Candidate
            Mail::to($application->candidate->email)->send(new ApplicationForwarded($application));
            
            // Note: Send CandidateForwardedMail to School
            $employerEmail = $application->jobPost->user->email ?? $application->jobPost->email;
            if ($employerEmail) {
                Mail::to($employerEmail)->send(new \App\Mail\CandidateForwardedMail($application));
            }
        } elseif ($request->status !== $oldStatus) {
            // For other status changes, send the generic ApplicationStatusMail
            Mail::to($application->candidate->email)->send(new \App\Mail\ApplicationStatusMail($application));
        }

        if ($request->status !== $oldStatus) {
            // DB Notification for Candidate Dashboard
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\ApplicationStatusUpdated',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $application->candidate_id,
                'data' => json_encode([
                    'title' => 'Application Update',
                    'message' => 'Your application for ' . $application->jobPost->title . ' is now ' . $request->status . '.',
                    'application_id' => $application->id
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $application->save();

        return back()->with('success', 'Application status updated successfully.');
    }
}
