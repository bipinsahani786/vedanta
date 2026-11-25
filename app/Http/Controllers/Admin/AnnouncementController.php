<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $announcements = $query->latest('updated_at')->paginate(10)->withQueryString();
        $liveNoticeId = Announcement::active()->latest('updated_at')->value('id');

        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::where('is_active', true)->count(),
            'inactive' => Announcement::where('is_active', false)->count(),
        ];

        return view('admin.announcements.index', compact('announcements', 'liveNoticeId', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAnnouncement($request);
        $data['is_active'] = $request->boolean('is_active');

        Announcement::create($data);

        return back()->with('success', 'Notice / announcement created successfully.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validateAnnouncement($request);
        $data['is_active'] = $request->boolean('is_active');

        $announcement->update($data);

        return back()->with('success', 'Notice / announcement updated successfully.');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->is_active = ! $announcement->is_active;
        $announcement->save();

        $statusLabel = $announcement->is_active ? 'shown on the website' : 'hidden from the website';

        return back()->with('success', "Notice is now {$statusLabel}.");
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return back()->with('success', 'Notice / announcement deleted successfully.');
    }

    private function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'title' => 'nullable|string|max:120',
            'message' => 'required|string|max:500',
            'link_text' => 'nullable|string|max:60',
            'link_url' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    $trimmed = trim((string) $value);
                    if ($trimmed === '') {
                        return;
                    }
                    if (! str_starts_with($trimmed, '/') && ! str_starts_with($trimmed, 'https://') && ! str_starts_with($trimmed, 'http://')) {
                        $fail('The link URL must start with /, http://, or https://.');
                    }
                },
            ],
            'style' => 'required|in:info,warning,success,urgent',
        ]);
    }
}
