@extends('layouts.admin')

@section('title', 'Manage Subjects')

@section('actions')
    <button x-data @click="$dispatch('open-modal', { isEdit: false, formData: { id: '', name: '', categories: [], is_active: 1 } })" class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Subject
    </button>
@endsection

@section('content')
<div x-data="{ showModal: false, isEdit: false, formData: { id: '', name: '', categories: [], is_active: 1 } }" @open-modal.window="isEdit = $event.detail.isEdit; formData = $event.detail.formData; showModal = true">

{{-- Analytics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xl">
            <i class="fas fa-book"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Total Subjects</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\Subject::count() }}</p>
        </div>
    </div>
    
    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center text-xl">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Active</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\Subject::where('is_active', true)->count() }}</p>
        </div>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center text-xl">
            <i class="fas fa-times-circle"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Inactive</p>
            <p class="text-2xl font-bold text-text-main">{{ \App\Models\Subject::where('is_active', false)->count() }}</p>
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
            <h3 class="font-bold text-text-main">Subject List</h3>
            <p class="text-xs text-text-dark/50 font-medium">Showing {{ $subjects->firstItem() ?? 0 }} to {{ $subjects->lastItem() ?? 0 }} of {{ $subjects->total() }} entries</p>
        </div>
    </div>
    
    <form action="{{ route('admin.subjects.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3 relative group">
        <select name="category_id" class="w-full sm:w-48 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/20 focus:border-accent-blue px-4 py-2.5 shadow-inner transition-all cursor-pointer hover:border-accent-blue/50" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>

        <div class="relative w-full sm:w-80">
            <i class="fas fa-search absolute left-4 top-3 text-text-dark/40 text-sm group-focus-within:text-accent-blue transition-colors"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search subjects..." 
                   class="w-full pl-11 pr-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/20 focus:border-accent-blue transition-all shadow-inner hover:border-accent-blue/50">
            @if(request('search') || request('category_id'))
                <a href="{{ route('admin.subjects.index') }}" class="absolute right-4 top-3 text-text-dark/40 hover:text-red-400 transition-colors">
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
                    $route = 'admin.subjects.index';
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
                <th class="text-text-dark/60">Category</th>
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
            @forelse($subjects as $subject)
            <tr>
                <td class="font-medium text-text-dark/50">#{{ $subject->id }}</td>
                <td class="font-semibold text-text-main">{{ $subject->name }}</td>
                <td>
                    @if($subject->categories->count() > 0)
                        <div class="flex flex-wrap gap-1.5 max-w-md">
                            @foreach($subject->categories as $cat)
                                <span class="bg-accent-blue/10 text-accent-blue px-2.5 py-1 rounded-lg text-xs font-semibold border border-accent-blue/20 flex items-center gap-1">
                                    <i class="fas fa-folder text-[10px] opacity-70"></i>
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-text-dark/40 text-xs italic">Uncategorized</span>
                    @endif
                </td>
                <td>
                    @if($subject->is_active)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider">Active</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider">Inactive</span>
                    @endif
                </td>
                <td class="text-text-dark/60">{{ $subject->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <button @click="$dispatch('open-modal', { isEdit: true, formData: { id: '{{ $subject->id }}', name: '{{ addslashes($subject->name) }}', categories: {{ $subject->categories->pluck('id')->values()->toJson() }}, is_active: {{ $subject->is_active ? 1 : 0 }} } })" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Edit Subject & Categories">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                    <p class="text-text-main font-semibold mb-1">No subjects found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your search criteria or add a new subject.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $subjects->links() }}
</div>

<!-- Modal -->
<div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Overlay -->
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="showModal = false"></div>

        <!-- Modal Content -->
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8 text-slate-800">
            <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800" x-text="isEdit ? 'Edit Subject' : 'Add Subject'"></h3>
                    <p class="text-xs text-slate-500 mt-0.5">Select one or multiple categories for this subject</p>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form :action="isEdit ? '{{ url('admin/subjects') }}/' + formData.id : '{{ route('admin.subjects.store') }}'" method="POST" @submit="if(formData.categories.length === 0) { $event.preventDefault(); alert('Please select at least one category.'); }">
                @csrf
                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Subject Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="formData.name" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-accent-blue focus:ring focus:ring-accent-blue/20 px-4 py-2.5 border transition-colors text-sm font-medium" placeholder="e.g. PTI, Mathematics, English">
                </div>

                {{-- Multi-Category Checkbox Selector --}}
                <div class="mb-5" x-data="{ catSearch: '' }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-slate-700">
                            Assign Categories <span class="text-red-500">*</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-accent-blue ml-1.5 border border-blue-100" x-text="`${formData.categories.length} selected`"></span>
                        </label>
                        <div class="flex items-center gap-2 text-xs">
                            <button type="button" @click="formData.categories = {{ $categories->pluck('id')->values()->toJson() }}" class="text-accent-blue hover:underline font-semibold">Select All</button>
                            <span class="text-slate-300">|</span>
                            <button type="button" @click="formData.categories = []" class="text-slate-500 hover:text-slate-700 font-medium">Clear</button>
                        </div>
                    </div>

                    @if($categories->count() > 5)
                    <div class="mb-2 relative">
                        <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                        <input type="text" x-model="catSearch" placeholder="Filter categories..." class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-blue">
                    </div>
                    @endif

                    <div class="max-h-52 overflow-y-auto border border-slate-200 rounded-xl p-2.5 bg-slate-50/60 space-y-1.5 custom-scrollbar">
                        @foreach($categories as $category)
                            <label x-show="!catSearch || '{{ strtolower(addslashes($category->name)) }}'.includes(catSearch.toLowerCase())" 
                                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition-all cursor-pointer border border-transparent hover:border-slate-200 select-none"
                                   :class="formData.categories.includes({{ $category->id }}) ? 'bg-blue-50/80 border-blue-200 shadow-sm' : ''">
                                <input type="checkbox" 
                                       name="categories[]" 
                                       value="{{ $category->id }}" 
                                       :checked="formData.categories.includes({{ $category->id }})"
                                       @change="if($event.target.checked) { if(!formData.categories.includes({{ $category->id }})) formData.categories.push({{ $category->id }}); } else { formData.categories = formData.categories.filter(c => c != {{ $category->id }}); }"
                                       class="rounded border-slate-300 text-accent-blue focus:ring-accent-blue w-4 h-4 cursor-pointer">
                                <span class="text-xs font-semibold flex-1" :class="formData.categories.includes({{ $category->id }}) ? 'text-accent-blue' : 'text-slate-700'">{{ $category->name }}</span>
                                <span x-show="formData.categories.includes({{ $category->id }})" class="text-accent-blue text-xs">
                                    <i class="fas fa-check"></i>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <div x-show="formData.categories.length === 0" class="mt-2 text-xs text-amber-600 font-medium flex items-center gap-1.5 bg-amber-50 p-2 rounded-lg border border-amber-200">
                        <i class="fas fa-exclamation-triangle"></i> Please choose at least one category for this subject.
                    </div>
                </div>

                <div class="mb-6 flex items-center p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <input type="checkbox" id="is_active" name="is_active" value="1" x-model="formData.is_active" class="rounded border-slate-300 text-accent-blue shadow-sm focus:border-accent-blue focus:ring focus:ring-accent-blue/20 w-4 h-4 cursor-pointer">
                    <label for="is_active" class="ml-2.5 block text-xs font-semibold text-slate-700 cursor-pointer">Active Status</label>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-accent-blue rounded-xl hover:bg-accent-blue-hover transition-colors shadow-lg" x-text="isEdit ? 'Update Subject' : 'Save Subject'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
