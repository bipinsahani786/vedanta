@extends('layouts.admin')

@section('title', 'CRM & Follow-ups')
@section('subtitle', 'Manage candidates, track hiring status, generate invoices, and log follow-ups.')

@section('actions')
    <a href="{{ route('admin.crm.create') }}" class="px-5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
        <i class="fas fa-user-plus text-xs"></i>
        <span>Manually Onboard Candidate</span>
    </a>
@endsection

@section('content')

{{-- Analytics Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}" class="bg-card-bg border {{ request('status') === null ? 'border-blue-500 shadow-md ring-1 ring-blue-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-blue-500 transition-all">
        <div class="absolute inset-0 bg-blue-500/5 group-hover:bg-blue-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Total Candidates</p>
        <h4 class="text-2xl font-extrabold text-blue-500 relative z-10">{{ $stats['total'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'active_paid', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'active_paid' ? 'border-green-500 shadow-md ring-1 ring-green-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-green-500 transition-all">
        <div class="absolute inset-0 bg-green-500/5 group-hover:bg-green-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Active / Paid</p>
        <h4 class="text-2xl font-extrabold text-green-500 relative z-10">{{ $stats['active_paid'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending_dues', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'pending_dues' ? 'border-rose-500 shadow-md ring-1 ring-rose-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-rose-500 transition-all">
        <div class="absolute inset-0 bg-rose-500/5 group-hover:bg-rose-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Pending Dues</p>
        <h4 class="text-2xl font-extrabold text-rose-500 relative z-10">{{ $stats['pending_dues'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'signed', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'signed' ? 'border-accent-blue shadow-md ring-1 ring-accent-blue' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-accent-blue transition-all">
        <div class="absolute inset-0 bg-accent-blue/5 group-hover:bg-accent-blue/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Signed Agreement</p>
        <h4 class="text-2xl font-extrabold text-accent-blue relative z-10">{{ $stats['signed'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'incomplete', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'incomplete' ? 'border-red-500 shadow-md ring-1 ring-red-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-red-500 transition-all">
        <div class="absolute inset-0 bg-red-500/5 group-hover:bg-red-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Incomplete</p>
        <h4 class="text-2xl font-extrabold text-red-500 relative z-10">{{ $stats['incomplete'] }}</h4>
    </a>
</div>

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4">
    <div class="flex justify-between items-center mb-4">
        <div class="text-sm text-text-dark/50 font-medium">
            Showing {{ $candidates->firstItem() ?? 0 }} to {{ $candidates->lastItem() ?? 0 }} of {{ $candidates->total() }} entries
        </div>
        <button type="button" onclick="document.getElementById('advanced-filters').classList.toggle('hidden')" class="text-sm font-semibold text-accent-blue flex items-center gap-2 hover:text-accent-blue-hover transition-colors">
            <i class="fas fa-filter"></i> Advanced Filters
        </button>
    </div>

    <form action="{{ route('admin.crm.index') }}" method="GET" class="space-y-4">
        <div class="flex items-center relative">
            <i class="fas fa-search absolute left-3 text-text-dark/40 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." 
                   class="w-full pl-9 pr-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
            @if(request()->anyFilled(['search', 'subject_id', 'experience', 'qualification_id', 'state_id', 'city_id', 'gender', 'english_fluency', 'availability', 'plan_amount']))
                <a href="{{ route('admin.crm.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-400 transition-colors text-sm font-bold flex items-center gap-1">
                    <i class="fas fa-times"></i> Clear Filters
                </a>
            @endif
        </div>

        <div id="advanced-filters" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 {{ request()->anyFilled(['subject_id', 'experience', 'qualification_id', 'state_id', 'city_id', 'gender', 'english_fluency', 'availability', 'plan_amount']) ? '' : 'hidden' }}">
            <select name="subject_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
            </select>

            <select name="qualification_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Qualifications</option>
                @foreach($qualifications as $qualification)
                    <option value="{{ $qualification->id }}" {{ request('qualification_id') == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                @endforeach
            </select>

            <select name="state_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All States</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                @endforeach
            </select>
            <select name="city_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>

            <select name="experience" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">Min Experience</option>
                <option value="1" {{ request('experience') == '1' ? 'selected' : '' }}>1+ Years</option>
                <option value="3" {{ request('experience') == '3' ? 'selected' : '' }}>3+ Years</option>
                <option value="5" {{ request('experience') == '5' ? 'selected' : '' }}>5+ Years</option>
                <option value="10" {{ request('experience') == '10' ? 'selected' : '' }}>10+ Years</option>
            </select>

            <select name="gender" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Genders</option>
                <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
            </select>

            <select name="english_fluency" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">English Fluency</option>
                <option value="beginner" {{ request('english_fluency') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="intermediate" {{ request('english_fluency') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="fluent" {{ request('english_fluency') == 'fluent' ? 'selected' : '' }}>Fluent</option>
            </select>

            <button type="submit" class="w-full bg-accent-blue text-white rounded-lg px-4 py-2 text-sm font-bold shadow hover:bg-accent-blue-hover transition-colors">
                Apply Filters
            </button>
        </div>
    </form>
</div>

{{-- Data Table --}}
<div class="bg-card-bg rounded-b-2xl border border-card-border overflow-x-auto shadow-xl">
    <table class="w-full text-left border-collapse admin-table">
        <thead>
            <tr>
                <th class="w-10 text-center">
                    <input type="checkbox" id="selectAllCandidates" onclick="toggleSelectAllCandidates(this)" class="rounded border-card-border text-accent-blue focus:ring-accent-blue cursor-pointer" title="Select All Candidates">
                </th>
                @php
                    $route = 'admin.crm.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'name', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Candidate
                        @if(request('sort_by') === 'name')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>Registration Status</th>
                <th>Hired Roles</th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'created_at', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Joined
                        @if(request('sort_by') === 'created_at' || !request('sort_by'))
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>Admin Rating</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($candidates as $candidate)
            <tr class="group">
                <td class="text-center">
                    <input type="checkbox" name="candidate_ids[]" value="{{ $candidate->id }}" class="candidate-checkbox rounded border-card-border text-accent-blue focus:ring-accent-blue cursor-pointer" onchange="updateBulkActionState()">
                </td>
                <td>
                    <div class="font-semibold text-text-main group-hover:text-accent-blue transition-colors">{{ $candidate->name }}</div>
                    <div class="text-xs text-text-dark/50 flex flex-col gap-0.5 mt-1">
                        <span><i class="fas fa-envelope text-[10px] w-3"></i> {{ $candidate->email }}</span>
                        <span><i class="fas fa-phone-alt text-[10px] w-3"></i> {{ $candidate->phone }}</span>
                    </div>
                    @if($candidate->profile && $candidate->profile->plan_type === 'standard' && !$candidate->profile->is_fee_paid)
                        <div class="mt-2">
                            <span class="bg-red-500/10 text-red-500 px-2 py-0.5 rounded flex items-center gap-1 text-[10px] font-bold w-max" title="Standard Plan Placement Fee Pending">
                                <i class="fas fa-exclamation-triangle"></i> ₹500 Due
                            </span>
                        </div>
                    @endif
                </td>
                <td>
                    @if($candidate->profile && $candidate->profile->is_fee_paid)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-check-circle"></i> Active / Paid
                        </span>
                    @elseif($candidate->profile && $candidate->profile->is_agreement_signed)
                        <span class="bg-accent-blue/10 text-accent-blue px-2.5 py-1 rounded-lg text-[10px] font-bold border border-accent-blue/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-signature"></i> Signed
                        </span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-exclamation-circle"></i> Incomplete
                        </span>
                        @if($candidate->profile)
                            <div class="mt-1 text-[10px] text-red-400 font-semibold max-w-[150px] leading-tight">
                                {{ $candidate->profile->pending_reason }}
                            </div>
                        @else
                            <div class="mt-1 text-[10px] text-red-400 font-semibold max-w-[150px] leading-tight">
                                Pending Profile Completion
                            </div>
                        @endif
                    @endif
                    @if($candidate->profile)
                        @if($candidate->profile->is_agreement_signed || $candidate->profile->agreement_pdf_path || $candidate->profile->signature_data || $candidate->profile->is_fee_paid)
                            <a href="{{ route('admin.crm.candidate.download-agreement', $candidate->id) }}" target="_blank" class="mt-1.5 inline-flex items-center gap-1 px-2 py-0.5 bg-teal-500/10 text-teal-500 hover:bg-teal-500/20 rounded text-[10px] font-bold border border-teal-500/20 transition-colors w-max" title="Download Signed Agreement (Auto-generates if missing)">
                                <i class="fas fa-file-download"></i> Agreement PDF
                            </a>
                        @else
                            <a href="{{ route('admin.crm.candidate.download-agreement', ['id' => $candidate->id, 'regenerate' => 1]) }}" target="_blank" class="mt-1.5 inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-500/10 text-indigo-500 hover:bg-indigo-500/20 rounded text-[10px] font-bold border border-indigo-500/20 transition-colors w-max" title="Generate Agreement PDF on-the-fly">
                                <i class="fas fa-file-pdf"></i> Generate PDF
                            </a>
                        @endif
                    @endif
                </td>
                <td>
                    @php
                        $hired = $candidate->applications->where('status', 'hired');
                    @endphp
                    @if($hired->count() > 0)
                        <span class="text-green-400 font-bold bg-green-500/10 px-2.5 py-1 rounded-lg text-xs">{{ $hired->count() }} Role(s)</span>
                    @else
                        <span class="text-text-dark/30 text-xs font-semibold">None</span>
                    @endif
                </td>
                <td class="text-text-dark/60 text-sm">
                    {{ $candidate->created_at->format('M d, Y') }}
                </td>
                <td>
                    @if($candidate->rating)
                        <div class="flex items-center gap-1 cursor-pointer hover:opacity-80 transition-opacity" onclick="openRatingModal({{ $candidate->id }}, {{ $candidate->rating->communication }}, {{ $candidate->rating->subject_knowledge }}, {{ $candidate->rating->demo_performance }}, {{ $candidate->rating->english_fluency }}, {{ $candidate->rating->discipline }}, '{{ addslashes($candidate->rating->remarks ?? '') }}')">
                            <span class="bg-yellow-500/10 text-yellow-500 text-xs font-bold px-2 py-1 rounded border border-yellow-500/20">
                                <i class="fas fa-star text-yellow-500 mr-1"></i> {{ number_format($candidate->rating->overall_rating, 1) }}
                            </span>
                        </div>
                    @else
                        <button type="button" onclick="openRatingModal({{ $candidate->id }}, 3, 3, 3, 3, 3, '')" class="text-[10px] uppercase font-bold text-text-dark/50 hover:text-accent-blue transition-colors px-2 py-1 border border-dashed border-card-border rounded">
                            <i class="far fa-star"></i> Rate
                        </button>
                    @endif
                </td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        @if(!$candidate->profile)
                            <form action="{{ route('admin.crm.candidate.remind', $candidate->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Send an email reminder to candidate about: Pending Profile Completion?')" class="px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 hover:border-indigo-600 text-xs font-semibold transition-colors flex items-center gap-1" title="Send Reminder for: Pending Profile Completion">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </form>
                        @elseif($candidate->profile->pending_reason !== 'Completed')
                            <form action="{{ route('admin.crm.candidate.remind', $candidate->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Send an email reminder to candidate about: {{ $candidate->profile->pending_reason }}?')" class="px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 hover:border-indigo-600 text-xs font-semibold transition-colors flex items-center gap-1" title="Send Reminder for: {{ $candidate->profile->pending_reason }}">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.crm.show', $candidate->id) }}" class="px-3 py-1.5 rounded-lg bg-accent-blue/10 text-accent-blue hover:bg-accent-blue hover:text-white text-xs font-semibold transition-colors flex items-center gap-1">
                            Manage CRM <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <div class="w-16 h-16 bg-secondary-bg rounded-2xl flex items-center justify-center text-text-dark/20 text-3xl mx-auto mb-4 border border-card-border">
                        <i class="fas fa-users-slash"></i>
                    </div>
                    <p class="text-text-main font-bold text-lg mb-1">No candidates found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your search criteria.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($candidates->hasPages())
<div class="mt-6 flex justify-end">
    {{ $candidates->links('pagination::tailwind') }}
</div>
@endif

{{-- Rating Modal --}}
<div id="ratingModal" class="fixed inset-0 z-[105] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('ratingModal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-md bg-card-bg rounded-2xl shadow-2xl overflow-hidden animate-[fadeIn_0.3s_ease-out]">
        <div class="p-6 border-b border-card-border flex justify-between items-center">
            <h3 class="text-xl font-bold text-text-main">Admin Rating</h3>
            <button type="button" onclick="document.getElementById('ratingModal').classList.add('hidden')" class="text-text-dark hover:text-red-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="ratingForm" action="" method="POST" class="p-6 space-y-4">
            @csrf
            @php
                $params = [
                    'communication' => 'Communication Skills',
                    'subject_knowledge' => 'Subject Knowledge',
                    'demo_performance' => 'Demo Performance',
                    'english_fluency' => 'English Fluency',
                    'discipline' => 'Professionalism & Discipline'
                ];
            @endphp

            @foreach($params as $key => $label)
            <div class="flex items-center justify-between">
                <label class="text-sm font-medium text-text-main">{{ $label }}</label>
                <select name="{{ $key }}" id="rating_{{ $key }}" class="rounded-lg bg-secondary-bg border-card-border text-text-main focus:border-accent-blue focus:ring-0 text-sm p-1.5 w-24">
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}">{{ $i }} Stars</option>
                    @endfor
                </select>
            </div>
            @endforeach

            <div class="pt-2">
                <label class="block text-xs font-semibold text-text-dark mb-1">Remarks</label>
                <textarea name="remarks" id="rating_remarks" rows="2" class="w-full rounded-lg bg-secondary-bg border-card-border text-text-main focus:border-accent-blue focus:ring-0 text-sm placeholder-text-dark/40"></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('ratingModal').classList.add('hidden')" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-text-main bg-secondary-bg hover:bg-card-border transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-accent-blue hover:bg-accent-blue-hover transition-colors shadow-glow-blue">
                    Save Ratings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Floating Bulk Action Bar -->
<div id="bulkActionBar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden bg-card-bg border border-accent-blue/50 rounded-2xl shadow-2xl p-4 flex items-center gap-4 backdrop-blur-xl animate-bounce-short">
    <div class="flex items-center gap-2 text-sm font-bold text-text-main">
        <span class="w-7 h-7 rounded-full bg-accent-blue text-white flex items-center justify-center text-xs" id="selectedCount">0</span>
        <span>Candidates Selected</span>
    </div>
    <div class="h-6 w-px bg-card-border"></div>
    <button type="button" onclick="openBulkNotificationModal()" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2 shadow-md">
        <i class="fas fa-paper-plane"></i> Send Bulk Notification / Email
    </button>
    <button type="button" onclick="deselectAllCandidates()" class="text-xs text-text-dark/50 hover:text-red-400 font-semibold px-2">
        Cancel
    </button>
</div>

<!-- Bulk Notification Modal -->
<div id="bulkNotificationModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-card-bg border border-card-border rounded-2xl max-w-lg w-full p-6 shadow-2xl relative">
        <button type="button" onclick="closeBulkNotificationModal()" class="absolute top-4 right-4 text-text-dark/50 hover:text-text-main">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <h3 class="text-lg font-bold text-text-main mb-1 flex items-center gap-2">
            <i class="fas fa-bullhorn text-accent-blue"></i> Send Bulk Notification
        </h3>
        <p class="text-xs text-text-dark/60 mb-4">Send email notifications to <span id="modalSelectedCount" class="font-bold text-accent-blue">0</span> selected candidate(s).</p>
        
        <form action="{{ route('admin.crm.candidate.bulk-remind') }}" method="POST" id="bulkNotificationForm">
            @csrf
            <!-- Hidden input containers for selected candidate IDs -->
            <div id="bulkCandidateInputs"></div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-2">Notification Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 bg-secondary-bg rounded-xl border border-card-border cursor-pointer hover:border-accent-blue transition-all">
                            <input type="radio" name="notification_type" value="status_reminder" checked onclick="toggleNotificationFields('status_reminder')" class="text-accent-blue focus:ring-accent-blue">
                            <div class="text-xs">
                                <div class="font-bold text-text-main">Status Reminders</div>
                                <div class="text-[10px] text-text-dark/50">Auto-send pending step email</div>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-secondary-bg rounded-xl border border-card-border cursor-pointer hover:border-accent-blue transition-all">
                            <input type="radio" name="notification_type" value="custom_email" onclick="toggleNotificationFields('custom_email')" class="text-accent-blue focus:ring-accent-blue">
                            <div class="text-xs">
                                <div class="font-bold text-text-main">Custom Email</div>
                                <div class="text-[10px] text-text-dark/50">Write custom email content</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="customEmailFields" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-1">Email Subject <span class="text-red-500">*</span></label>
                        <input type="text" name="custom_subject" placeholder="E.g., Important Update Regarding Your Vedanta Profile" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2 text-xs text-text-main focus:outline-none focus:border-accent-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-1">Email Message <span class="text-red-500">*</span></label>
                        <textarea name="custom_message" rows="4" placeholder="Type your custom notification message here..." class="w-full bg-secondary-bg border border-card-border rounded-xl p-3 text-xs text-text-main focus:outline-none focus:border-accent-blue"></textarea>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" onclick="closeBulkNotificationModal()" class="px-4 py-2 bg-secondary-bg text-text-dark/70 text-xs font-bold rounded-xl hover:bg-card-border transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-accent-blue text-white text-xs font-bold rounded-xl hover:bg-accent-blue-hover shadow-md flex items-center gap-2 transition-all">
                        <i class="fas fa-paper-plane"></i> Send Now
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openRatingModal(candidateId, comm, subj, demo, eng, disc, rem) {
        const form = document.getElementById('ratingForm');
        form.action = `/admin/crm/candidate/${candidateId}/rate`;

        document.getElementById('rating_communication').value = comm;
        document.getElementById('rating_subject_knowledge').value = subj;
        document.getElementById('rating_demo_performance').value = demo;
        document.getElementById('rating_english_fluency').value = eng;
        document.getElementById('rating_discipline').value = disc;
        document.getElementById('rating_remarks').value = rem;

        document.getElementById('ratingModal').classList.remove('hidden');
    }

    function toggleSelectAllCandidates(master) {
        const checkboxes = document.querySelectorAll('.candidate-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        updateBulkActionState();
    }

    function deselectAllCandidates() {
        const master = document.getElementById('selectAllCandidates');
        if (master) master.checked = false;
        const checkboxes = document.querySelectorAll('.candidate-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        updateBulkActionState();
    }

    function updateBulkActionState() {
        const checked = document.querySelectorAll('.candidate-checkbox:checked');
        const count = checked.length;
        const bar = document.getElementById('bulkActionBar');
        const countSpan = document.getElementById('selectedCount');
        const master = document.getElementById('selectAllCandidates');
        const allCheckboxes = document.querySelectorAll('.candidate-checkbox');

        if (countSpan) countSpan.textContent = count;

        if (master && allCheckboxes.length > 0) {
            master.checked = (count === allCheckboxes.length);
        }

        if (count > 0) {
            bar.classList.remove('hidden');
        } else {
            bar.classList.add('hidden');
        }
    }

    function openBulkNotificationModal() {
        const checked = document.querySelectorAll('.candidate-checkbox:checked');
        if (checked.length === 0) return;

        const inputsContainer = document.getElementById('bulkCandidateInputs');
        inputsContainer.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'candidate_ids[]';
            input.value = cb.value;
            inputsContainer.appendChild(input);
        });

        document.getElementById('modalSelectedCount').textContent = checked.length;
        document.getElementById('bulkNotificationModal').classList.remove('hidden');
    }

    function closeBulkNotificationModal() {
        document.getElementById('bulkNotificationModal').classList.add('hidden');
    }

    function toggleNotificationFields(type) {
        const customFields = document.getElementById('customEmailFields');
        if (type === 'custom_email') {
            customFields.classList.remove('hidden');
        } else {
            customFields.classList.add('hidden');
        }
    }
</script>
@endpush
