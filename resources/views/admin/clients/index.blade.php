@extends('layouts.admin')

@section('title', 'Manage Client Logos')
@section('subtitle', 'Manage the logos displayed in the "Our Trusted Clients" section.')

@section('actions')
    <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Client Logo
    </button>
@endsection

@section('content')

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-text-dark/50 font-medium">
        Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} entries
    </div>
    <form action="{{ route('admin.clients.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative">
        <i class="fas fa-search absolute left-3 text-text-dark/40 text-sm"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client name..." 
               class="w-full sm:w-72 pl-9 pr-4 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        @if(request('search'))
            <a href="{{ route('admin.clients.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-400 transition-colors">
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
                        <button onclick="editClient({{ $client->id }}, '{{ addslashes($client->name) }}', {{ $client->is_active ? 'true' : 'false' }})" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Edit">
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

<!-- Add Client Modal -->
<div id="addClientModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-card-bg rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-card-border animate-[slideIn_0.3s_ease-out]">
        <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
            <h3 class="font-bold text-text-main text-lg">Add Client Logo</h3>
            <button onclick="document.getElementById('addClientModal').classList.add('hidden')" class="text-text-dark/40 hover:text-red-400 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Client / School Name</label>
                    <input type="text" name="name" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Logo Image</label>
                    <input type="file" name="logo" accept="image/*" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue/10 file:text-accent-blue hover:file:bg-accent-blue/20 transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" checked class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                    <label for="is_active" class="text-sm text-text-main">Active on website</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                <button type="button" onclick="document.getElementById('addClientModal').classList.add('hidden')" class="px-5 py-2 text-text-dark/60 hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg shadow-accent-blue/20">Upload Logo</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Client Modal -->
<div id="editClientModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-card-bg rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-card-border">
        <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
            <h3 class="font-bold text-text-main text-lg">Edit Client</h3>
            <button onclick="document.getElementById('editClientModal').classList.add('hidden')" class="text-text-dark/40 hover:text-red-400 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Client / School Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Logo Image (Leave blank to keep current)</label>
                    <input type="file" name="logo" accept="image/*" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue/10 file:text-accent-blue hover:file:bg-accent-blue/20 transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                    <label for="edit_is_active" class="text-sm text-text-main">Active on website</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                <button type="button" onclick="document.getElementById('editClientModal').classList.add('hidden')" class="px-5 py-2 text-text-dark/60 hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg shadow-accent-blue/20">Update Logo</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editClient(id, name, isActive) {
        document.getElementById('editForm').action = `/admin/clients/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('editClientModal').classList.remove('hidden');
    }
</script>
@endpush

@endsection
