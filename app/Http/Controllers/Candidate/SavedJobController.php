<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\SavedJob;
use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    /**
     * Display a listing of the candidate's saved jobs.
     */
    public function index()
    {
        $user = auth()->user();
        $savedJobs = SavedJob::where('user_id', $user->id)
            ->with(['jobPost.category', 'jobPost.subject', 'jobPost.city', 'jobPost.state', 'jobPost.qualification'])
            ->latest()
            ->paginate(12);

        return view('candidate.saved_jobs.index', compact('savedJobs'));
    }

    /**
     * Toggle saving/bookmarking a job for the authenticated candidate.
     */
    public function toggle(Request $request, JobPost $job)
    {
        $userId = auth()->id();

        $existing = SavedJob::where('user_id', $userId)
            ->where('job_post_id', $job->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
            $message = 'Job removed from saved list.';
        } else {
            SavedJob::create([
                'user_id' => $userId,
                'job_post_id' => $job->id,
            ]);
            $saved = true;
            $message = 'Job saved successfully!';
        }

        $totalSaved = SavedJob::where('user_id', $userId)->count();

        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'saved' => $saved,
                'message' => $message,
                'total_saved' => $totalSaved,
            ]);
        }

        return back()->with('success', $message);
    }
}
