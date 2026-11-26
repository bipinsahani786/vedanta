@extends('layouts.admin')

@section('title', 'School Sharing Files & Shortlists')
@section('subtitle', 'Create per-school candidate sharing files with custom categories (TGT Hindi, PGT Maths, PRT Hindi), password protection, WhatsApp/Email sharing, and view-only CV access.')

@section('actions')
    @if($featureReady)
        <button type="button" onclick="document.getElementById('createSharingFileModal').classList.remove('hidden')"
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm flex items-center gap-2 cursor-pointer">
            <i class="fas fa-folder-plus"></i> Create School Sharing File
        </button>
    @endif
@endsection

@section('content')
@if(!$featureReady)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-sm text-amber-800">
        School Sharing File tables are not migrated yet. Please run <code class="font-mono font-bold">php artisan migrate --force</code>.
    </div>
@else
    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-wrap items-center justify-between gap-4">
            <form action="{{ route('admin.sharing-files.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[260px]">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search file title, school name, or category (e.g. TGT Hindi)..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:border-blue-600 outline-none">
                </div>

                <select name="school_user_id" onchange="this.form.submit()" class="rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 bg-white outline-none max-w-xs">
                    <option value="">All Schools</option>
                    @foreach($schools as $sch)
                        @php $schLabel = $sch->employerProfile?->school_name ?: $sch->name; @endphp
                        <option value="{{ $sch->id }}" {{ request('school_user_id') == $sch->id ? 'selected' : '' }}>{{ $schLabel }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'school_user_id']))
                    <a href="{{ route('admin.sharing-files.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-gray-500 hover:bg-gray-100">Reset</a>
                @endif
            </form>

            <span class="text-xs text-gray-400 font-medium">
                Showing {{ $files->firstItem() ?? 0 }} to {{ $files->lastItem() ?? 0 }} of {{ $files->total() }} file(s)
            </span>
        </div>

        <!-- Files List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">School &amp; Sharing File</th>
                            <th class="px-6 py-3 text-left">Categories</th>
                            <th class="px-6 py-3 text-left">Security &amp; Status</th>
                            <th class="px-6 py-3 text-left">Quick Share</th>
                            <th class="px-6 py-3 text-right">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($files as $file)
                            @php
                                $schName = $file->school?->employerProfile?->school_name ?: ($file->school?->name ?? 'School');
                                $url = $file->shareUrl();
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4 align-top">
                                    <a href="{{ route('admin.sharing-files.show', $file->id) }}" class="font-bold text-gray-900 hover:text-blue-600 block">
                                        {{ $file->title }}
                                    </a>
                                    <div class="text-xs text-blue-700 font-semibold mt-0.5">{{ $schName }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">
                                        {{ $file->entries_count }} candidate(s) · {{ $file->views_count }} view(s)
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @forelse($file->categories as $cat)
                                            <a href="{{ route('admin.sharing-files.show', ['id' => $file->id, 'category_id' => $cat->id]) }}"
                                               class="px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 text-[11px] font-bold hover:bg-indigo-100">
                                                {{ $cat->name }} ({{ $cat->entries_count }})
                                            </a>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">No categories yet</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top text-xs space-y-1">
                                    <div>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $file->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $file->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($file->hasPassword())
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 ml-1">
                                                <i class="fas fa-lock text-[9px]"></i> Password Set
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 ml-1">Open Link</span>
                                        @endif
                                    </div>
                                    @if($file->last_shared_at)
                                        <div class="text-[10px] text-gray-500">Emailed {{ $file->last_shared_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <button type="button" onclick="copyShareUrl(@json($url), this)"
                                            class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold cursor-pointer inline-flex items-center gap-1">
                                        <i class="fas fa-copy"></i> <span>Copy Link</span>
                                    </button>
                                </td>

                                <td class="px-6 py-4 align-top text-right whitespace-nowrap">
                                    <a href="{{ route('admin.sharing-files.show', $file->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs">
                                        <i class="fas fa-folder-open"></i> Open File
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    No school sharing files created yet. Click <strong>Create School Sharing File</strong> above to create one for a school.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($files->hasPages())
                <div class="p-4 border-t border-gray-100">{{ $files->links() }}</div>
            @endif
        </div>
    </div>

    <!-- Create School Sharing File Modal -->
    <div id="createSharingFileModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-4">
            <button type="button" onclick="document.getElementById('createSharingFileModal').classList.add('hidden')"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 cursor-pointer">
                <i class="fas fa-times text-lg"></i>
            </button>

            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-folder-plus text-blue-600"></i> Create School Sharing File
            </h3>

            <form action="{{ route('admin.sharing-files.store') }}" method="POST" class="space-y-4 text-sm">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Select School <span class="text-rose-500">*</span></label>
                    <select name="school_user_id" required class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-800 outline-none focus:border-blue-600">
                        <option value="">-- Choose School Account --</option>
                        @foreach($schools as $sch)
                            @php $schLabel = $sch->employerProfile?->school_name ?: $sch->name; @endphp
                            <option value="{{ $sch->id }}" {{ request('school_user_id') == $sch->id ? 'selected' : '' }}>
                                {{ $schLabel }} ({{ $sch->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sharing File Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required maxlength="255" placeholder="e.g., St. Xavier School - Session 2026 Shortlist"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-800 outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Initial Categories (Comma Separated)</label>
                    <input type="text" id="initialCategoriesInput" name="initial_categories" value="TGT Hindi, PGT Maths, PRT Hindi"
                           placeholder="TGT Hindi, PGT Maths, PRT Hindi"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-800 outline-none focus:border-blue-600">
                    <p class="text-[11px] text-gray-400 mt-1">You can also add/manage categories anytime inside the file.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">File Password (Optional)</label>
                        <input type="text" name="file_password" minlength="4" maxlength="50" placeholder="Min 4 characters"
                               class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-800 outline-none focus:border-blue-600">
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="inline-flex items-start gap-2 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" name="separate_password_share" value="1" class="mt-0.5 rounded text-blue-600">
                            <span><strong>Safer Mode:</strong> Share password separately from link messages</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1.5">Approved Profile Fields &amp; View-Only CV</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_qualifications" value="1" checked> Qualifications</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_date_of_birth" value="1" checked> Date of Birth</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_current_salary" value="1" checked> Current Salary</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_expected_salary" value="1" checked> Expected Salary</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_experience" value="1" checked> Experience</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_availability" value="1" checked> Availability</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-emerald-50 border border-emerald-200 font-semibold text-emerald-800"><input type="checkbox" name="allow_cv_view" value="1" checked> CV (View-Only)</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_photo" value="1"> Photo</label>
                        <label class="flex items-center gap-1.5 p-2 rounded-lg bg-gray-50 border border-gray-200"><input type="checkbox" name="allow_contact" value="1"> Contact Details</label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('createSharingFileModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer shadow-xs">
                        Create Sharing File
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<script>
    function copyShareUrl(url, btn) {
        if (!navigator.clipboard) {
            window.prompt('Copy sharing file link:', url);
            return;
        }
        navigator.clipboard.writeText(url).then(() => {
            const span = btn.querySelector('span');
            const prev = span.textContent;
            span.textContent = 'Copied!';
            setTimeout(() => { span.textContent = prev; }, 1600);
        });
    }
</script>
@endsection
