<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Jobs\SendBulkTemplateEmail;
use Illuminate\Http\Request;

class BulkEmailController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.bulk_email.index', compact('templates'));
    }

    public function searchUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            if ($request->role === 'candidate') {
                $query->where('role', 'candidate');
            } elseif ($request->role === 'employer') {
                $query->where('role', 'employer');
            }
        } else {
            $query->whereIn('role', ['candidate', 'employer']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20, ['id', 'name', 'email', 'role']);
        return response()->json($users);
    }

    public function send(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $userIds = $request->user_ids;
        $subject = $request->subject;
        $body = $request->body;

        // Process in chunks or queue
        SendBulkTemplateEmail::dispatch($userIds, $subject, $body);

        return redirect()->back()->with('success', 'Bulk email has been queued and will be sent shortly.');
    }
}
