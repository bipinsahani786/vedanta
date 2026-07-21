<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->whereIn('notifiable_id', $adminIds)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->through(function($n) {
                $n->data = json_decode($n->data);
                return $n;
            });

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        DB::table('notifications')
            ->where('id', $id)
            ->whereIn('notifiable_id', $adminIds)
            ->update(['read_at' => now()]);

        return back();
    }

    public function markAllRead()
    {
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->whereIn('notifiable_id', $adminIds)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
