@extends('layouts.admin')

@section('title', 'Manage Testimonials')
@section('subtitle', 'Manage client and candidate reviews displayed on the website.')

@section('actions')
    <button onclick="document.getElementById('addTestimonialModal').classList.remove('hidden')" class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Testimonial
    </button>
@endsection

@section('content')

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-text-dark/50 font-medium">
        Showing {{ $testimonials->firstItem() ?? 0 }} to {{ $testimonials->lastItem() ?? 0 }} of {{ $testimonials->total() }} entries
    </div>
    <form action="{{ route('admin.testimonials.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative">
        <i class="fas fa-search absolute left-3 text-text-dark/40 text-sm"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or message..." 
               class="w-full sm:w-72 pl-9 pr-4 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        @if(request('search'))
            <a href="{{ route('admin.testimonials.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-400 transition-colors">
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
                    $route = 'admin.testimonials.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th class="w-16">Image</th>
                <th class="w-1/4">
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'name', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Author Details
                        @if(request('sort_by') === 'name')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>Message & Rating</th>
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
            @forelse($testimonials as $testimonial)
            <tr class="group">
                <td>
                    @if($testimonial->image_path)
                        <img src="{{ Storage::url($testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent-blue to-accent-blue/70 text-white flex items-center justify-center font-bold shadow-sm text-sm">
                            {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                        </div>
                    @endif
                </td>
                <td>
                    <div class="font-bold text-text-main">{{ $testimonial->name }}</div>
                    <div class="text-xs text-text-dark/60 mt-0.5">{{ $testimonial->role }}</div>
                </td>
                <td>
                    <div class="flex text-accent-yellow text-[10px] mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-accent-yellow' : 'text-card-border' }}"></i>
                        @endfor
                    </div>
                    <p class="text-xs text-text-dark/60 line-clamp-2 max-w-sm italic">"{{ $testimonial->message }}"</p>
                </td>
                <td>
                    @if($testimonial->is_active)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider">Active</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="editTestimonial({{ $testimonial->id }}, '{{ addslashes($testimonial->name) }}', '{{ addslashes($testimonial->role) }}', '{{ addslashes($testimonial->message) }}', {{ $testimonial->rating }}, {{ $testimonial->is_active ? 'true' : 'false' }})" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
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
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-text-main font-semibold mb-1">No testimonials found</p>
                    <p class="text-text-dark/40 text-sm">Click "Add Testimonial" to create one.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($testimonials->hasPages())
<div class="mt-6 flex justify-end">
    {{ $testimonials->links('pagination::tailwind') }}
</div>
@endif

<!-- Add Testimonial Modal -->
<div id="addTestimonialModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center overflow-y-auto">
    <div class="bg-card-bg rounded-2xl shadow-2xl w-full max-w-lg my-8 border border-card-border animate-[slideIn_0.3s_ease-out]">
        <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
            <h3 class="font-bold text-text-main text-lg">Add New Testimonial</h3>
            <button onclick="document.getElementById('addTestimonialModal').classList.add('hidden')" class="text-text-dark/40 hover:text-red-400 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Name</label>
                        <input type="text" name="name" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Role/School</label>
                        <input type="text" name="role" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Rating (1-5)</label>
                    <input type="number" name="rating" min="1" max="5" value="5" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Profile Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue/10 file:text-accent-blue hover:file:bg-accent-blue/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Message</label>
                    <textarea name="message" rows="4" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" checked class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                    <label for="is_active" class="text-sm text-text-main">Active on website</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                <button type="button" onclick="document.getElementById('addTestimonialModal').classList.add('hidden')" class="px-5 py-2 text-text-dark/60 hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg shadow-accent-blue/20">Save Testimonial</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Testimonial Modal -->
<div id="editTestimonialModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center overflow-y-auto">
    <div class="bg-card-bg rounded-2xl shadow-2xl w-full max-w-lg my-8 border border-card-border animate-[slideIn_0.3s_ease-out]">
        <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
            <h3 class="font-bold text-text-main text-lg">Edit Testimonial</h3>
            <button onclick="document.getElementById('editTestimonialModal').classList.add('hidden')" class="text-text-dark/40 hover:text-red-400 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Role/School</label>
                        <input type="text" name="role" id="edit_role" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Rating (1-5)</label>
                    <input type="number" name="rating" id="edit_rating" min="1" max="5" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Profile Image (Leave blank to keep current)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue/10 file:text-accent-blue hover:file:bg-accent-blue/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-text-dark/60 mb-1 uppercase tracking-wider">Message</label>
                    <textarea name="message" id="edit_message" rows="4" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                    <label for="edit_is_active" class="text-sm text-text-main">Active on website</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                <button type="button" onclick="document.getElementById('editTestimonialModal').classList.add('hidden')" class="px-5 py-2 text-text-dark/60 hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg shadow-accent-blue/20">Update Testimonial</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editTestimonial(id, name, role, message, rating, isActive) {
        document.getElementById('editForm').action = `/admin/testimonials/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_message').value = message;
        document.getElementById('edit_rating').value = rating;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('editTestimonialModal').classList.remove('hidden');
    }
</script>
@endpush

@endsection
