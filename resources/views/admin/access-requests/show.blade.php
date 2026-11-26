@extends('layouts.admin')

@section('title', 'Review Profile Access Request #' . $accessRequest->id)

@section('actions')
    <a href="{{ route('admin.access-requests.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Back to Access Requests</a>
@endsection

@section('content')
@php
    $school = $accessRequest->school;
    $schoolProfile = $school?->employerProfile;
    $schoolName = $schoolProfile?->school_name ?: ($school?->name ?? 'School');

    $candidate = $accessRequest->candidate;
    $candProfile = $candidate?->profile;

    $statusBadgeClass = match ($accessRequest->status) {
        'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'partially_approved' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
        'declined' => 'bg-rose-100 text-rose-800 border-rose-200',
        default => 'bg-amber-100 text-amber-800 border-amber-200',
    };
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: School Identity & Candidate Summary -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <h3 class="text-sm font-bold text-gray-900"><i class="fas fa-school text-blue-600 mr-1.5"></i> Requesting School</h3>
                @if($school)
                    <a href="{{ route('admin.schools.show', $school->id) }}" class="text-xs font-bold text-blue-600 hover:underline">Open School CRM</a>
                @endif
            </div>
            <div class="text-xs space-y-1.5">
                <div class="text-base font-extrabold text-gray-900">{{ $schoolName }}</div>
                <div class="text-gray-600">Account: <strong>{{ $school?->name ?? 'N/A' }}</strong> (#{{ $accessRequest->school_user_id }})</div>
                <div class="text-gray-600"><i class="fas fa-envelope w-4 text-gray-400"></i> {{ $school?->email ?? 'N/A' }}</div>
                <div class="text-gray-600"><i class="fas fa-phone-alt w-4 text-gray-400"></i> {{ $school?->phone ?? 'N/A' }}</div>
                @if($schoolProfile?->address)
                    <div class="text-gray-500"><i class="fas fa-map-marker-alt w-4 text-gray-400"></i> {{ $schoolProfile->address }}</div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <h3 class="text-sm font-bold text-gray-900"><i class="fas fa-user-graduate text-indigo-600 mr-1.5"></i> Candidate Record</h3>
                @if($candidate)
                    <a href="{{ route('admin.crm.show', $candidate->id) }}" class="text-xs font-bold text-blue-600 hover:underline">Open Candidate CRM</a>
                @endif
            </div>
            <div class="text-xs space-y-1.5">
                <div class="text-base font-extrabold text-gray-900">{{ $candidate?->name ?? 'Unknown' }}</div>
                <div class="font-mono text-gray-500">{{ $candProfile?->vpa_id ?? ('#' . $accessRequest->candidate_user_id) }}</div>
                <div class="text-gray-700 font-semibold">{{ $candProfile?->category?->name ?? 'General' }} · {{ $candProfile?->subject?->name ?? 'General' }}</div>
                <div class="text-gray-600">Qualification: {{ $candProfile?->highestQualification?->name ?? 'N/A' }}</div>
                <div class="text-gray-600">Experience: {{ (int) ($candProfile?->experience_years ?? 0) }} Year(s) · Salary: {{ $candProfile?->current_salary ?: 'Not Disclosed' }}</div>
                <div class="text-gray-600">Availability: {{ $candProfile?->availability_to_join ?: 'On Inquiry' }}</div>
                <div class="pt-2 border-t border-gray-100 space-y-1">
                    <div><i class="fas fa-envelope w-4 text-gray-400"></i> {{ $candidate?->email ?? 'N/A' }}</div>
                    <div><i class="fas fa-phone-alt w-4 text-gray-400"></i> {{ $candidate?->phone ?? 'N/A' }}</div>
                    <div>
                        <i class="fas fa-file-pdf w-4 text-gray-400"></i>
                        @if($candProfile?->resume_path)
                            <a href="{{ Storage::url($candProfile->resume_path) }}" target="_blank" class="text-blue-600 font-bold hover:underline">Preview Candidate CV</a>
                        @else
                            <span class="text-gray-400">No CV uploaded</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Field-Level Permissions Review + History Log -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900"><i class="fas fa-user-shield text-blue-600 mr-2"></i>Field-Level Permission Controls</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Choose exactly which fields to share and how the school receives access.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusBadgeClass }}">
                    Status: {{ $accessRequest->statusLabel() }}
                </span>
            </div>

            <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 text-xs space-y-1.5">
                <div><strong class="text-blue-900">School Requested:</strong> {{ implode(', ', $accessRequest->requestedPermissionLabels()) ?: 'Profile Unlock' }}</div>
                @if(!empty($accessRequest->school_note))
                    <div class="text-gray-700"><strong>School Note:</strong> "{{ $accessRequest->school_note }}"</div>
                @endif
                @if($accessRequest->reviewed_at)
                    <div class="text-gray-600">
                        Last Reviewed: <strong>{{ $accessRequest->reviewed_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST</strong>
                        by {{ $accessRequest->reviewer?->name ?? 'Admin' }}
                        @if($accessRequest->email_sent_at)
                            · <span class="text-emerald-700 font-semibold">Shared via Email {{ $accessRequest->email_sent_at->timezone('Asia/Kolkata')->format('d M, h:i A') }} IST</span>
                        @endif
                    </div>
                @endif
            </div>

            @error('permissions')
                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-700">{{ $message }}</div>
            @enderror

            <form action="{{ route('admin.access-requests.review', $accessRequest->id) }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Grant Field Permissions</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-400 cursor-pointer bg-gray-50/50">
                            <input type="checkbox" name="can_view_photo" value="1"
                                   {{ old('can_view_photo', $accessRequest->status === 'pending' ? $accessRequest->requested_photo : $accessRequest->can_view_photo) ? 'checked' : '' }}
                                   class="mt-0.5 rounded text-blue-600">
                            <div>
                                <span class="font-bold text-gray-900 block text-xs">Profile Photo (Unblurred)</span>
                                <span class="text-[11px] text-gray-500">Allow clear candidate photograph instead of blurred preview.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-400 cursor-pointer bg-gray-50/50">
                            <input type="checkbox" name="can_view_full_profile" value="1"
                                   {{ old('can_view_full_profile', $accessRequest->status === 'pending' ? $accessRequest->requested_full_profile : $accessRequest->can_view_full_profile) ? 'checked' : '' }}
                                   class="mt-0.5 rounded text-blue-600">
                            <div>
                                <span class="font-bold text-gray-900 block text-xs">Full Profile Details</span>
                                <span class="text-[11px] text-gray-500">Full name, current school, address, expected salary, certifications.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-400 cursor-pointer bg-gray-50/50">
                            <input type="checkbox" name="can_view_cv" value="1"
                                   {{ old('can_view_cv', $accessRequest->status === 'pending' ? $accessRequest->requested_cv : $accessRequest->can_view_cv) ? 'checked' : '' }}
                                   class="mt-0.5 rounded text-blue-600">
                            <div>
                                <span class="font-bold text-gray-900 block text-xs">CV / Resume View</span>
                                <span class="text-[11px] text-gray-500">Allow downloading candidate's uploaded resume / CV.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-400 cursor-pointer bg-gray-50/50">
                            <input type="checkbox" name="can_view_contact" value="1"
                                   {{ old('can_view_contact', $accessRequest->status === 'pending' ? $accessRequest->requested_contact : $accessRequest->can_view_contact) ? 'checked' : '' }}
                                   class="mt-0.5 rounded text-blue-600">
                            <div>
                                <span class="font-bold text-gray-900 block text-xs">Contact Details (Email &amp; Phone)</span>
                                <span class="text-[11px] text-gray-500">Expose candidate mobile number &amp; email address to school.</span>
                            </div>
                        </label>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1.5">Selecting all 4 sets status to <strong>Approved</strong>; selecting 1–3 sets status to <strong>Partially Approved</strong>.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Sharing / Delivery Method</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        @php $curMethod = old('delivery_method', $accessRequest->delivery_method ?: 'dashboard'); @endphp
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-blue-400 cursor-pointer">
                            <input type="radio" name="delivery_method" value="dashboard" {{ $curMethod === 'dashboard' ? 'checked' : '' }}>
                            <div>
                                <span class="font-bold text-gray-900 block">School Dashboard</span>
                                <span class="text-[10px] text-gray-500">Unlock securely in portal</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-blue-400 cursor-pointer">
                            <input type="radio" name="delivery_method" value="email" {{ $curMethod === 'email' ? 'checked' : '' }}>
                            <div>
                                <span class="font-bold text-gray-900 block">Email Only</span>
                                <span class="text-[10px] text-gray-500">Send approved fields to email</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-blue-400 cursor-pointer">
                            <input type="radio" name="delivery_method" value="both" {{ $curMethod === 'both' ? 'checked' : '' }}>
                            <div>
                                <span class="font-bold text-gray-900 block">Dashboard &amp; Email</span>
                                <span class="text-[10px] text-gray-500">Unlock portal + email copy</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="admin_notes" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">
                        Admin Notes / Decline Reason
                    </label>
                    <textarea id="admin_notes" name="admin_notes" rows="2" maxlength="1000"
                              placeholder="Optional remark on approval; required when declining a request..."
                              class="w-full rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-800 focus:border-blue-600 outline-none">{{ old('admin_notes', $accessRequest->admin_notes) }}</textarea>
                    @error('admin_notes')
                        <p class="text-xs text-rose-700 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" name="decision" value="decline"
                            onclick="return confirm('Decline this access request and revoke any dashboard view? Requires a reason above.')"
                            class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold cursor-pointer">
                        <i class="fas fa-times-circle mr-1"></i> Decline Request
                    </button>

                    <button type="submit" name="decision" value="approve"
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs cursor-pointer">
                        <i class="fas fa-check-circle mr-1"></i> Save &amp; Grant Selected Permissions
                    </button>
                </div>
            </form>
        </div>

        <!-- Audit Log Timeline -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-base font-bold text-gray-900"><i class="fas fa-history text-blue-600 mr-2"></i>Access &amp; Request History Log</h3>
            <div class="divide-y divide-gray-100 text-xs">
                @forelse($accessRequest->logs as $log)
                    <div class="py-3 flex flex-wrap items-start justify-between gap-2">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-bold uppercase text-[10px]">{{ str_replace('_', ' ', $log->action) }}</span>
                                <span class="font-semibold text-gray-800">Status: {{ ucwords(str_replace('_', ' ', $log->status)) }}</span>
                                @if(!empty($log->granted_permissions))
                                    <span class="text-emerald-700 font-semibold">· Permissions: {{ implode(', ', $log->granted_permissions) }}</span>
                                @endif
                                @if($log->delivery_method)
                                    <span class="text-blue-600 font-semibold">· Delivery: {{ ucfirst($log->delivery_method) }}</span>
                                @endif
                            </div>
                            @if($log->note)
                                <p class="text-gray-600 italic">"{{ $log->note }}"</p>
                            @endif
                            <p class="text-[11px] text-gray-400">Actor: {{ $log->actor?->name ?? 'System' }}</p>
                        </div>
                        <span class="text-[11px] text-gray-400 shrink-0">{{ $log->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST</span>
                    </div>
                @empty
                    <p class="py-4 text-gray-400 italic">No audit entries yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
