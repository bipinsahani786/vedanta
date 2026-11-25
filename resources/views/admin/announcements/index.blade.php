@extends('layouts.admin')

@section('title', 'Notice & Announcement Banner')
@section('subtitle', 'Create, edit, and show or hide public announcement banners displayed on the website.')

@section('actions')
    <button type="button"
        x-data
        @click="$dispatch('open-notice-modal', { isEdit: false, notice: { id: '', title: '', message: '', link_text: '', link_url: '', style: 'info', is_active: true } })"
        class="px-4 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-all shadow-lg flex items-center gap-2 cursor-pointer">
        <i class="fas fa-plus"></i> Add Notice
    </button>
@endsection

@section('content')
<div x-data="{
        showModal: {{ $errors->any() ? 'true' : 'false' }},
        isEdit: {{ old('_method') === 'PUT' ? 'true' : 'false' }},
        formData: {
            id: @js(old('edit_id', '')),
            title: @js(old('title', '')),
            message: @js(old('message', '')),
            link_text: @js(old('link_text', '')),
            link_url: @js(old('link_url', '')),
            style: @js(old('style', 'info')),
            is_active: {{ old('_token') ? (old('is_active') ? 'true' : 'false') : 'true' }}
        }
    }"
    @open-notice-modal.window="isEdit = $event.detail.isEdit; formData = Object.assign({}, $event.detail.notice); showModal = true">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Analytics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.announcements.index') }}" class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xl">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Total Notices</p>
                <p class="text-2xl font-bold text-text-main">{{ $stats['total'] }}</p>
            </div>
        </a>

        <a href="{{ route('admin.announcements.index', ['status' => 'active']) }}" class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Active (Visible)</p>
                <p class="text-2xl font-bold text-text-main">{{ $stats['active'] }}</p>
            </div>
        </a>

        <a href="{{ route('admin.announcements.index', ['status' => 'inactive']) }}" class="bg-card-bg rounded-2xl border border-card-border p-5 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center text-xl">
                <i class="fas fa-eye-slash"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-text-dark/70 uppercase tracking-wider mb-1">Hidden</p>
                <p class="text-2xl font-bold text-text-main">{{ $stats['inactive'] }}</p>
            </div>
        </a>
    </div>

    {{-- Filter/Search Bar --}}
    <div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-5 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-secondary-bg flex items-center justify-center text-text-dark/60 border border-card-border">
                <i class="fas fa-list"></i>
            </div>
            <div>
                <h3 class="font-bold text-text-main">Notice List</h3>
                <p class="text-xs text-text-dark/60 font-medium">The most recently updated active notice appears as the site-wide announcement banner.</p>
            </div>
        </div>

        <form action="{{ route('admin.announcements.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative group">
            <i class="fas fa-search absolute left-4 text-text-dark/40 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notices..."
                class="w-full sm:w-72 pl-11 pr-9 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/20 focus:border-accent-blue transition-all">
            @if(request('search'))
                <a href="{{ route('admin.announcements.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-500 transition-colors">
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
                    <th>Notice Details</th>
                    <th>Style</th>
                    <th>Visibility</th>
                    <th>Last Updated</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-card-border">
                @forelse($announcements as $notice)
                    @php
                        $styleClasses = [
                            'info' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
                            'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'urgent' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $noticePayload = [
                            'id' => $notice->id,
                            'title' => $notice->title ?? '',
                            'message' => $notice->message,
                            'link_text' => $notice->link_text ?? '',
                            'link_url' => $notice->link_url ?? '',
                            'style' => $notice->style ?? 'info',
                            'is_active' => (bool) $notice->is_active,
                        ];
                    @endphp
                    <tr>
                        <td class="max-w-md">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                @if($notice->title)
                                    <span class="font-bold text-text-main">{{ $notice->title }}</span>
                                @endif
                                @if($liveNoticeId === $notice->id)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Live on Site
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $notice->message }}</p>
                            @if($notice->link_url)
                                <div class="mt-1.5 text-[11px] text-accent-blue font-semibold">
                                    <i class="fas fa-link mr-1"></i>
                                    {{ $notice->link_text ?: 'View Details' }} &rarr; {{ $notice->link_url }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold border uppercase tracking-wider {{ $styleClasses[$notice->style] ?? $styleClasses['info'] }}">
                                {{ ucfirst($notice->style) }}
                            </span>
                        </td>
                        <td>
                            @if($notice->is_active)
                                <span class="bg-green-500/10 text-green-700 px-2.5 py-1 rounded-lg text-[11px] font-bold border border-green-500/20 uppercase tracking-wider">Visible</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-[11px] font-bold border border-slate-200 uppercase tracking-wider">Hidden</span>
                            @endif
                        </td>
                        <td class="text-xs text-slate-500 whitespace-nowrap">
                            {{ $notice->updated_at->format('d M, Y h:i A') }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.announcements.toggle', $notice) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $notice->is_active ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }}"
                                        title="{{ $notice->is_active ? 'Hide Notice' : 'Show Notice' }}">
                                        <i class="fas {{ $notice->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>
                                        {{ $notice->is_active ? 'Hide' : 'Show' }}
                                    </button>
                                </form>

                                <button type="button"
                                    @click="$dispatch('open-notice-modal', { isEdit: true, notice: @js($noticePayload) })"
                                    class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors cursor-pointer"
                                    title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>

                                <form action="{{ route('admin.announcements.destroy', $notice) }}" method="POST" class="inline" onsubmit="return confirm('Delete this notice?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors cursor-pointer"
                                        title="Delete">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="w-16 h-16 bg-secondary-bg rounded-2xl flex items-center justify-center text-text-dark/30 text-2xl mx-auto mb-4 border border-card-border">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <p class="text-text-main font-semibold mb-1">No notices created yet</p>
                            <p class="text-text-dark/60 text-sm">Click "Add Notice" to publish a public banner announcement.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($announcements->hasPages())
        <div class="mt-6 flex justify-end">
            {{ $announcements->links('pagination::tailwind') }}
        </div>
    @endif

    {{-- Create / Edit Alpine Modal --}}
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition class="relative inline-block align-bottom bg-card-bg rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-card-border">
                <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/40">
                    <h3 class="font-bold text-text-main text-lg" x-text="isEdit ? 'Edit Notice / Announcement' : 'Create Notice / Announcement'"></h3>
                    <button type="button" @click="showModal = false" class="text-text-dark/50 hover:text-red-500 transition-colors cursor-pointer">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('admin/announcements') }}/' + formData.id : '{{ route('admin.announcements.store') }}'" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">
                    <input type="hidden" name="edit_id" x-model="formData.id">

                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-text-dark mb-1 uppercase tracking-wider">Badge / Title (Optional)</label>
                                <input type="text" name="title" x-model="formData.title" maxlength="120" placeholder="e.g. Important Notice"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/40">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-text-dark mb-1 uppercase tracking-wider">Banner Style</label>
                                <select name="style" x-model="formData.style"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/40">
                                    <option value="info">Info (Blue)</option>
                                    <option value="warning">Highlight (Amber)</option>
                                    <option value="success">Announcement (Emerald)</option>
                                    <option value="urgent">Urgent (Red)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-text-dark mb-1 uppercase tracking-wider">Notice Message *</label>
                            <textarea name="message" x-model="formData.message" rows="3" maxlength="500" required placeholder="Enter announcement text shown to visitors..."
                                class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/40"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-text-dark mb-1 uppercase tracking-wider">Button Text (Optional)</label>
                                <input type="text" name="link_text" x-model="formData.link_text" maxlength="60" placeholder="e.g. View Jobs"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/40">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-dark mb-1 uppercase tracking-wider">Link URL (Optional)</label>
                                <input type="text" name="link_url" x-model="formData.link_url" maxlength="500" placeholder="e.g. /jobs or https://..."
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/40">
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_active" id="notice_is_active" x-model="formData.is_active" value="1"
                                class="rounded border-card-border text-accent-blue focus:ring-accent-blue bg-secondary-bg">
                            <label for="notice_is_active" class="text-sm text-text-main font-medium cursor-pointer">Show banner on website</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-card-border">
                        <button type="button" @click="showModal = false" class="px-5 py-2 text-text-dark hover:bg-secondary-bg rounded-xl text-sm font-semibold transition-colors cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg cursor-pointer" x-text="isEdit ? 'Update Notice' : 'Save Notice'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
