@extends('layouts.admin')

@section('title', 'Job Applications')
@section('subtitle', 'Track and manage candidate applications for job posts.')

@section('content')

<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4">
    <form action="{{ route('admin.applications.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-3 text-text-dark/40 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate or job title..." 
                   class="w-full pl-9 pr-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Statuses</option>
                <option value="applied" {{ request('status') == 'applied' ? 'selected' : '' }}>New (Applied)</option>
                <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Forwarded</option>
                <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Selected (Hired)</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <button type="submit" class="bg-accent-blue text-white rounded-xl px-6 py-2.5 text-sm font-bold shadow hover:bg-accent-blue-hover transition-colors">
            Filter
        </button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.applications.index') }}" class="flex items-center justify-center px-4 py-2 text-text-dark/40 hover:text-red-400 transition-colors text-sm font-bold">
                Clear
            </a>
        @endif
    </form>
</div>

<div class="bg-card-bg rounded-b-2xl border border-card-border overflow-x-auto shadow-xl mb-6">
    <table class="w-full text-left border-collapse admin-table">
        <thead>
            <tr>
                <th>Candidate</th>
                <th>Job Post (School)</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Date Applied</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($applications as $app)
            <tr class="group">
                <td>
                    <div class="font-semibold text-text-main group-hover:text-accent-blue transition-colors">{{ $app->candidate->name }}</div>
                    <div class="text-xs text-text-dark/50">{{ $app->candidate->email }}</div>
                    <div class="text-[10px] text-text-dark/40 mt-1">
                        <a href="{{ route('admin.crm.show', $app->candidate->id) }}" class="text-accent-blue hover:underline">View Profile</a>
                    </div>
                </td>
                <td>
                    <div class="font-semibold text-text-main truncate max-w-[200px]" title="{{ $app->jobPost->title }}">{{ $app->jobPost->title }}</div>
                    <div class="text-xs text-text-dark/50">{{ $app->jobPost->user->name ?? 'Unknown School' }}</div>
                </td>
                <td>
                    @if($app->status === 'applied')
                        <span class="bg-text-dark/10 text-text-dark px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">New</span>
                    @elseif($app->status === 'shortlisted')
                        <span class="bg-accent-blue/10 text-accent-blue px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Forwarded</span>
                    @elseif($app->status === 'hired')
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Selected</span>
                    @elseif($app->status === 'rejected')
                        <span class="bg-red-500/10 text-red-500 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Rejected</span>
                    @endif
                </td>
                <td class="max-w-[200px]">
                    @if($app->remarks)
                        <p class="text-xs text-text-dark/70 truncate" title="{{ $app->remarks }}">{{ $app->remarks }}</p>
                    @else
                        <span class="text-text-dark/30 text-xs italic">No remarks</span>
                    @endif
                </td>
                <td class="text-text-dark/60 text-sm">
                    {{ $app->created_at->format('M d, Y') }}
                </td>
                <td class="text-right">
                    <button type="button" onclick="openStatusModal({{ $app->id }}, '{{ $app->status }}', '{{ addslashes($app->remarks ?? '') }}', '{{ $app->interview_date ? $app->interview_date->format('Y-m-d\TH:i') : '' }}', '{{ addslashes($app->interview_link ?? '') }}')" class="px-3 py-1.5 rounded-lg bg-secondary-bg text-text-main border border-card-border hover:border-accent-blue hover:text-accent-blue text-xs font-semibold transition-colors">
                        Update Status
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <p class="text-text-main font-bold text-lg mb-1">No applications found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your search criteria.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($applications->hasPages())
<div class="mt-4">
    {{ $applications->links('pagination::tailwind') }}
</div>
@endif

<!-- Update Status Modal -->
<div id="statusModal" class="fixed inset-0 z-50 hidden" style="background-color: rgba(0,0,0,0.5);">
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-card-bg rounded-2xl border border-card-border w-full max-w-md shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
                <h3 class="font-bold text-text-main">Update Application Status</h3>
                <button type="button" onclick="closeStatusModal()" class="text-text-dark/50 hover:text-red-400 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="statusForm" method="POST" action="">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text-main mb-2">Status</label>
                        <select name="status" id="modal_status" onchange="toggleScheduleFields()" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                            <option value="applied">New (Applied)</option>
                            <option value="shortlisted">Forwarded to School</option>
                            <option value="hired">Selected (Hired)</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <p class="text-xs text-text-dark/50 mt-2"><i class="fas fa-info-circle text-accent-blue"></i> Note: Marking as "Forwarded to School" will automatically email the candidate.</p>
                    </div>

                    <div id="scheduleFields" class="hidden space-y-4 p-4 border border-accent-blue/30 bg-accent-blue/5 rounded-xl">
                        <h4 class="font-bold text-sm text-accent-blue flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i> Interview Schedule (Optional)
                        </h4>
                        <div>
                            <label class="block text-xs font-semibold text-text-main mb-1">Date & Time</label>
                            <input type="datetime-local" name="interview_date" id="modal_interview_date" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-text-main mb-1">Meeting Link / Location</label>
                            <input type="text" name="interview_link" id="modal_interview_link" placeholder="e.g. Google Meet link or Address" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-text-main mb-2">School Remarks (Optional)</label>
                        <textarea name="remarks" id="modal_remarks" rows="3" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:border-accent-blue focus:outline-none placeholder-text-dark/30" placeholder="e.g. Needs to improve communication skills, or Selected for a salary of 20k"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-card-border bg-secondary-bg/30 flex justify-end gap-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 rounded-xl text-sm font-bold text-text-dark hover:bg-card-border/50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-sm font-bold bg-accent-blue text-white shadow hover:bg-accent-blue-hover transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openStatusModal(id, currentStatus, currentRemarks, interviewDate, interviewLink) {
        document.getElementById('statusModal').classList.remove('hidden');
        document.getElementById('modal_status').value = currentStatus;
        document.getElementById('modal_remarks').value = currentRemarks;
        document.getElementById('modal_interview_date').value = interviewDate;
        document.getElementById('modal_interview_link').value = interviewLink;
        document.getElementById('statusForm').action = `/admin/applications/${id}/status`;
        toggleScheduleFields();
    }

    function toggleScheduleFields() {
        const status = document.getElementById('modal_status').value;
        const scheduleFields = document.getElementById('scheduleFields');
        if (status === 'shortlisted') {
            scheduleFields.classList.remove('hidden');
        } else {
            scheduleFields.classList.add('hidden');
        }
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
</script>

@endsection
