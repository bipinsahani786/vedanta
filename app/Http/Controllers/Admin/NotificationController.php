<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
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
        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->update(['read_at' => now()]);

        return back();
    }

    public function markAllRead()
    {
        DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
