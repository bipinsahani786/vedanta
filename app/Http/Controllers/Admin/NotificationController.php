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
            ->through(function ($n) {
                $n->data = json_decode($n->data);
                $n->target_url = self::resolveTargetUrl($n->data, $n->type ?? null);

                return $n;
            });

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, $id)
    {
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        $notif = DB::table('notifications')
            ->where('id', $id)
            ->whereIn('notifiable_id', $adminIds)
            ->first();

        if ($notif) {
            DB::table('notifications')
                ->where('id', $id)
                ->update(['read_at' => $notif->read_at ?? now()]);

            if (! $request->boolean('stay')) {
                $data = is_string($notif->data) ? json_decode($notif->data) : $notif->data;
                if ($targetUrl = self::resolveTargetUrl($data, $notif->type ?? null)) {
                    return redirect($targetUrl);
                }
            }
        }

        return back();
    }

    /**
     * Resolve an admin destination URL for a notification payload when present.
     */
    public static function resolveTargetUrl(?object $data, ?string $type = null): ?string
    {
        if ($data && ! empty($data->candidate_id)) {
            return route('admin.crm.show', (int) $data->candidate_id);
        }

        if ($data && ! empty($data->job_id)) {
            return route('admin.jobs.show', (int) $data->job_id);
        }

        if ($data && ! empty($data->lead_id)) {
            return route('admin.leads.show', (int) $data->lead_id);
        }

        if ($type && str_contains($type, 'NewContactLead')) {
            return route('admin.leads.index');
        }

        if ($type && str_contains($type, 'NewJobPosted')) {
            return route('admin.jobs.index', ['status' => 'pending']);
        }

        if ($data && ! empty($data->action_url) && is_string($data->action_url)) {
            $appUrl = rtrim(url('/'), '/');
            if (str_starts_with($data->action_url, '/') || str_starts_with($data->action_url, $appUrl)) {
                return $data->action_url;
            }
        }

        return null;
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
