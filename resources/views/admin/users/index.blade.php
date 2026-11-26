@extends('layouts.admin')

@section('title', 'Users Management')

@section('actions')
    @php
        $exportParams = request()->only(['role', 'status', 'search']);
    @endphp
    <div class="flex flex-wrap items-center gap-2">
        <button type="button" onclick="openImportUsersModal()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-bold transition-all inline-flex items-center gap-2 shadow-sm cursor-pointer">
            <i class="fas fa-file-import"></i>
            <span>Import Users</span>
        </button>
        <a href="{{ route('admin.users.export', array_merge($exportParams, ['format' => 'xlsx'])) }}"
           class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold transition-all inline-flex items-center gap-2 shadow-sm">
            <i class="fas fa-file-excel"></i>
            <span>Export Excel (.xlsx)</span>
        </a>
        <a href="{{ route('admin.users.export', array_merge($exportParams, ['format' => 'csv'])) }}"
           class="px-4 py-2 bg-[#031b4e] hover:bg-blue-900 text-white rounded-xl text-xs sm:text-sm font-bold transition-all inline-flex items-center gap-2 shadow-sm">
            <i class="fas fa-file-csv"></i>
            <span>Export CSV (.csv)</span>
        </a>
    </div>
@endsection

@section('content')
    @if(session('warning'))
        <div class="mb-5 bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-4 rounded-xl shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 text-sm font-medium">
                <i class="fas fa-exclamation-triangle text-amber-500"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-900 text-sm">&times;</button>
        </div>
    @endif

    @if($errors->has('import_file') || $errors->has('duplicate_mode'))
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-sm">
            <p class="font-bold"><i class="fas fa-exclamation-circle mr-1"></i> Import Error</p>
            <ul class="list-disc list-inside mt-1 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('import_report'))
        @php $report = session('import_report'); @endphp
        <div class="mb-5 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-blue-600"></i> User Import Summary
                </h3>
                <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Created: {{ $report['created'] ?? 0 }}</span>
                    <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Updated: {{ $report['updated'] ?? 0 }}</span>
                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">Skipped: {{ $report['skipped'] ?? 0 }}</span>
                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700">Failed: {{ $report['failed'] ?? 0 }}</span>
                </div>
            </div>
            @if(!empty($report['issues']))
                <div class="mt-3 max-h-40 overflow-y-auto bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-gray-700 space-y-1">
                    @foreach($report['issues'] as $issue)
                        <div>&bull; {{ $issue }}</div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Users -->
        <a href="{{ request()->fullUrlWithQuery(['role' => null, 'status' => null, 'page' => null]) }}" class="bg-white rounded-2xl p-6 shadow-sm border {{ (!request('role') && !request('status')) ? 'border-blue-500 ring-1 ring-blue-500' : 'border-gray-100' }} flex items-center gap-4 hover:border-blue-500 transition-all">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Users</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</h3>
            </div>
        </a>

        <!-- Active Users -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'active', 'role' => null, 'page' => null]) }}" class="bg-white rounded-2xl p-6 shadow-sm border {{ request('status') === 'active' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-100' }} flex items-center gap-4 hover:border-green-500 transition-all">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Active Users</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['active'] }}</h3>
            </div>
        </a>

        <!-- Candidates -->
        <a href="{{ request()->fullUrlWithQuery(['role' => 'candidate', 'status' => null, 'page' => null]) }}" class="bg-white rounded-2xl p-6 shadow-sm border {{ request('role') === 'candidate' ? 'border-purple-500 ring-1 ring-purple-500' : 'border-gray-100' }} flex items-center gap-4 hover:border-purple-500 transition-all">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Candidates</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['candidates'] }}</h3>
            </div>
        </a>

        <!-- Employers -->
        <a href="{{ request()->fullUrlWithQuery(['role' => 'employer', 'status' => null, 'page' => null]) }}" class="bg-white rounded-2xl p-6 shadow-sm border {{ request('role') === 'employer' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100' }} flex items-center gap-4 hover:border-orange-500 transition-all">
            <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xl">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Employers</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $stats['employers'] }}</h3>
            </div>
        </a>
    </div>

    <!-- Filters & Table -->
    <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex gap-2 w-full sm:w-auto overflow-x-auto">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors shrink-0 {{ !request('role') ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</a>
                <a href="{{ route('admin.users.index', ['role' => 'candidate']) }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors shrink-0 {{ request('role') === 'candidate' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Candidates</a>
                <a href="{{ route('admin.users.index', ['role' => 'employer']) }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors shrink-0 {{ request('role') === 'employer' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Employers</a>
            </div>
            
            <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-auto">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-blue-500"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." class="block w-full sm:w-72 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm transition-all duration-200 shadow-sm" style="padding-left: 2.5rem;">
                </div>
            </form>
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
                                    @if($user->role === 'candidate')
                                        <a href="{{ route('admin.crm.show', $user->id) }}" class="px-2.5 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors inline-flex items-center gap-1" title="View Candidate Details">
                                            <i class="fas fa-id-card"></i> View Details
                                        </a>
                                    @endif
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

    <!-- Import Users Modal -->
    <div id="importUsersModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/50 p-4">
        <div class="absolute inset-0" onclick="closeImportUsersModal()"></div>
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 border border-gray-100 relative z-10 max-h-[92vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-file-import text-blue-600"></i> Import Users (Excel / CSV)
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Bulk create or update candidates &amp; employers using the official template.</p>
                </div>
                <button type="button" onclick="closeImportUsersModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>

            <!-- Download Template Box -->
            <div class="mb-5 p-4 rounded-xl bg-blue-50/70 border border-blue-200 space-y-2.5">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-xs font-bold text-blue-950">Step 1: Download Sample Template</p>
                        <p class="text-[11px] text-blue-800 mt-0.5">
                            Required: <strong>Name</strong>, <strong>Email</strong>. Optional: <strong>Phone</strong>, <strong>Role</strong> (<code>candidate</code> / <code>employer</code>), <strong>Temporary Password</strong> (min 8 chars; blank auto-generates), <strong>Account Status</strong> (<code>active</code> / <code>inactive</code>), <strong>VPA ID / School Name</strong>, <strong>Contact Person</strong>, <strong>Address</strong>.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 pt-1">
                    <a href="{{ route('admin.users.import-template', ['format' => 'xlsx']) }}"
                       class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-xs transition-colors">
                        <i class="fas fa-download"></i> Download Excel Template (.xlsx)
                    </a>
                    <a href="{{ route('admin.users.import-template', ['format' => 'csv']) }}"
                       class="px-3.5 py-2 rounded-lg bg-white hover:bg-gray-50 text-gray-800 border border-gray-300 text-xs font-bold inline-flex items-center gap-1.5 shadow-xs transition-colors">
                        <i class="fas fa-file-csv"></i> Download CSV Template (.csv)
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return handleImportSubmit(this)">
                @csrf

                <div>
                    <label for="users_import_file" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Step 2: Upload Filled Spreadsheet <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="users_import_file" name="import_file" accept=".xlsx,.csv,.txt" required
                           class="w-full text-xs text-gray-700 border border-gray-300 rounded-xl p-2.5 bg-gray-50 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    <p class="text-[11px] text-gray-400 mt-1">Accepted formats: <code>.xlsx</code>, <code>.csv</code> (max 10 MB, up to 5,000 rows per upload).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">If Email Already Exists</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex items-start gap-2 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs">
                            <input type="radio" name="duplicate_mode" value="skip" checked class="mt-0.5 text-blue-600 focus:ring-blue-500">
                            <span>
                                <strong class="block text-gray-900">Skip Existing</strong>
                                <span class="text-[11px] text-gray-500">Only create new users</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-2 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs">
                            <input type="radio" name="duplicate_mode" value="update" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                            <span>
                                <strong class="block text-gray-900">Update Existing</strong>
                                <span class="text-[11px] text-gray-500">Sync details for non-admins</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-900">
                    <i class="fas fa-shield-alt mr-1 text-amber-600"></i>
                    Imported users store encrypted passwords only and are required to set a private password on their next login. Admin accounts cannot be created or modified via import.
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeImportUsersModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="importUsersSubmitBtn" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm inline-flex items-center gap-1.5 transition-colors cursor-pointer">
                        <i class="fas fa-upload"></i>
                        <span>Upload &amp; Import Users</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openImportUsersModal() {
            document.getElementById('importUsersModal').classList.remove('hidden');
        }

        function closeImportUsersModal() {
            document.getElementById('importUsersModal').classList.add('hidden');
        }

        function handleImportSubmit(form) {
            const btn = document.getElementById('importUsersSubmitBtn');
            if (btn) {
                setTimeout(() => {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Importing...</span>';
                }, 10);
            }
            return true;
        }
    </script>
    @endpush
@endsection
