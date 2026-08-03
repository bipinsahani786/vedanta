@extends('layouts.admin')

@section('title', 'Contact Queries')
@section('subtitle', 'Manage and respond to inquiries from the public contact form.')

@section('content')

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-text-dark/50 font-medium whitespace-nowrap">
        Showing {{ $leads->firstItem() ?? 0 }} to {{ $leads->lastItem() ?? 0 }} of {{ $leads->total() }} entries
    </div>
    <form action="{{ route('admin.leads.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center justify-end gap-3">
        <div class="relative w-full sm:w-64">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-text-dark/40 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, subject..." 
                   class="w-full pl-9 pr-4 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        </div>
        
        <div class="relative w-full sm:w-auto flex items-center gap-2">
            <span class="text-xs font-bold text-text-dark/50 uppercase tracking-wider whitespace-nowrap">Next Follow-up:</span>
            <input type="date" name="follow_up_date" value="{{ request('follow_up_date') }}" title="Filter by Follow-up Date"
                   class="w-full sm:w-40 px-3 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        </div>
        
        <button type="submit" class="w-full sm:w-auto bg-accent-blue text-white rounded-xl px-4 py-2 text-sm font-bold shadow hover:bg-accent-blue-hover transition-colors whitespace-nowrap">Filter</button>
        
        @if(request()->anyFilled(['search', 'follow_up_date']))
            <a href="{{ route('admin.leads.index') }}" class="text-text-dark/40 hover:text-red-400 transition-colors w-full sm:w-auto text-center" title="Clear Filters">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </form>
</div>

{{-- Data Table --}}
<div class="bg-card-bg rounded-b-2xl border border-card-border overflow-x-auto shadow-xl">
    <table class="w-full text-left border-collapse admin-table">
        <thead>
            <tr>
                <th class="w-10 text-center">
                    <input type="checkbox" id="selectAllLeads" onclick="toggleSelectAllLeads(this)" class="rounded border-card-border text-accent-blue focus:ring-accent-blue cursor-pointer" title="Select All Queries">
                </th>
                @php
                    $route = 'admin.leads.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'name', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Sender Info
                        @if(request('sort_by') === 'name')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th class="w-1/3">Message Details</th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'status', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Status
                        @if(request('sort_by') === 'status')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'created_at', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Received
                        @if(request('sort_by') === 'created_at' || !request('sort_by'))
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th class="text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($leads as $lead)
            <tr class="group">
                <td class="text-center align-top">
                    <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="lead-checkbox rounded border-card-border text-accent-blue focus:ring-accent-blue cursor-pointer mt-1" onchange="updateLeadBulkActionState()">
                </td>
                <td class="align-top">
                    <div class="font-bold text-text-main group-hover:text-accent-blue transition-colors">{{ $lead->name }}</div>
                    <div class="text-xs text-text-dark/60 mt-1 flex flex-col gap-0.5">
                        <span class="flex items-center gap-1.5"><i class="fas fa-envelope w-3 text-[10px]"></i> {{ $lead->email }}</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-phone-alt w-3 text-[10px]"></i> {{ $lead->phone }}</span>
                    </div>
                </td>
                <td class="align-top">
                    <div class="text-sm font-semibold text-text-main mb-1">{{ $lead->subject }}</div>
                    <div class="text-xs text-text-dark/60 leading-relaxed bg-secondary-bg/50 p-2.5 rounded-lg border border-card-border">{{ $lead->message }}</div>
                </td>
                <td class="align-top">
                    @if($lead->status === 'new')
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-star text-[9px]"></i> New
                        </span>
                    @elseif($lead->status === 'contacted')
                        <span class="bg-accent-yellow/10 text-accent-yellow px-2.5 py-1 rounded-lg text-[10px] font-bold border border-accent-yellow/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-reply text-[9px]"></i> Contacted
                        </span>
                    @else
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-check-double text-[9px]"></i> Closed
                        </span>
                    @endif
                </td>
                <td class="align-top text-text-dark/60 text-sm">
                    {{ $lead->created_at->format('M d, Y h:i A') }}
                    <div class="text-[10px] text-text-dark/40 mt-1">{{ $lead->created_at->diffForHumans() }}</div>
                </td>
                <td class="align-top">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.leads.show', $lead->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-accent-blue/10 text-accent-blue hover:bg-accent-blue hover:text-white rounded-lg text-xs font-bold transition-colors whitespace-nowrap">
                            Manage Lead <i class="fas fa-arrow-right"></i>
                        </a>
                        <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this query from {{ addslashes($lead->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-500/10 rounded-lg transition-colors border border-transparent hover:border-red-500/20" title="Delete Query">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <div class="w-16 h-16 bg-secondary-bg rounded-2xl flex items-center justify-center text-text-dark/20 text-3xl mx-auto mb-4 border border-card-border">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <p class="text-text-main font-bold text-lg mb-1">No contact queries found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your search criteria.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Floating Bulk Action Bar for Leads -->
<form action="{{ route('admin.leads.bulk-delete') }}" method="POST" id="leadBulkDeleteForm">
    @csrf
    <div id="leadBulkCandidateInputs"></div>
    <div id="leadBulkActionBar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden bg-card-bg border border-red-500/50 rounded-2xl shadow-2xl p-4 flex items-center gap-4 backdrop-blur-xl">
        <div class="flex items-center gap-2 text-sm font-bold text-text-main">
            <span class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center text-xs" id="leadSelectedCount">0</span>
            <span>Queries Selected</span>
        </div>
        <div class="h-6 w-px bg-card-border"></div>
        <button type="button" onclick="submitLeadBulkDelete()" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2 shadow-md">
            <i class="fas fa-trash-alt"></i> Delete Selected Queries
        </button>
        <button type="button" onclick="deselectAllLeads()" class="text-xs text-text-dark/50 hover:text-text-main font-semibold px-2">
            Cancel
        </button>
    </div>
</form>

{{-- Pagination --}}
@if($leads->hasPages())
<div class="mt-6 flex justify-end">
    {{ $leads->links('pagination::tailwind') }}
</div>
@endif

@endsection

@push('scripts')
<script>
    function toggleSelectAllLeads(master) {
        const checkboxes = document.querySelectorAll('.lead-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        updateLeadBulkActionState();
    }

    function deselectAllLeads() {
        const master = document.getElementById('selectAllLeads');
        if (master) master.checked = false;
        const checkboxes = document.querySelectorAll('.lead-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        updateLeadBulkActionState();
    }

    function updateLeadBulkActionState() {
        const checked = document.querySelectorAll('.lead-checkbox:checked');
        const count = checked.length;
        const bar = document.getElementById('leadBulkActionBar');
        const countSpan = document.getElementById('leadSelectedCount');
        const master = document.getElementById('selectAllLeads');
        const allCheckboxes = document.querySelectorAll('.lead-checkbox');

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

    function submitLeadBulkDelete() {
        const checked = document.querySelectorAll('.lead-checkbox:checked');
        if (checked.length === 0) return;

        if (!confirm(`Are you sure you want to delete ${checked.length} selected contact query(ies)?`)) {
            return;
        }

        const container = document.getElementById('leadBulkCandidateInputs');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'lead_ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        document.getElementById('leadBulkDeleteForm').submit();
    }
</script>
@endpush
