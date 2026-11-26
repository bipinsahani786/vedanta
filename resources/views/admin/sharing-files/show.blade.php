@extends('layouts.admin')

@section('title', 'Sharing File: ' . $sharingFile->title)
@section('subtitle', 'School: ' . $schoolName)

@section('actions')
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('admin.sharing-files.index') }}" class="text-sm text-gray-600 hover:underline">&larr; All Sharing Files</a>
        <a href="{{ route('admin.crm.index') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs inline-flex items-center gap-1.5">
            <i class="fas fa-user-plus"></i> Add Candidates from CRM
        </a>
        <a href="{{ $shareUrl }}" target="_blank" class="px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold rounded-xl inline-flex items-center gap-1.5">
            <i class="fas fa-external-link-alt"></i> Preview Shared Link
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Sharing Controls, Password, Permissions & Delete -->
    <div class="space-y-6">
        <!-- Share via Link, WhatsApp & Email -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <h3 class="text-sm font-bold text-gray-900"><i class="fas fa-share-alt text-blue-600 mr-1.5"></i> Share With School</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sharingFile->hasPassword() ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ $sharingFile->hasPassword() ? 'Password Protected' : 'Open Link' }}
                </span>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Shareable Link</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="{{ $shareUrl }}" id="shareLinkInput"
                           class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-mono text-gray-700">
                    <button type="button" onclick="copyTextValue(document.getElementById('shareLinkInput').value, this)"
                            class="px-3 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold cursor-pointer shrink-0">
                        <i class="fas fa-copy mr-1"></i> <span>Copy Link</span>
                    </button>
                </div>
            </div>

            <!-- WhatsApp Sharing Options -->
            <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 space-y-2.5 text-xs">
                <div class="flex items-center justify-between gap-2">
                    <span class="font-bold text-emerald-900"><i class="fab fa-whatsapp text-emerald-600 mr-1"></i> Share on WhatsApp</span>
                    @if($sharingFile->hasPassword() && $sharingFile->separate_password_share)
                        <span class="px-2 py-0.5 rounded bg-white border border-emerald-300 text-[10px] font-bold text-emerald-800">Safer Separate Mode ON</span>
                    @endif
                </div>

                @if(!$sharingFile->separate_password_share || !$sharingFile->hasPassword())
                    <a href="{{ $waUrlCombined }}" target="_blank"
                       class="w-full py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center gap-2 shadow-xs">
                        <i class="fab fa-whatsapp text-sm"></i>
                        <span>{{ $sharingFile->hasPassword() ? 'Share on WhatsApp (Link + Password)' : 'Share on WhatsApp (File Link)' }}</span>
                    </a>
                    @if($sharingFile->hasPassword() && $waUrlPasswordOnly)
                        <p class="text-[11px] text-emerald-800/80 leading-snug">
                            Optional safer alternative:
                            <a href="{{ $waUrlLinkOnly }}" target="_blank" class="font-bold underline">Send link without password</a> ·
                            <a href="{{ $waUrlPasswordOnly }}" target="_blank" class="font-bold underline">Send password separately</a>
                        </p>
                    @endif
                @else
                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ $waUrlLinkOnly }}" target="_blank"
                           class="w-full py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center gap-2 shadow-xs">
                            <i class="fab fa-whatsapp text-sm"></i>
                            <span>1. Send File Link Only on WhatsApp</span>
                        </a>
                        @if($waUrlPasswordOnly)
                            <a href="{{ $waUrlPasswordOnly }}" target="_blank"
                               class="w-full py-2 px-3 rounded-xl bg-white hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold flex items-center justify-center gap-2">
                                <i class="fas fa-key"></i>
                                <span>2. Send Password Separately on WhatsApp</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Email Sharing Form -->
            <form action="{{ route('admin.sharing-files.share-email', $sharingFile->id) }}" method="POST" class="pt-3 border-t border-gray-100 space-y-2.5 text-xs">
                @csrf
                <h4 class="font-bold text-gray-800 flex items-center gap-1.5">
                    <i class="fas fa-envelope text-blue-600"></i> Share via Email
                </h4>
                <input type="email" name="recipient_email" value="{{ old('recipient_email', $sharingFile->school?->email) }}" required
                       placeholder="school@example.com"
                       class="w-full rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-800 outline-none focus:border-blue-600">
                <textarea name="custom_note" rows="2" maxlength="1000" placeholder="Optional message for the school..."
                          class="w-full rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-800 outline-none focus:border-blue-600"></textarea>

                @if($sharingFile->hasPassword())
                    <label class="flex items-start gap-2 text-[11px] text-gray-700 cursor-pointer">
                        <input type="checkbox" name="include_password" value="1"
                               {{ !$sharingFile->separate_password_share ? 'checked' : '' }}
                               class="mt-0.5 rounded text-blue-600">
                        <span>Include file password in this email <span class="text-gray-400">(uncheck to send password separately)</span></span>
                    </label>
                @endif

                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold cursor-pointer shadow-xs">
                    <i class="fas fa-paper-plane mr-1"></i> Send File Link Email
                </button>
            </form>
        </div>

        <!-- Password Set / Change / Remove -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3 text-xs">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <h3 class="text-sm font-bold text-gray-900"><i class="fas fa-key text-amber-600 mr-1.5"></i> Password Protection</h3>
                @if($sharingFile->hasPassword() && $plainPassword)
                    <button type="button" onclick="copyTextValue(@json($plainPassword), this)"
                            class="px-2 py-0.5 rounded bg-amber-50 border border-amber-200 text-amber-800 font-bold text-[10px] cursor-pointer">
                        <i class="fas fa-copy mr-0.5"></i> <span>Copy Current Password</span>
                    </button>
                @endif
            </div>

            <form action="{{ route('admin.sharing-files.password', $sharingFile->id) }}" method="POST" class="space-y-2.5">
                @csrf
                <input type="hidden" name="password_action" value="set">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">
                        {{ $sharingFile->hasPassword() ? 'Change File Password' : 'Set File Password' }}
                    </label>
                    <input type="text" name="file_password" minlength="4" maxlength="50" required
                           placeholder="Enter new password (min 4 chars)"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-800 outline-none focus:border-blue-600">
                    @error('file_password')
                        <p class="text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-start gap-2 text-[11px] text-gray-700 cursor-pointer">
                    <input type="checkbox" name="separate_password_share" value="1"
                           {{ $sharingFile->separate_password_share ? 'checked' : '' }}
                           class="mt-0.5 rounded text-blue-600">
                    <span><strong>Safer Option:</strong> Send password separately from WhatsApp/email link</span>
                </label>

                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold cursor-pointer">
                    {{ $sharingFile->hasPassword() ? 'Update Password' : 'Set Password' }}
                </button>
            </form>

            @if($sharingFile->hasPassword())
                <form action="{{ route('admin.sharing-files.password', $sharingFile->id) }}" method="POST"
                      onsubmit="return confirm('Remove password protection from this file?')">
                    @csrf
                    <input type="hidden" name="password_action" value="remove">
                    <button type="submit" class="w-full py-2 px-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold cursor-pointer">
                        Remove Password Protection
                    </button>
                </form>
            @endif
        </div>

        <!-- Edit Settings & Approved Fields -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3 text-xs">
            <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2.5"><i class="fas fa-sliders-h text-indigo-600 mr-1.5"></i> File Settings &amp; Visible Fields</h3>

            <form action="{{ route('admin.sharing-files.update', $sharingFile->id) }}" method="POST" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">File Title</label>
                    <input type="text" name="title" value="{{ $sharingFile->title }}" required class="w-full rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-800">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">Description / Note</label>
                    <textarea name="description" rows="2" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-800">{{ $sharingFile->description }}</textarea>
                </div>

                <label class="flex items-center gap-2 font-bold text-gray-800 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $sharingFile->is_active ? 'checked' : '' }}>
                    <span>Sharing Link Active</span>
                </label>

                <label class="flex items-center gap-2 font-semibold text-gray-700 cursor-pointer">
                    <input type="checkbox" name="separate_password_share" value="1" {{ $sharingFile->separate_password_share ? 'checked' : '' }}>
                    <span>Separate Password Share Mode (Safer)</span>
                </label>

                <div class="pt-2 border-t border-gray-100 space-y-1.5">
                    <span class="block text-[10px] font-bold uppercase text-gray-400">Approved Fields for School View</span>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_qualifications" value="1" {{ $sharingFile->allow_qualifications ? 'checked' : '' }}> Qualifications</label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_date_of_birth" value="1" {{ $sharingFile->allow_date_of_birth ? 'checked' : '' }}> Date of Birth</label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_current_salary" value="1" {{ $sharingFile->allow_current_salary ? 'checked' : '' }}> Current Salary</label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_expected_salary" value="1" {{ $sharingFile->allow_expected_salary ? 'checked' : '' }}> Expected Salary</label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_experience" value="1" {{ $sharingFile->allow_experience ? 'checked' : '' }}> Experience</label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_availability" value="1" {{ $sharingFile->allow_availability ? 'checked' : '' }}> Joining Availability</label>
                    <label class="flex items-center gap-2 font-bold text-emerald-700 cursor-pointer"><input type="checkbox" name="allow_cv_view" value="1" {{ $sharingFile->allow_cv_view ? 'checked' : '' }}> CV View Only (Download Blocked)</label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_photo" value="1" {{ $sharingFile->allow_photo ? 'checked' : '' }}> Candidate Photo</label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_contact" value="1" {{ $sharingFile->allow_contact ? 'checked' : '' }}> Phone &amp; Email</label>
                </div>

                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold cursor-pointer">
                    Save Settings &amp; Fields
                </button>
            </form>

            <form action="{{ route('admin.sharing-files.destroy', $sharingFile->id) }}" method="POST"
                  onsubmit="return confirm('Delete this entire school sharing file and all its categories permanently?')"
                  class="pt-3 border-t border-gray-100">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold cursor-pointer">
                    <i class="fas fa-trash-alt mr-1"></i> Delete Sharing File
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Categories + Candidates inside Category -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">
                    <i class="fas fa-folder-open text-blue-600 mr-2"></i>Categories inside "{{ $sharingFile->title }}"
                </h3>
                <form action="{{ route('admin.sharing-files.categories.store', $sharingFile->id) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <input type="text" name="name" required maxlength="120" placeholder="e.g., TGT Hindi, PGT Maths..."
                           class="rounded-xl border border-gray-200 px-3 py-1.5 text-xs text-gray-800 outline-none focus:border-blue-600">
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer">
                        + Add Category
                    </button>
                </form>
            </div>

            @if($sharingFile->categories->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($sharingFile->categories as $cat)
                        @php $isCatActive = $selectedCategory && (int) $selectedCategory->id === (int) $cat->id; @endphp
                        <div class="inline-flex items-center rounded-xl border {{ $isCatActive ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                            <a href="{{ route('admin.sharing-files.show', ['id' => $sharingFile->id, 'category_id' => $cat->id]) }}"
                               class="px-3 py-1.5 text-xs font-bold flex items-center gap-1.5">
                                <i class="fas fa-folder text-[11px]"></i>
                                <span>{{ $cat->name }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[10px] {{ $isCatActive ? 'bg-white/20 text-white' : 'bg-white text-gray-600' }}">{{ $cat->entries_count }}</span>
                            </a>
                            <form action="{{ route('admin.sharing-files.categories.destroy', ['id' => $sharingFile->id, 'categoryId' => $cat->id]) }}" method="POST"
                                  onsubmit="return confirm('Delete category {{ addslashes($cat->name) }} and remove its assigned candidates?')" class="pr-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] {{ $isCatActive ? 'text-white/75 hover:text-white' : 'text-gray-400 hover:text-rose-600' }} cursor-pointer" title="Delete Category">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 rounded-xl bg-gray-50 border border-dashed border-gray-200 text-xs text-gray-500">
                    No categories created yet. Add categories like <strong>TGT Hindi</strong>, <strong>PGT Maths</strong>, or <strong>PRT Hindi</strong> above, then add candidates from Candidates CRM.
                </div>
            @endif
        </div>

        <!-- Assigned Candidates in Selected Category -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h3 class="text-base font-bold text-gray-900">
                    <i class="fas fa-users text-indigo-600 mr-2"></i>
                    {{ $selectedCategory ? "Candidates in \"{$selectedCategory->name}\"" : 'Assigned Candidates' }}
                    <span class="text-xs font-semibold text-gray-400 ml-1">({{ $entries->count() }})</span>
                </h3>
                <a href="{{ route('admin.crm.index') }}" class="text-xs font-bold text-blue-600 hover:underline">+ Select Candidates in CRM</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-xs">
                    <thead class="bg-gray-50 text-[10px] font-bold uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Candidate</th>
                            <th class="px-5 py-3 text-left">Profile Snapshot</th>
                            <th class="px-5 py-3 text-left">Move Category</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($entries as $entry)
                            @php
                                $cand = $entry->candidate;
                                $cp = $cand?->profile;
                            @endphp
                            @if($cand)
                                <tr class="hover:bg-gray-50/70">
                                    <td class="px-5 py-3.5 align-top">
                                        <a href="{{ route('admin.crm.show', $cand->id) }}" class="font-bold text-gray-900 hover:text-blue-600 block">
                                            {{ $cand->name }}
                                        </a>
                                        <div class="font-mono text-[11px] text-gray-500">{{ $cp?->vpa_id ?? ('#' . $cand->id) }}</div>
                                        <div class="text-[11px] text-gray-600 mt-0.5">{{ $cp?->category?->name ?? 'General' }} · {{ $cp?->subject?->name ?? 'General' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 align-top text-gray-700 space-y-0.5">
                                        <div><strong>Qual:</strong> {{ $cp?->highestQualification?->name ?? 'N/A' }} {{ $cp?->other_qualifications ? "({$cp->other_qualifications})" : '' }}</div>
                                        <div><strong>DOB:</strong> {{ $cp?->date_of_birth ? $cp->date_of_birth->format('d M Y') : 'N/A' }}</div>
                                        <div><strong>Salary:</strong> Current {{ $cp?->current_salary ?: 'N/A' }} · Expected {{ $cp?->expected_salary ?: 'N/A' }}</div>
                                        <div><strong>CV:</strong> {!! $cp?->resume_path ? '<span class="text-emerald-700 font-bold">Uploaded (View-Only)</span>' : '<span class="text-gray-400">No CV</span>' !!}</div>
                                    </td>
                                    <td class="px-5 py-3.5 align-top">
                                        @if($sharingFile->categories->count() > 1)
                                            <form action="{{ route('admin.sharing-files.candidates.move', ['id' => $sharingFile->id, 'entryId' => $entry->id]) }}" method="POST" class="flex items-center gap-1.5">
                                                @csrf
                                                <select name="target_category_id" onchange="this.form.submit()" class="rounded-lg border border-gray-200 px-2 py-1 text-[11px] text-gray-700">
                                                    @foreach($sharingFile->categories as $cOpt)
                                                        <option value="{{ $cOpt->id }}" {{ (int) $cOpt->id === (int) $entry->category_id ? 'selected' : '' }}>{{ $cOpt->name }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @else
                                            <span class="text-gray-400">{{ $entry->category?->name }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 align-top text-right whitespace-nowrap">
                                        <form action="{{ route('admin.sharing-files.candidates.remove', ['id' => $sharingFile->id, 'entryId' => $entry->id]) }}" method="POST"
                                              onsubmit="return confirm('Remove this candidate from category?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold cursor-pointer">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-400 italic">
                                    No candidates assigned to this category yet. Select candidates in Candidates CRM and choose "Add to School File / Category".
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function copyTextValue(val, btn) {
        if (!navigator.clipboard) {
            window.prompt('Copy value:', val);
            return;
        }
        navigator.clipboard.writeText(val).then(() => {
            const span = btn.querySelector('span');
            if (!span) return;
            const prev = span.textContent;
            span.textContent = 'Copied!';
            setTimeout(() => { span.textContent = prev; }, 1600);
        });
    }
</script>
@endsection
