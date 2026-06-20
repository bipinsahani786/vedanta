@extends('layouts.admin')

@section('title', 'Manage Services')
@section('subtitle', 'Configure the services offered on the frontend homepage.')

@section('actions')
    <button onclick="document.getElementById('addServiceModal').classList.remove('hidden')" class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Service
    </button>
@endsection

@section('content')

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-text-dark/50 font-medium">
        Showing {{ $services->firstItem() ?? 0 }} to {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} entries
    </div>
    <form action="{{ route('admin.services.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative">
        <i class="fas fa-search absolute left-3 text-text-dark/40 text-sm"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search services..." 
               class="w-full sm:w-72 pl-9 pr-4 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        @if(request('search'))
            <a href="{{ route('admin.services.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-400 transition-colors">
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
                    $route = 'admin.services.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th class="w-20">Icon</th>
                <th class="w-1/4">
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'title', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Title
                        @if(request('sort_by') === 'title')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>Description</th>
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
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($services as $service)
            <tr class="group">
                <td>
                    <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                        <i class="{{ $service->icon }}"></i>
                    </div>
                </td>
                <td class="font-bold text-text-main">{{ $service->title }}</td>
                <td>
                    <p class="text-xs text-text-dark/60 line-clamp-2 max-w-sm">{{ $service->description }}</p>
                </td>
                <td>
                    @if($service->is_active)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider">Active</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="editService({{ $service->id }}, '{{ addslashes($service->title) }}', '{{ addslashes($service->description) }}', '{{ $service->icon }}', {{ $service->is_active ? 'true' : 'false' }})" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this service?');">
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
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <p class="text-text-main font-semibold mb-1">No services found</p>
                    <p class="text-text-dark/40 text-sm">Click "Add Service" to create one.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($services->hasPages())
<div class="mt-6 flex justify-end">
    {{ $services->links('pagination::tailwind') }}
</div>
@endif

<!-- Add Service Modal -->
<div id="addServiceModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-card-bg rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-card-border animate-[slideIn_0.3s_ease-out]">
        <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
            <h3 class="font-bold text-text-main text-lg">Add New Service</h3>
            <button onclick="document.getElementById('addServiceModal').classList.add('hidden')" class="text-text-dark/40 hover:text-red-400 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.services.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Service Title</label>
                    <input type="text" name="title" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">FontAwesome Icon Class (e.g., fas fa-laptop)</label>
                    <input type="text" name="icon" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Description</label>
                    <textarea name="description" rows="3" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" checked class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                    <label for="is_active" class="text-sm text-text-main">Active</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                <button type="button" onclick="document.getElementById('addServiceModal').classList.add('hidden')" class="px-5 py-2 text-text-dark/60 hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg shadow-accent-blue/20">Save Service</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Service Modal -->
<div id="editServiceModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-card-bg rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-card-border">
        <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
            <h3 class="font-bold text-text-main text-lg">Edit Service</h3>
            <button onclick="document.getElementById('editServiceModal').classList.add('hidden')" class="text-text-dark/40 hover:text-red-400 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Service Title</label>
                    <input type="text" name="title" id="edit_title" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">FontAwesome Icon Class</label>
                    <input type="text" name="icon" id="edit_icon" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Description</label>
                    <textarea name="description" id="edit_description" rows="3" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                    <label for="edit_is_active" class="text-sm text-text-main">Active</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                <button type="button" onclick="document.getElementById('editServiceModal').classList.add('hidden')" class="px-5 py-2 text-text-dark/60 hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg shadow-accent-blue/20">Update Service</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editService(id, title, description, icon, isActive) {
        document.getElementById('editForm').action = `/admin/services/${id}`;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('editServiceModal').classList.remove('hidden');
    }
</script>
@endpush

@endsection
