@extends('layouts.admin')

@section('title', 'Manage Client Logos')
@section('subtitle', 'Manage the logos displayed in the "Our Trusted Clients" section.')

@section('actions')
    <button @click="$dispatch('open-modal', { isEdit: false, formData: { id: '', name: '', is_active: 1 } })" class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Client Logo
    </button>
@endsection

@section('content')
<div x-data="{ showModal: false, isEdit: false, formData: { id: '', name: '', is_active: 1 } }" @open-modal.window="isEdit = $event.detail.isEdit; formData = $event.detail.formData; showModal = true">

{{-- Analytics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xl">
            <i class="fas fa-building"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Total Logos</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\ClientLogo::count() }}</p>
        </div>
    </div>
    
    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center text-xl">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Active</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\ClientLogo::where('is_active', true)->count() }}</p>
        </div>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center text-xl">
            <i class="fas fa-times-circle"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Inactive</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\ClientLogo::where('is_active', false)->count() }}</p>
        </div>
    </div>
</div>

{{-- Enhanced Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-5 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-secondary-bg flex items-center justify-center text-text-dark/50 border border-card-border">
            <i class="fas fa-list"></i>
        </div>
        <div>
            <h3 class="font-bold text-text-main">Client Logo List</h3>
            <p class="text-xs text-text-dark/50 font-medium">Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} entries</p>
        </div>
    </div>
    
    <form action="{{ route('admin.clients.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative group">
        <i class="fas fa-search absolute left-4 text-text-dark/40 text-sm group-focus-within:text-accent-blue transition-colors"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client name..." 
               class="w-full sm:w-80 pl-11 pr-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/20 focus:border-accent-blue transition-all shadow-inner hover:border-accent-blue/50">
        @if(request('search'))
            <a href="{{ route('admin.clients.index') }}" class="absolute right-4 text-text-dark/40 hover:text-red-400 transition-colors">
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
                    $route = 'admin.clients.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th class="w-32">Logo Preview</th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'name', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Client Name
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
                        Uploaded On
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
            @forelse($clients as $client)
            <tr class="group">
                <td>
                    <div class="h-16 w-24 bg-white rounded-lg flex items-center justify-center p-2 shadow-sm border border-card-border">
                        <img src="{{ Storage::url($client->logo_path) }}" alt="{{ $client->name }}" class="max-h-full max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">
                    </div>
                </td>
                <td class="font-bold text-text-main text-base">{{ $client->name }}</td>
                <td>
                    @if($client->is_active)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider">Active</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider">Inactive</span>
                    @endif
                </td>
                <td class="text-text-dark/60 text-sm">{{ $client->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <button @click="$dispatch('open-modal', { isEdit: true, formData: { id: '{{ $client->id }}', name: '{{ addslashes($client->name) }}', is_active: {{ $client->is_active ? 1 : 0 }} } })" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this client logo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center hover:bg-red-50 hover:text-white transition-colors tooltip" title="Delete">
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
                        <i class="fas fa-building"></i>
                    </div>
                    <p class="text-text-main font-semibold mb-1">No client logos found</p>
                    <p class="text-text-dark/40 text-sm">Click "Add Client Logo" to upload one.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($clients->hasPages())
<div class="mt-6 flex justify-end">
    {{ $clients->links('pagination::tailwind') }}
</div>
@endif

</div>

{{-- Alpine Modal Form --}}
<template x-teleport="body">
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-card-bg rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-card-border">
                
                <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
                    <h3 class="font-bold text-text-main text-lg" x-text="isEdit ? 'Edit Client Logo' : 'Add Client Logo'"></h3>
                    <button @click="showModal = false" class="text-text-dark/40 hover:text-red-400 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form :action="isEdit ? '/admin/clients/' + formData.id : '{{ route('admin.clients.store') }}'" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <template x-if="isEdit">
                        @method('PUT')
                    </template>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Client / School Name</label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">
                                Logo Image <span x-show="isEdit" class="text-text-dark/40 lowercase normal-case">(Leave blank to keep current)</span>
                            </label>
                            <input type="file" name="logo" accept="image/*" :required="!isEdit" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue/10 file:text-accent-blue hover:file:bg-accent-blue/20 transition-all">
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input type="checkbox" name="is_active" id="is_active" x-model="formData.is_active" value="1" class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                            <label for="is_active" class="text-sm text-text-main font-medium">Active on website</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                        <button type="button" @click="showModal = false" class="px-5 py-2 text-text-dark/60 hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg shadow-accent-blue/20" x-text="isEdit ? 'Update Logo' : 'Upload Logo'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
@endsection
