@extends('layouts.admin')

@section('title', 'Manage Qualifications')

@section('actions')
    <a href="{{ route('admin.qualifications.create') }}" class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Qualification
    </a>
@endsection

@section('content')

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-text-dark/50 font-medium">
        Showing {{ $qualifications->firstItem() ?? 0 }} to {{ $qualifications->lastItem() ?? 0 }} of {{ $qualifications->total() }} entries
    </div>
    <form action="{{ route('admin.qualifications.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative">
        <i class="fas fa-search absolute left-3 text-text-dark/40 text-sm"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search qualifications..." 
               class="w-full sm:w-72 pl-9 pr-4 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        @if(request('search'))
            <a href="{{ route('admin.qualifications.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-400 transition-colors">
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
                    $route = 'admin.qualifications.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th class="w-16">
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'id', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        ID
                        @if(request('sort_by') === 'id')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'name', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Name
                        @if(request('sort_by') === 'name')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'is_active', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Status
                        @if(request('sort_by') === 'is_active')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'created_at', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Created At
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
            @forelse($qualifications as $qualification)
            <tr>
                <td class="font-medium text-text-dark/50">#{{ $qualification->id }}</td>
                <td class="font-semibold text-text-main">{{ $qualification->name }}</td>
                <td>
                    @if($qualification->is_active)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider">Active</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider">Inactive</span>
                    @endif
                </td>
                <td class="text-text-dark/60">{{ $qualification->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.qualifications.edit', $qualification) }}" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form action="{{ route('admin.qualifications.destroy', $qualification) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this qualification?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors tooltip" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-12 text-center">
                    <div class="w-16 h-16 bg-secondary-bg rounded-2xl flex items-center justify-center text-text-dark/20 text-2xl mx-auto mb-4 border border-card-border">
                        <i class="fas fa-search"></i>
                    </div>
                    <p class="text-text-main font-semibold mb-1">No qualifications found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your search criteria or add a new qualification.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($qualifications->hasPages())
<div class="mt-6 flex justify-end">
    {{ $qualifications->links('pagination::tailwind') }}
</div>
@endif

@endsection
