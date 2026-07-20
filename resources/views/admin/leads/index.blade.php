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
                    <div class="flex justify-end">
                        <a href="{{ route('admin.leads.show', $lead->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-accent-blue/10 text-accent-blue hover:bg-accent-blue hover:text-white rounded-lg text-xs font-bold transition-colors whitespace-nowrap">
                            Manage Lead <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center">
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

{{-- Pagination --}}
@if($leads->hasPages())
<div class="mt-6 flex justify-end">
    {{ $leads->links('pagination::tailwind') }}
</div>
@endif

@endsection
