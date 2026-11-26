@extends('layouts.admin')

@section('title', 'School CRM: ' . ($profile?->school_name ?: $school->name))

@section('actions')
    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
        <a href="{{ route('admin.schools.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Back to Schools</a>

        <a href="{{ route('admin.schools.edit', $school->id) }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5 shadow-sm">
            <i class="fas fa-edit"></i> Edit School Profile
        </a>

        @if($school->is_active)
            <a href="{{ route('admin.users.impersonate', $school->id) }}" target="_blank"
               class="px-4 py-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5 shadow-sm">
                <i class="fas fa-sign-in-alt"></i> Login as School
            </a>
        @endif

        <form action="{{ route('admin.schools.toggle-status', $school->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                    class="px-4 py-2 {{ $school->is_active ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }} text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5 shadow-sm cursor-pointer">
                <i class="fas {{ $school->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                <span>{{ $school->is_active ? 'Deactivate' : 'Activate' }}</span>
            </button>
        </form>
    </div>
@endsection

@section('content')
@php
    $schoolTitle = $profile?->school_name ?: $school->name;
    $logoUrl = ($profile && $profile->logo_path) ? Storage::url($profile->logo_path) : '';
    $photoUploadUrl = route('admin.schools.photo', $school->id);
@endphp

@if(session('school_temp_password'))
    <div class="mb-6 bg-amber-50 border border-amber-300 rounded-2xl p-4 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="text-xs sm:text-sm text-amber-900">
            <p class="font-bold"><i class="fas fa-key mr-1.5 text-amber-600"></i> Auto-Generated Account Password (Shown Once Only)</p>
            <p class="mt-0.5 text-amber-800">
                Share this password securely with the school:
                <code class="font-mono font-extrabold bg-white px-2.5 py-0.5 rounded border border-amber-300 select-all ml-1">{{ session('school_temp_password') }}</code>
            </p>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Identity, Account & Checklist -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50/50 to-white flex items-center gap-4">
                <button type="button"
                        onclick='openAdminPhotoModal(@json($schoolTitle), @json("School #" . $school->id), @json($logoUrl), @json($photoUploadUrl), "School Logo / Photo")'
                        class="relative group shrink-0 rounded-2xl focus:outline-none cursor-pointer"
                        title="Click to enlarge or update school photo">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $schoolTitle }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow group-hover:ring-4 group-hover:ring-blue-400/40 transition-all">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-2xl border-2 border-white shadow">
                            {{ strtoupper(substr($schoolTitle, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-blue-600 text-white border-2 border-white flex items-center justify-center text-[10px] shadow">
                        <i class="fas {{ $logoUrl ? 'fa-search-plus' : 'fa-camera' }}"></i>
                    </span>
                </button>

                <div class="min-w-0">
                    <h2 class="text-lg font-extrabold text-gray-900 break-words">{{ $schoolTitle }}</h2>
                    <div class="mt-1 flex items-center gap-2 flex-wrap">
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $school->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $school->is_active ? 'Active Account' : 'Inactive' }}
                        </span>
                        <span class="text-[11px] font-mono text-gray-500">#{{ $school->id }}</span>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-3 text-xs">
                <div class="flex justify-between gap-2 pb-2 border-b border-gray-100">
                    <span class="text-gray-400 font-bold uppercase">Account Name</span>
                    <span class="font-semibold text-gray-800 text-right">{{ $school->name }}</span>
                </div>
                <div class="flex justify-between gap-2 pb-2 border-b border-gray-100">
                    <span class="text-gray-400 font-bold uppercase">Contact Person</span>
                    <span class="font-semibold text-gray-800 text-right">{{ $profile?->contact_person ?: 'Not set' }}</span>
                </div>
                <div class="flex justify-between gap-2 pb-2 border-b border-gray-100">
                    <span class="text-gray-400 font-bold uppercase">Email</span>
                    <span class="font-semibold text-gray-800 text-right break-all">{{ $school->email }}</span>
                </div>
                <div class="flex justify-between gap-2 pb-2 border-b border-gray-100">
                    <span class="text-gray-400 font-bold uppercase">Phone</span>
                    <span class="font-semibold text-gray-800 text-right">{{ $school->phone ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="text-gray-400 font-bold uppercase">Joined</span>
                    <span class="font-semibold text-gray-800 text-right">{{ $school->created_at?->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST</span>
                </div>

                <div class="pt-3">
                    <button type="button"
                            onclick='openAdminPhotoModal(@json($schoolTitle), @json("School #" . $school->id), @json($logoUrl), @json($photoUploadUrl), "School Logo / Photo")'
                            class="w-full py-2 px-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold transition-colors cursor-pointer">
                        <i class="fas fa-camera mr-1.5"></i> {{ $logoUrl ? 'View / Change School Photo' : 'Upload School Photo' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Saved Required Profile Checklist -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Saved Profile Completeness</h3>
            <ul class="space-y-2 text-xs">
                @php
                    $checks = [
                        ['label' => 'School Name', 'ok' => !empty($profile?->school_name)],
                        ['label' => 'Contact Person', 'ok' => !empty($profile?->contact_person)],
                        ['label' => 'School Address', 'ok' => !empty($profile?->address)],
                        ['label' => 'School Photo / Logo', 'ok' => !empty($profile?->logo_path)],
                        ['label' => 'About / Description', 'ok' => !empty($profile?->about)],
                    ];
                @endphp
                @foreach($checks as $chk)
                    <li class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                        <span class="text-gray-700 font-medium">{{ $chk['label'] }}</span>
                        <span class="font-bold {{ $chk['ok'] ? 'text-emerald-600' : 'text-amber-600' }}">
                            <i class="fas {{ $chk['ok'] ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                            {{ $chk['ok'] ? 'Saved' : 'Missing' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Right Column: Institution Details, Job Stats & Job Posts -->
    <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold uppercase text-gray-400">Total Jobs</p>
                <p class="text-2xl font-black text-gray-900 mt-0.5">{{ $jobSummary['total'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold uppercase text-gray-400">Live Jobs</p>
                <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ $jobSummary['live'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold uppercase text-gray-400">Pending Review</p>
                <p class="text-2xl font-black text-amber-600 mt-0.5">{{ $jobSummary['pending'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold uppercase text-gray-400">Rejected</p>
                <p class="text-2xl font-black text-rose-600 mt-0.5">{{ $jobSummary['rejected'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900"><i class="fas fa-university text-blue-600 mr-2"></i>Saved School Details</h3>
                <a href="{{ route('admin.schools.edit', $school->id) }}" class="text-xs font-bold text-blue-600 hover:underline">Edit Details</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold uppercase text-gray-400 block mb-1">Institution Name</span>
                    <span class="font-bold text-gray-900">{{ $profile?->school_name ?: 'Not provided yet' }}</span>
                </div>
                <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold uppercase text-gray-400 block mb-1">Contact Person</span>
                    <span class="font-bold text-gray-900">{{ $profile?->contact_person ?: 'Not provided yet' }}</span>
                </div>
                <div class="md:col-span-2 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold uppercase text-gray-400 block mb-1">Address</span>
                    <span class="text-gray-800">{{ $profile?->address ?: 'No address saved yet.' }}</span>
                </div>
                <div class="md:col-span-2 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold uppercase text-gray-400 block mb-1">About Institution</span>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $profile?->about ?: 'No description saved yet.' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900"><i class="fas fa-briefcase text-blue-600 mr-2"></i>Jobs Posted by This School</h3>
                <span class="text-xs font-bold text-gray-400">{{ $school->jobPosts->count() }} total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-xs">
                    <thead class="bg-gray-50 text-[10px] font-bold uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Job Title</th>
                            <th class="px-5 py-3 text-left">Location</th>
                            <th class="px-5 py-3 text-left">Applicants</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($school->jobPosts as $job)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-3 font-bold text-gray-900">
                                    {{ $job->title }}
                                    <div class="text-[10px] text-gray-400 font-normal mt-0.5">{{ $job->category?->name ?? '' }} {{ $job->subject?->name ? '· ' . $job->subject->name : '' }}</div>
                                </td>
                                <td class="px-5 py-3 text-gray-600">{{ $job->city?->name ?? 'N/A' }}, {{ $job->state?->name ?? '' }}</td>
                                <td class="px-5 py-3 font-semibold text-gray-700">{{ $job->applications_count }}</td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $job->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($job->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-blue-600 font-bold hover:underline">View Job</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400 italic">No job posts created by this school yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.crm.partials.photo-modal')
@endsection
