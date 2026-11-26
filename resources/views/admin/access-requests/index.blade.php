@extends('layouts.admin')

@section('title', 'School Profile Access Requests')
@section('subtitle', 'Review school requests to unlock candidate photos, full profiles, CVs, and contact details with field-level permissions.')

@section('content')
@if(!$featureReady)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-sm text-amber-800">
        School discovery &amp; access request tables are not migrated yet. Please run <code class="font-mono font-bold">php artisan migrate --force</code>.
    </div>
@else
    <div class="space-y-6">
        <!-- Status Filter KPI Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $kpiCards = [
                    ['key' => null, 'label' => 'All Requests', 'count' => $stats['total'], 'color' => 'text-blue-600'],
                    ['key' => 'pending', 'label' => 'Pending Review', 'count' => $stats['pending'], 'color' => 'text-amber-600'],
                    ['key' => 'approved', 'label' => 'Approved', 'count' => $stats['approved'], 'color' => 'text-emerald-600'],
                    ['key' => 'partially_approved', 'label' => 'Partially Approved', 'count' => $stats['partially_approved'], 'color' => 'text-indigo-600'],
                    ['key' => 'declined', 'label' => 'Declined', 'count' => $stats['declined'], 'color' => 'text-rose-600'],
                ];
            @endphp
            @foreach($kpiCards as $kc)
                @php $isAct = $kc['key'] === null ? !request('status') : request('status') === $kc['key']; @endphp
                <a href="{{ request()->fullUrlWithQuery(['status' => $kc['key'], 'page' => null]) }}"
                   class="bg-white rounded-2xl border p-4 shadow-xs transition-all {{ $isAct ? 'border-blue-600 ring-2 ring-blue-500/20' : 'border-gray-100 hover:border-gray-300' }}">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ $kc['label'] }}</p>
                    <p class="text-2xl font-black mt-1 {{ $kc['color'] }}">{{ $kc['count'] }}</p>
                </a>
            @endforeach
        </div>

        <!-- Requests Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <form action="{{ route('admin.access-requests.index') }}" method="GET" class="flex flex-wrap items-center gap-2 flex-1 min-w-[260px]">
                    <div class="relative flex-1 min-w-[220px] max-w-md">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search school name/email or candidate VPA ID/name/subject..."
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:border-blue-600 outline-none">
                    </div>

                    <select name="status" onchange="this.form.submit()" class="rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 bg-white outline-none">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="partially_approved" {{ request('status') === 'partially_approved' ? 'selected' : '' }}>Partially Approved</option>
                        <option value="declined" {{ request('status') === 'declined' ? 'selected' : '' }}>Declined</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('admin.access-requests.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-gray-500 hover:bg-gray-100">Reset</a>
                    @endif
                </form>

                <span class="text-xs text-gray-400 font-medium">
                    Showing {{ $requests->firstItem() ?? 0 }} to {{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">School / Institution</th>
                            <th class="px-6 py-3 text-left">Requested Candidate</th>
                            <th class="px-6 py-3 text-left">Requested &amp; Granted Fields</th>
                            <th class="px-6 py-3 text-left">Status &amp; Delivery</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($requests as $req)
                            @php
                                $schoolName = $req->school?->employerProfile?->school_name ?: ($req->school?->name ?? 'Unknown School');
                                $candProfile = $req->candidate?->profile;
                                $badgeClass = match ($req->status) {
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'partially_approved' => 'bg-indigo-100 text-indigo-800',
                                    'declined' => 'bg-rose-100 text-rose-800',
                                    default => 'bg-amber-100 text-amber-800',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4 align-top">
                                    <a href="{{ $req->school ? route('admin.schools.show', $req->school->id) : '#' }}" class="font-bold text-gray-900 hover:text-blue-600 block">
                                        {{ $schoolName }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $req->school?->email }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">Requested {{ $req->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST</div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <a href="{{ $req->candidate ? route('admin.crm.show', $req->candidate->id) : '#' }}" class="font-bold text-gray-900 hover:text-blue-600 block">
                                        {{ $req->candidate?->name ?? 'Unknown' }}
                                    </a>
                                    <div class="text-xs font-mono text-gray-500 mt-0.5">{{ $candProfile?->vpa_id ?? ('#' . $req->candidate_user_id) }}</div>
                                    <div class="text-xs text-gray-600 mt-0.5">{{ $candProfile?->category?->name ?? 'General' }} · {{ $candProfile?->subject?->name ?? 'General' }}</div>
                                </td>

                                <td class="px-6 py-4 align-top text-xs space-y-1.5">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-gray-400">Requested:</span>
                                        <span class="text-gray-700">{{ implode(', ', $req->requestedPermissionLabels()) ?: 'All' }}</span>
                                    </div>
                                    @if(!empty($req->school_note))
                                        <div class="text-[11px] text-gray-500 italic max-w-xs truncate" title="{{ $req->school_note }}">"{{ $req->school_note }}"</div>
                                    @endif
                                    @if(!empty($req->grantedPermissionLabels()))
                                        <div class="flex flex-wrap gap-1 pt-0.5">
                                            @foreach($req->grantedPermissionLabels() as $gp)
                                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">{{ $gp }} ✓</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 align-top text-xs space-y-1">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                                        {{ $req->statusLabel() }}
                                    </span>
                                    @if($req->isUnlockGranted())
                                        <div class="text-[11px] text-gray-600">Method: <strong>{{ ucfirst($req->delivery_method) }}</strong></div>
                                    @endif
                                    @if($req->email_sent_at)
                                        <div class="text-[10px] text-emerald-700">Emailed {{ $req->email_sent_at->timezone('Asia/Kolkata')->format('d M, h:i A') }}</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 align-top text-right whitespace-nowrap">
                                    <a href="{{ route('admin.access-requests.show', $req->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs">
                                        <i class="fas fa-sliders-h"></i> Review &amp; Grant Access
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    No school profile access requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="p-4 border-t border-gray-100">{{ $requests->links() }}</div>
            @endif
        </div>

        <!-- Global Access & Request History Audit Log -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">
                    <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>Recent Access &amp; Request History Log
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-xs">
                    <thead class="bg-gray-50 text-[10px] font-bold uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-2.5 text-left">When (IST)</th>
                            <th class="px-5 py-2.5 text-left">School</th>
                            <th class="px-5 py-2.5 text-left">Candidate</th>
                            <th class="px-5 py-2.5 text-left">Action</th>
                            <th class="px-5 py-2.5 text-left">Permissions / Method</th>
                            <th class="px-5 py-2.5 text-left">Actor / Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentLogs as $log)
                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-2.5 text-gray-500 whitespace-nowrap">{{ $log->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                                <td class="px-5 py-2.5 font-semibold text-gray-800">{{ $log->school?->employerProfile?->school_name ?: ($log->school?->name ?? 'N/A') }}</td>
                                <td class="px-5 py-2.5 font-medium text-gray-700">{{ $log->candidate?->name ?? 'N/A' }} ({{ $log->candidate?->profile?->vpa_id ?? '#' . $log->candidate_user_id }})</td>
                                <td class="px-5 py-2.5">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-bold uppercase text-[10px]">{{ str_replace('_', ' ', $log->action) }}</span>
                                </td>
                                <td class="px-5 py-2.5 text-gray-600">
                                    {{ !empty($log->granted_permissions) ? implode(', ', $log->granted_permissions) : '—' }}
                                    @if($log->delivery_method) <span class="text-blue-600 font-semibold">({{ ucfirst($log->delivery_method) }})</span> @endif
                                </td>
                                <td class="px-5 py-2.5 text-gray-600">
                                    <span class="font-semibold">{{ $log->actor?->name ?? 'System' }}</span>
                                    @if($log->note) · <span class="italic text-gray-500">{{ $log->note }}</span> @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-6 text-center text-gray-400 italic">No access logs recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
