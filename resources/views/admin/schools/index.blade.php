@extends('layouts.admin')

@section('title', 'Schools CRM')
@section('subtitle', 'Manage school accounts, saved institution profile details, photos/logos, and job postings.')

@section('actions')
    <a href="{{ route('admin.schools.create') }}"
       class="px-5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
        <i class="fas fa-plus-circle text-xs"></i>
        <span>Create School Account</span>
    </a>
@endsection

@section('content')
<div class="space-y-6" x-data="{ viewMode: localStorage.getItem('school_crm_view_mode') || 'table' }">

    <!-- KPI Filter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @php
            $cards = [
                ['key' => null, 'label' => 'Total Schools', 'count' => $stats['total'], 'badgeClass' => 'bg-blue-50 text-blue-600', 'icon' => 'fa-school'],
                ['key' => 'active', 'label' => 'Active', 'count' => $stats['active'], 'badgeClass' => 'bg-emerald-50 text-emerald-600', 'icon' => 'fa-check-circle'],
                ['key' => 'inactive', 'label' => 'Inactive', 'count' => $stats['inactive'], 'badgeClass' => 'bg-rose-50 text-rose-600', 'icon' => 'fa-ban'],
                ['key' => 'profile_complete', 'label' => 'Profile Complete', 'count' => $stats['profile_complete'], 'badgeClass' => 'bg-teal-50 text-teal-600', 'icon' => 'fa-id-card'],
                ['key' => 'profile_incomplete', 'label' => 'Missing Details/Photo', 'count' => $stats['profile_incomplete'], 'badgeClass' => 'bg-amber-50 text-amber-600', 'icon' => 'fa-exclamation-triangle'],
                ['key' => 'with_jobs', 'label' => 'With Posted Jobs', 'count' => $stats['with_jobs'], 'badgeClass' => 'bg-indigo-50 text-indigo-600', 'icon' => 'fa-briefcase'],
            ];
        @endphp
        @foreach($cards as $c)
            @php
                $isActive = $c['key'] === null ? !request('status') : request('status') === $c['key'];
            @endphp
            <a href="{{ request()->fullUrlWithQuery(['status' => $c['key'], 'page' => null]) }}"
               class="bg-white rounded-2xl border p-4 shadow-sm transition-all flex items-center justify-between {{ $isActive ? 'border-blue-600 ring-2 ring-blue-500/20' : 'border-gray-100 hover:border-gray-300' }}">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ $c['label'] }}</p>
                    <p class="text-2xl font-black text-gray-900 mt-0.5">{{ $c['count'] }}</p>
                </div>
                <div class="w-9 h-9 rounded-xl {{ $c['badgeClass'] }} flex items-center justify-center text-sm shrink-0">
                    <i class="fas {{ $c['icon'] }}"></i>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Search & View Switcher -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <form action="{{ route('admin.schools.index') }}" method="GET" class="flex flex-wrap items-center gap-2 flex-1 min-w-[260px]">
                <div class="relative flex-1 min-w-[220px] max-w-md">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search school name, contact, email, phone, address..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <select name="status" onchange="this.form.submit()"
                        class="rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 bg-white focus:border-blue-600 outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Accounts</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Accounts</option>
                    <option value="profile_complete" {{ request('status') === 'profile_complete' ? 'selected' : '' }}>Profile &amp; Photo Complete</option>
                    <option value="profile_incomplete" {{ request('status') === 'profile_incomplete' ? 'selected' : '' }}>Missing Details / Photo</option>
                    <option value="with_jobs" {{ request('status') === 'with_jobs' ? 'selected' : '' }}>With Posted Jobs</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.schools.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-gray-500 hover:bg-gray-100">
                        Reset
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 font-medium">
                    Showing {{ $schools->firstItem() ?? 0 }} to {{ $schools->lastItem() ?? 0 }} of {{ $schools->total() }}
                </span>
                <div class="bg-gray-100 rounded-xl p-1 flex items-center gap-1">
                    <button type="button" @click="viewMode = 'table'; localStorage.setItem('school_crm_view_mode', 'table')"
                            :class="viewMode === 'table' ? 'bg-white text-blue-600 shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer">
                        <i class="fas fa-list mr-1"></i> Table
                    </button>
                    <button type="button" @click="viewMode = 'cards'; localStorage.setItem('school_crm_view_mode', 'cards')"
                            :class="viewMode === 'cards' ? 'bg-white text-blue-600 shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer">
                        <i class="fas fa-th-large mr-1"></i> Cards
                    </button>
                </div>
            </div>
        </div>

        <!-- Cards Grid View -->
        <div x-show="viewMode === 'cards'" x-cloak class="p-5">
            @if($schools->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($schools as $school)
                        @php
                            $sp = $school->employerProfile;
                            $logoUrl = ($sp && $sp->logo_path) ? Storage::url($sp->logo_path) : '';
                            $photoUploadUrl = route('admin.schools.photo', $school->id);
                            $schoolTitle = $sp?->school_name ?: $school->name;
                            $hasName = !empty($sp?->school_name);
                            $hasContact = !empty($sp?->contact_person);
                            $hasAddress = !empty($sp?->address);
                            $hasPhoto = !empty($sp?->logo_path);
                        @endphp
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/40 p-5 flex flex-col justify-between gap-4 hover:border-blue-300 transition-all shadow-xs">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <button type="button"
                                                onclick='openAdminPhotoModal(@json($schoolTitle), @json("School #" . $school->id), @json($logoUrl), @json($photoUploadUrl), "School Logo / Photo")'
                                                class="relative group shrink-0 rounded-xl focus:outline-none cursor-pointer"
                                                title="Click to enlarge or update school photo">
                                            @if($logoUrl)
                                                <img src="{{ $logoUrl }}" alt="{{ $schoolTitle }}" class="w-12 h-12 rounded-xl object-cover border-2 border-white shadow-sm group-hover:ring-2 group-hover:ring-blue-400">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-lg border border-blue-200">
                                                    {{ strtoupper(substr($schoolTitle, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-blue-600 text-white text-[9px] flex items-center justify-center shadow">
                                                <i class="fas {{ $logoUrl ? 'fa-search-plus' : 'fa-camera' }}"></i>
                                            </span>
                                        </button>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-gray-900 truncate">{{ $schoolTitle }}</h4>
                                            <p class="text-[11px] text-gray-500 truncate">Contact: {{ $sp?->contact_person ?: $school->name }} (#{{ $school->id }})</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0 {{ $school->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $school->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <div class="mt-3 text-xs text-gray-600 space-y-1">
                                    <div class="truncate"><i class="fas fa-envelope w-4 text-gray-400"></i> {{ $school->email }}</div>
                                    <div><i class="fas fa-phone-alt w-4 text-gray-400"></i> {{ $school->phone ?? 'N/A' }}</div>
                                    <div class="truncate"><i class="fas fa-map-marker-alt w-4 text-gray-400"></i> {{ $sp?->address ?: 'Address not saved' }}</div>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-1 text-[10px] font-bold">
                                    <span class="px-1.5 py-0.5 rounded {{ $hasName ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">Name {{ $hasName ? '✓' : '✗' }}</span>
                                    <span class="px-1.5 py-0.5 rounded {{ $hasContact ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">Contact {{ $hasContact ? '✓' : '✗' }}</span>
                                    <span class="px-1.5 py-0.5 rounded {{ $hasAddress ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">Address {{ $hasAddress ? '✓' : '✗' }}</span>
                                    <span class="px-1.5 py-0.5 rounded {{ $hasPhoto ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">Photo {{ $hasPhoto ? '✓' : '✗' }}</span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-gray-200 flex items-center justify-between gap-2 flex-wrap">
                                <span class="text-xs font-bold text-gray-600">
                                    <i class="fas fa-briefcase text-blue-500 mr-1"></i> {{ $school->job_posts_count }} Jobs ({{ $school->live_jobs_count }} Live)
                                </span>
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.schools.edit', $school->id) }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.schools.show', $school->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-gray-400 text-sm">No schools match your filters.</div>
            @endif
        </div>

        <!-- Table View -->
        <div x-show="viewMode === 'table'" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">School &amp; Photo</th>
                        <th class="px-6 py-3 text-left">Contact &amp; Address</th>
                        <th class="px-6 py-3 text-left">Saved Profile Fields</th>
                        <th class="px-6 py-3 text-left">Jobs &amp; Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($schools as $school)
                        @php
                            $sp = $school->employerProfile;
                            $logoUrl = ($sp && $sp->logo_path) ? Storage::url($sp->logo_path) : '';
                            $photoUploadUrl = route('admin.schools.photo', $school->id);
                            $schoolTitle = $sp?->school_name ?: $school->name;
                            $hasName = !empty($sp?->school_name);
                            $hasContact = !empty($sp?->contact_person);
                            $hasAddress = !empty($sp?->address);
                            $hasPhoto = !empty($sp?->logo_path);
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    <button type="button"
                                            onclick='openAdminPhotoModal(@json($schoolTitle), @json("School #" . $school->id), @json($logoUrl), @json($photoUploadUrl), "School Logo / Photo")'
                                            class="relative group shrink-0 rounded-xl focus:outline-none cursor-pointer"
                                            title="Click to view or update school photo">
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" alt="{{ $schoolTitle }}" class="w-11 h-11 rounded-xl object-cover border border-gray-200 group-hover:ring-2 group-hover:ring-blue-400">
                                        @else
                                            <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-base border border-blue-200">
                                                {{ strtoupper(substr($schoolTitle, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-blue-600 text-white text-[8px] flex items-center justify-center shadow">
                                            <i class="fas {{ $logoUrl ? 'fa-search-plus' : 'fa-camera' }}"></i>
                                        </span>
                                    </button>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.schools.show', $school->id) }}" class="font-bold text-gray-900 hover:text-blue-600 transition-colors block">
                                            {{ $schoolTitle }}
                                        </a>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            Account: <span class="font-medium text-gray-700">{{ $school->name }}</span> (#{{ $school->id }})
                                        </div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">Joined {{ $school->created_at?->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 align-top text-xs text-gray-600 space-y-1">
                                <div><span class="text-gray-400">Contact:</span> <strong>{{ $sp?->contact_person ?: 'Not set' }}</strong></div>
                                <div><i class="fas fa-envelope text-gray-400 w-3.5"></i> {{ $school->email }}</div>
                                <div><i class="fas fa-phone-alt text-gray-400 w-3.5"></i> {{ $school->phone ?? 'N/A' }}</div>
                                <div class="max-w-xs truncate text-gray-500" title="{{ $sp?->address }}"><i class="fas fa-map-marker-alt text-gray-400 w-3.5"></i> {{ $sp?->address ?: 'Address missing' }}</div>
                            </td>

                            <td class="px-6 py-4 align-top">
                                <div class="flex flex-wrap gap-1 max-w-[190px] text-[10px] font-bold">
                                    <span class="px-2 py-0.5 rounded {{ $hasName ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">School Name {{ $hasName ? '✓' : '✗' }}</span>
                                    <span class="px-2 py-0.5 rounded {{ $hasContact ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">Contact {{ $hasContact ? '✓' : '✗' }}</span>
                                    <span class="px-2 py-0.5 rounded {{ $hasAddress ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">Address {{ $hasAddress ? '✓' : '✗' }}</span>
                                    <span class="px-2 py-0.5 rounded {{ $hasPhoto ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">Photo {{ $hasPhoto ? '✓' : '✗' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 align-top text-xs space-y-1.5">
                                <div>
                                    @if($school->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700">Inactive</span>
                                    @endif
                                </div>
                                <div class="font-semibold text-gray-700">
                                    {{ $school->job_posts_count }} Jobs <span class="text-emerald-600">({{ $school->live_jobs_count }} Live)</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 align-top text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.schools.show', $school->id) }}"
                                       class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-bold transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('admin.schools.edit', $school->id) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition-colors">
                                        Edit
                                    </a>
                                    @if($school->is_active)
                                        <a href="{{ route('admin.users.impersonate', $school->id) }}" target="_blank"
                                           class="p-2 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs" title="Login as School">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.schools.toggle-status', $school->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 rounded-lg {{ $school->is_active ? 'bg-rose-50 hover:bg-rose-100 text-rose-600' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600' }} text-xs cursor-pointer"
                                                title="{{ $school->is_active ? 'Deactivate School' : 'Activate School' }}">
                                            <i class="fas {{ $school->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-school text-3xl mb-2 block text-gray-200"></i>
                                No school accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schools->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $schools->links() }}
            </div>
        @endif
    </div>
</div>

@include('admin.crm.partials.photo-modal')
@endsection
