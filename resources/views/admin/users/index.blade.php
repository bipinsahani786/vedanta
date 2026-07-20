@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Users</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Active Users</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['active'] }}</h3>
            </div>
        </div>

        <!-- Candidates -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Candidates</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['candidates'] }}</h3>
            </div>
        </div>

        <!-- Employers -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xl">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Employers</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['employers'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ !request('role') ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</a>
                <a href="{{ route('admin.users.index', ['role' => 'candidate']) }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ request('role') === 'candidate' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Candidates</a>
                <a href="{{ route('admin.users.index', ['role' => 'employer']) }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ request('role') === 'employer' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Employers</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full admin-table text-left">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr>
                            <td class="font-semibold text-gray-800">
                                {{ $user->name }}
                                @if($user->role === 'candidate' && $user->profile && $user->profile->is_verified)
                                    <i class="fas fa-check-circle text-blue-500 ml-1" title="Verified Candidate"></i>
                                @endif
                            </td>
                            <td class="text-gray-500">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'candidate')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Candidate
                                    </span>
                                @elseif($user->role === 'employer')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Employer
                                    </span>
                                @endif
                            </td>
                            <td class="text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        <i class="fas fa-circle text-[8px] mr-1.5"></i> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        <i class="fas fa-circle text-[8px] mr-1.5"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    @if($user->is_active)
                                        <a href="{{ route('admin.users.impersonate', $user->id) }}" target="_blank" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors" title="Login as User">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if($user->is_active)
                                            <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Deactivate User" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="p-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors" title="Activate User">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <div class="text-4xl text-gray-200 mb-2"><i class="fas fa-users-slash"></i></div>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
@endsection
