<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile')->whereIn('role', ['candidate', 'employer']);

        if ($request->has('role') && in_array($request->role, ['candidate', 'employer'])) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20);

        $stats = [
            'total' => User::whereIn('role', ['candidate', 'employer'])->count(),
            'candidates' => User::where('role', 'candidate')->count(),
            'employers' => User::where('role', 'employer')->count(),
            'active' => User::whereIn('role', ['candidate', 'employer'])->where('is_active', true)->count(),
            'inactive' => User::whereIn('role', ['candidate', 'employer'])->where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
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
