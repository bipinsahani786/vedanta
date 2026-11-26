<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SpreadsheetExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected function buildFilteredUsersQuery(Request $request, bool $applyRoleAndStatus = true)
    {
        $query = User::whereIn('role', ['candidate', 'employer']);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($applyRoleAndStatus) {
            if ($request->filled('role') && in_array($request->role, ['candidate', 'employer'], true)) {
                $query->where('role', $request->role);
            }

            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        $baseQuery = $this->buildFilteredUsersQuery($request, false);
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'candidates' => (clone $baseQuery)->where('role', 'candidate')->count(),
            'employers' => (clone $baseQuery)->where('role', 'employer')->count(),
            'active' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
        ];

        $users = $this->buildFilteredUsersQuery($request, true)
            ->with('profile')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function export(Request $request, SpreadsheetExportService $exporter)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (!in_array($format, ['xlsx', 'csv'], true)) {
            $format = 'xlsx';
        }

        $query = $this->buildFilteredUsersQuery($request, true)
            ->with(['profile.category', 'profile.subject', 'employerProfile']);

        $headers = [
            'User ID',
            'Name',
            'Email',
            'Phone',
            'Role',
            'Account Status',
            'Email Verified',
            'VPA ID / School Name',
            'Category & Subject / Contact Person',
            'Onboarding / Verification Status',
            'Plan / Paid Amount',
            'Referral Code',
            'Joined Date (IST)',
        ];

        $rows = [];
        $query->chunkById(500, function ($chunk) use (&$rows): void {
            foreach ($chunk as $user) {
                $profile = $user->profile;
                $employer = $user->employerProfile;

                $vpaOrSchool = $user->role === 'candidate'
                    ? ($profile?->vpa_id ?? '')
                    : ($employer?->school_name ?? '');

                $categoryOrContact = $user->role === 'candidate'
                    ? trim(implode(' - ', array_filter([
                        $profile?->category?->name,
                        $profile?->subject?->name,
                    ])))
                    : ($employer?->contact_person ?? '');

                if ($user->role === 'candidate') {
                    if ($profile) {
                        $statusText = $profile->isRegistrationCompleted() ? 'Completed' : $profile->pending_reason;
                        if ($profile->is_verified) {
                            $statusText .= ' (Verified)';
                        }
                    } else {
                        $statusText = 'Profile Pending';
                    }
                } else {
                    $statusText = 'Employer Account';
                }

                $planInfo = $user->role === 'candidate' && $profile
                    ? (($profile->plan_type ? ucfirst($profile->plan_type) : 'None') . ' / Rs. ' . number_format((float) ($profile->paid_amount ?? 0), 2))
                    : '';

                $rows[] = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? '',
                    ucfirst((string) $user->role),
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $vpaOrSchool,
                    $categoryOrContact,
                    $statusText,
                    $planInfo,
                    $user->referral_code ?? '',
                    $user->created_at ? $user->created_at->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ' IST' : '',
                ];
            }
        }, 'users.id', 'id');

        usort($rows, static fn (array $a, array $b): int => ((int) $b[0]) <=> ((int) $a[0]));

        $roleSlug = $request->filled('role') && in_array($request->role, ['candidate', 'employer'], true)
            ? $request->role
            : 'all';
        $fileBaseName = 'vedanta_users_' . $roleSlug . '_' . now('Asia/Kolkata')->format('Ymd_His');

        return $format === 'csv'
            ? $exporter->downloadCsv($fileBaseName, $headers, $rows)
            : $exporter->downloadXlsx($fileBaseName, $headers, $rows, 'Vedanta Users');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin status.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User account has been {$status}.");
    }

    public function impersonate($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot impersonate an admin.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'Cannot impersonate an inactive user.');
        }

        // Store admin id in session to return later
        $adminId = auth()->id();
        
        Auth::login($user);
        
        session()->put('impersonate_admin_id', $adminId);
        session()->save();

        if ($user->role === 'employer') {
            return redirect()->route('employer.dashboard');
        }

        return redirect()->route('candidate.dashboard');
    }

    public function leaveImpersonate()
    {
        if (session()->has('impersonate_admin_id')) {
            $adminId = session('impersonate_admin_id');
            $admin = User::find($adminId);

            if ($admin && $admin->role === 'admin') {
                session()->forget('impersonate_admin_id');
                Auth::login($admin);
                session()->save();
                return redirect()->route('admin.users.index')->with('success', 'Returned to admin dashboard.');
            }
        }

        return redirect('/');
    }
}
