@extends('layouts.admin')

@section('title', 'All Notifications')
@section('subtitle', 'View and manage all your system notifications')

@section('content')
<div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-50 text-accent-blue rounded-xl flex items-center justify-center">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-800">All Notifications</h2>
                <p class="text-xs text-gray-400">{{ $notifications->total() }} total</p>
            </div>
        </div>
        <a href="{{ route('admin.notifications.mark-all-read') }}"
           class="text-sm text-white bg-accent-blue hover:bg-blue-700 px-4 py-2 rounded-xl font-semibold transition-colors shadow-sm flex items-center gap-2">
            <i class="fas fa-check-double text-xs"></i> Mark All Read
        </a>
    </div>

    <div class="divide-y divide-gray-50">
        @forelse($notifications as $notif)
            <div class="flex items-start gap-4 px-6 py-4 {{ $notif->read_at ? 'bg-white opacity-75' : 'bg-blue-50/30' }} hover:bg-gray-50 transition-colors">
                <!-- Icon -->
                <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-sm
                    {{ str_contains($notif->type, 'LeadFollowUp') ? 'bg-purple-100 text-purple-600' :
                      (str_contains($notif->type, 'CandidateFollowUpReminder') ? 'bg-amber-100 text-amber-600' :
                      (str_contains($notif->type, 'StandaloneAgreementSigned') ? 'bg-emerald-100 text-emerald-700' :
                      (str_contains($notif->type, 'EmployerAgreementUploaded') ? 'bg-indigo-100 text-indigo-700' :
                      (str_contains($notif->type, 'SchoolProfileAccessRequested') ? 'bg-amber-100 text-amber-700' :
                      (str_contains($notif->type, 'VerificationRequested') ? 'bg-cyan-100 text-cyan-600' :
                      (str_contains($notif->type, 'PaymentReceived') ? 'bg-emerald-100 text-emerald-600' :
                      (str_contains($notif->type, 'LateFee') ? 'bg-orange-100 text-orange-600' :
                      (str_contains($notif->type, 'ProfileVerified') ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600')))))))) }}">
                    @if(str_contains($notif->type, 'LeadFollowUp'))
                        <i class="fas fa-user-clock"></i>
                    @elseif(str_contains($notif->type, 'CandidateFollowUpReminder'))
                        <i class="fas fa-calendar-check"></i>
                    @elseif(str_contains($notif->type, 'StandaloneAgreementSigned'))
                        <i class="fas fa-file-signature"></i>
                    @elseif(str_contains($notif->type, 'EmployerAgreementUploaded'))
                        <i class="fas fa-file-signature"></i>
                    @elseif(str_contains($notif->type, 'SchoolProfileAccessRequested'))
                        <i class="fas fa-user-shield"></i>
                    @elseif(str_contains($notif->type, 'VerificationRequested'))
                        <i class="fas fa-user-check"></i>
                    @elseif(str_contains($notif->type, 'PaymentReceived'))
                        <i class="fas fa-rupee-sign"></i>
                    @elseif(str_contains($notif->type, 'LateFee'))
                        <i class="fas fa-exclamation-triangle"></i>
                    @elseif(str_contains($notif->type, 'ProfileVerified'))
                        <i class="fas fa-check-circle"></i>
                    @else
                        <i class="fas fa-bell"></i>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    @if(!empty($notif->target_url))
                        <a href="{{ route('admin.notifications.mark-read', $notif->id) }}"
                           class="text-sm font-bold text-gray-800 hover:text-accent-blue transition-colors inline-flex items-center gap-1.5">
                            <span>{{ $notif->data->title ?? 'Notification' }}</span>
                            <i class="fas fa-arrow-right text-[10px] text-accent-blue"></i>
                        </a>
                    @else
                        <p class="text-sm font-bold text-gray-800">{{ $notif->data->title ?? 'Notification' }}</p>
                    @endif
                    <p class="text-sm text-gray-500 mt-0.5">{{ $notif->data->message ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1.5">
                        <i class="fas fa-clock text-[10px]"></i>
                        {{ \Carbon\Carbon::parse($notif->created_at)->format('d M Y, h:i A') }}
                        · {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                    </p>
                </div>

                <!-- Status & Action -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    @if(!empty($notif->target_url))
                        <a href="{{ route('admin.notifications.mark-read', $notif->id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-accent-blue hover:bg-accent-blue hover:text-white border border-blue-100 text-xs font-bold transition-colors whitespace-nowrap">
                            <span>{{ !empty($notif->data->candidate_id) ? 'Open Candidate' : 'Open Record' }}</span>
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                        </a>
                    @endif
                    @if(!$notif->read_at)
                        <span class="w-2.5 h-2.5 bg-blue-500 rounded-full shadow-[0_0_6px_rgba(59,130,246,0.5)]"></span>
                        <a href="{{ route('admin.notifications.mark-read', ['id' => $notif->id, 'stay' => 1]) }}"
                           class="text-xs text-accent-blue hover:underline font-semibold whitespace-nowrap">Mark Read</a>
                    @else
                        <span class="text-xs text-gray-400 font-medium">Read</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-16 text-center">
                <div class="text-5xl text-gray-200 mb-3"><i class="fas fa-bell-slash"></i></div>
                <p class="text-gray-400 font-semibold">No notifications yet</p>
                <p class="text-gray-300 text-sm mt-1">System events and alerts will appear here</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
