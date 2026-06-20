@extends('layouts.admin')

@section('title', 'CRM & Follow-ups')
@section('subtitle', 'Manage candidates, track hiring status, generate invoices, and log follow-ups.')

@section('content')

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-text-dark/50 font-medium">
        Showing {{ $candidates->firstItem() ?? 0 }} to {{ $candidates->lastItem() ?? 0 }} of {{ $candidates->total() }} entries
    </div>
    <form action="{{ route('admin.crm.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative">
        <i class="fas fa-search absolute left-3 text-text-dark/40 text-sm"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." 
               class="w-full sm:w-72 pl-9 pr-4 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        @if(request('search'))
            <a href="{{ route('admin.crm.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-400 transition-colors">
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
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($candidates as $candidate)
            <tr class="group">
                <td>
                    <div class="font-semibold text-text-main group-hover:text-accent-blue transition-colors">{{ $candidate->name }}</div>
                    <div class="text-xs text-text-dark/50 flex flex-col gap-0.5 mt-1">
                        <span><i class="fas fa-envelope text-[10px] w-3"></i> {{ $candidate->email }}</span>
                        <span><i class="fas fa-phone-alt text-[10px] w-3"></i> {{ $candidate->phone }}</span>
                    </div>
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
                        <span class="bg-card-border/50 text-text-dark/60 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-card-border uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-exclamation-circle"></i> Incomplete
                        </span>
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
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.crm.show', $candidate->id) }}" class="px-3 py-1.5 rounded-lg bg-accent-blue/10 text-accent-blue hover:bg-accent-blue hover:text-white text-xs font-semibold transition-colors flex items-center gap-1">
                            Manage CRM <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center">
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

@endsection
