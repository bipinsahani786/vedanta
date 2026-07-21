@extends('layouts.admin')

@section('title', 'Manage Cities')

@section('actions')
    <button x-data @click="$dispatch('open-modal', { isEdit: false, formData: { id: '', name: '', state_id: '', is_active: 1 } })" class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus"></i> Add City
    </button>
@endsection

@section('content')
<div x-data="{ showModal: false, isEdit: false, formData: { id: '', name: '', state_id: '', is_active: 1 } }" @open-modal.window="isEdit = $event.detail.isEdit; formData = $event.detail.formData; showModal = true">

{{-- Analytics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xl">
            <i class="fas fa-city"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Total Cities</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\City::count() }}</p>
        </div>
    </div>
    
    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center text-xl">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Active</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\City::where('is_active', true)->count() }}</p>
        </div>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center text-xl">
            <i class="fas fa-times-circle"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Inactive</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\City::where('is_active', false)->count() }}</p>
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
            <h3 class="font-bold text-text-main">City List</h3>
            <p class="text-xs text-text-dark/50 font-medium">Showing {{ $cities->firstItem() ?? 0 }} to {{ $cities->lastItem() ?? 0 }} of {{ $cities->total() }} entries</p>
        </div>
    </div>
    
    <form action="{{ route('admin.cities.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3 relative group">
        <select name="state_id" class="w-full sm:w-48 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/20 focus:border-accent-blue px-4 py-2.5 shadow-inner transition-all cursor-pointer hover:border-accent-blue/50" onchange="this.form.submit()">
            <option value="">All States</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
            @endforeach
        </select>

        <div class="relative w-full sm:w-80">
            <i class="fas fa-search absolute left-4 top-3 text-text-dark/40 text-sm group-focus-within:text-accent-blue transition-colors"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cities..." 
                   class="w-full pl-11 pr-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/20 focus:border-accent-blue transition-all shadow-inner hover:border-accent-blue/50">
            @if(request('search') || request('state_id'))
                <a href="{{ route('admin.cities.index') }}" class="absolute right-4 top-3 text-text-dark/40 hover:text-red-400 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Data Table --}}
<div class="bg-card-bg rounded-b-2xl border border-card-border overflow-x-auto shadow-xl">
    <table class="w-full text-left border-collapse admin-table">
        <thead>
            <tr>
                @php
                    $route = 'admin.cities.index';
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
                        City
                        @if(request('sort_by') === 'name')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'state_id', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        State
                        @if(request('sort_by') === 'state_id')
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
            @forelse($cities as $city)
            <tr>
                <td class="font-medium text-text-dark/50">#{{ $city->id }}</td>
                <td class="font-semibold text-text-main">{{ $city->name }}</td>
                <td class="text-text-main">{{ $city->state->name ?? '-' }}</td>
                <td>
                    @if($city->is_active)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider">Active</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider">Inactive</span>
                    @endif
                </td>
                <td class="text-text-dark/60">{{ $city->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <button @click="$dispatch('open-modal', { isEdit: true, formData: { id: '{{ $city->id }}', name: '{{ addslashes($city->name) }}', state_id: '{{ $city->state_id }}', is_active: {{ $city->is_active ? 1 : 0 }} } })" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this city?');">
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
                <td colspan="6" class="py-12 text-center">
                    <div class="w-16 h-16 bg-secondary-bg rounded-2xl flex items-center justify-center text-text-dark/20 text-2xl mx-auto mb-4 border border-card-border">
                        <i class="fas fa-search"></i>
                    </div>
                    <p class="text-text-main font-semibold mb-1">No cities found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your search criteria or add a new city.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $cities->links() }}
</div>

<!-- Modal -->
<div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Overlay -->
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="showModal = false"></div>

        <!-- Modal Content -->
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8 text-slate-800">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold" x-text="isEdit ? 'Edit City' : 'Add City'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form :action="isEdit ? '{{ url('admin/cities') }}/' + formData.id : '{{ route('admin.cities.store') }}'" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">

                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">State <span class="text-red-500">*</span></label>
                    <select name="state_id" x-model="formData.state_id" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-accent-blue focus:ring focus:ring-accent-blue/20 px-4 py-2 border transition-colors">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">City Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="formData.name" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-accent-blue focus:ring focus:ring-accent-blue/20 px-4 py-2 border transition-colors">
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" x-model="formData.is_active" class="rounded border-slate-300 text-accent-blue shadow-sm focus:border-accent-blue focus:ring focus:ring-accent-blue/20 w-4 h-4 cursor-pointer">
                    <label for="is_active" class="ml-2 block text-sm text-slate-700 cursor-pointer">Active</label>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-accent-blue rounded-lg hover:bg-accent-blue-hover transition-colors shadow-lg" x-text="isEdit ? 'Update City' : 'Save City'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
