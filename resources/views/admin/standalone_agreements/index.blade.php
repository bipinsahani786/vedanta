@extends('layouts.admin')

@section('title', 'Send Agreement')
@section('subtitle', 'Send direct digital signing links to any email without candidate registration, profile completion, or payment')

@section('content')
<div class="space-y-6">
    @if(!$tableReady)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-5 flex items-start gap-3">
            <i class="fas fa-database mt-0.5 text-amber-600"></i>
            <div class="text-sm">
                <p class="font-bold">Database migration required</p>
                <p class="mt-1">The <code>standalone_agreements</code> table does not exist yet. Run <code>php artisan migrate --force</code> to activate Standalone Send Agreement tracking.</p>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-4 rounded-xl shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 text-sm font-medium">
                <i class="fas fa-exclamation-triangle text-amber-500"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-900 text-sm">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-sm">
            <p class="font-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Please check the form fields:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Status Summary / Quick Filter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @php
            $summaryCards = [
                [
                    'key' => 'all',
                    'label' => 'Total Sent',
                    'count' => $counts['total'],
                    'icon' => 'fa-paper-plane',
                    'ring' => 'ring-slate-900/15 bg-slate-50 border-slate-300',
                    'iconBg' => 'bg-slate-100 text-slate-700',
                ],
                [
                    'key' => 'pending',
                    'label' => 'Pending',
                    'count' => $counts['pending'],
                    'icon' => 'fa-hourglass-half',
                    'ring' => 'ring-amber-500/20 bg-amber-50/70 border-amber-300',
                    'iconBg' => 'bg-amber-100 text-amber-700',
                ],
                [
                    'key' => 'resent',
                    'label' => 'Resent',
                    'count' => $counts['resent'],
                    'icon' => 'fa-redo-alt',
                    'ring' => 'ring-indigo-500/20 bg-indigo-50/70 border-indigo-300',
                    'iconBg' => 'bg-indigo-100 text-indigo-700',
                ],
                [
                    'key' => 'signed',
                    'label' => 'Signed',
                    'count' => $counts['signed'],
                    'icon' => 'fa-check-circle',
                    'ring' => 'ring-emerald-500/20 bg-emerald-50/70 border-emerald-300',
                    'iconBg' => 'bg-emerald-100 text-emerald-700',
                ],
                [
                    'key' => 'expired',
                    'label' => 'Expired',
                    'count' => $counts['expired'],
                    'icon' => 'fa-clock',
                    'ring' => 'ring-rose-500/20 bg-rose-50/70 border-rose-300',
                    'iconBg' => 'bg-rose-100 text-rose-700',
                ],
            ];
        @endphp

        @foreach($summaryCards as $card)
            @php $isActive = $activeStatus === $card['key']; @endphp
            <a href="{{ route('admin.standalone-agreements.index', array_filter(['status' => $card['key'] === 'all' ? null : $card['key'], 'q' => $search ?: null])) }}"
               class="rounded-2xl border p-4 transition-all flex items-center justify-between {{ $isActive ? 'ring-2 ' . $card['ring'] . ' shadow-sm' : 'bg-white border-gray-100 hover:border-gray-300 shadow-sm' }}">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ $card['label'] }}</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $card['count'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $card['iconBg'] }}">
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Standalone Send Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r from-[#031b4e]/[0.03] to-transparent">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#031b4e] text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Send Standalone Agreement</h2>
                    <p class="text-xs text-gray-500">Enter any email address to issue a secure signing link directly — no account, profile, plan, or payment needed.</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="fas fa-bolt text-[10px]"></i> Zero Registration Prerequisites
            </span>
        </div>

        <form method="POST" action="{{ route('admin.standalone-agreements.store') }}" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label for="recipient_email" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Recipient Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="recipient_email" name="recipient_email" required
                           value="{{ old('recipient_email') }}"
                           placeholder="recipient@example.com"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                </div>

                <div>
                    <label for="recipient_name" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Recipient Name <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" id="recipient_name" name="recipient_name"
                           value="{{ old('recipient_name') }}"
                           placeholder="Full name (or filled by signer)"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                </div>

                <div>
                    <label for="expiry_days" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Link Expiry Window
                    </label>
                    <select id="expiry_days" name="expiry_days"
                            class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm bg-white focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                        <option value="3" {{ (string) old('expiry_days') === '3' ? 'selected' : '' }}>3 Days</option>
                        <option value="7" {{ (string) old('expiry_days', '7') === '7' ? 'selected' : '' }}>7 Days (Default)</option>
                        <option value="14" {{ (string) old('expiry_days') === '14' ? 'selected' : '' }}>14 Days</option>
                        <option value="30" {{ (string) old('expiry_days') === '30' ? 'selected' : '' }}>30 Days</option>
                        <option value="60" {{ (string) old('expiry_days') === '60' ? 'selected' : '' }}>60 Days</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="recipient_phone" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Phone Number <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" id="recipient_phone" name="recipient_phone"
                           value="{{ old('recipient_phone') }}"
                           placeholder="+91 98XXXXXXXX"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                </div>

                <div>
                    <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Custom Email Subject <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" id="subject" name="subject"
                           value="{{ old('subject') }}"
                           placeholder="Service Agreement for Digital Signature"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                </div>

                <div>
                    <label for="admin_note" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Note for Recipient <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" id="admin_note" name="admin_note"
                           value="{{ old('admin_note') }}"
                           placeholder="Role / school / instruction note shown on email & form"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-shield-alt text-[#0b6e87] mr-1"></i>
                    A unique tokenized URL will be emailed immediately and tracked below.
                </p>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#031b4e] hover:bg-blue-900 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition-all">
                    <i class="fas fa-paper-plane text-xs"></i>
                    <span>Send Agreement Link</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Sent Agreements Tracking List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Sent Agreements Tracker</h3>
                <p class="text-xs text-gray-500">Track delivery, resends, expirations, and inspect completed signed agreement PDF copies.</p>
            </div>

            <form method="GET" action="{{ route('admin.standalone-agreements.index') }}" class="flex items-center gap-2">
                @if($activeStatus !== 'all')
                    <input type="hidden" name="status" value="{{ $activeStatus }}">
                @endif
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="Search email, name, code..."
                           class="pl-9 pr-3 py-2 text-xs rounded-xl border border-gray-200 w-56 sm:w-64 focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                </div>
                <button type="submit" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">
                    Search
                </button>
                @if($search !== '' || $activeStatus !== 'all')
                    <a href="{{ route('admin.standalone-agreements.index') }}"
                       class="px-3 py-2 text-xs font-semibold rounded-xl text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50/80 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Recipient &amp; Agreement ID</th>
                        <th class="px-6 py-3 text-left">Sent / Updated Dates</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Completed Signature</th>
                        <th class="px-6 py-3 text-right">Actions &amp; Copy</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($agreements as $item)
                        @php
                            $displayStatus = $item->display_status;
                            $statusBadge = match($displayStatus) {
                                'Signed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Resent' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'Expired' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                            $isHighlighted = (string) request('highlight') === (string) $item->id;
                        @endphp
                        <tr class="{{ $isHighlighted ? 'bg-blue-50/50' : 'hover:bg-gray-50/70' }} transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-xs font-bold text-[#031b4e] bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                                        {{ $item->agreement_code }}
                                    </span>
                                    @if($item->sender)
                                        <span class="text-[11px] text-gray-400">by {{ $item->sender->name }}</span>
                                    @endif
                                </div>
                                <div class="font-bold text-gray-900 mt-1.5">{{ $item->recipient_email }}</div>
                                @if($item->recipient_name || $item->recipient_phone)
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $item->recipient_name }}
                                        @if($item->recipient_name && $item->recipient_phone) &bull; @endif
                                        {{ $item->recipient_phone }}
                                    </div>
                                @endif
                                @if($item->admin_note)
                                    <div class="text-[11px] text-gray-400 mt-1 max-w-xs truncate" title="{{ $item->admin_note }}">
                                        <i class="fas fa-sticky-note mr-1"></i> {{ $item->admin_note }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 align-top text-xs text-gray-600 space-y-1">
                                <div>
                                    <span class="text-gray-400">Sent:</span>
                                    <span class="font-semibold text-gray-800">{{ \App\Models\StandaloneAgreement::formatIst($item->sent_at) ?? '—' }}</span>
                                </div>
                                @if($item->resent_count > 0 && $item->resent_at)
                                    <div>
                                        <span class="text-indigo-500 font-semibold">Resent ({{ $item->resent_count }}x):</span>
                                        <span class="text-gray-700">{{ \App\Models\StandaloneAgreement::formatIst($item->resent_at) }}</span>
                                    </div>
                                @endif
                                @if($item->isSigned() && $item->signed_at)
                                    <div>
                                        <span class="text-emerald-600 font-semibold">Signed:</span>
                                        <span class="font-bold text-emerald-700">{{ \App\Models\StandaloneAgreement::formatIst($item->signed_at) }}</span>
                                    </div>
                                @elseif($item->expires_at)
                                    <div>
                                        <span class="text-gray-400">{{ $item->isExpired() ? 'Expired:' : 'Expires:' }}</span>
                                        <span class="{{ $item->isExpired() ? 'text-rose-600 font-semibold' : 'text-gray-700' }}">
                                            {{ \App\Models\StandaloneAgreement::formatIst($item->expires_at) }}
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusBadge }}">
                                    @if($displayStatus === 'Signed')
                                        <i class="fas fa-check-circle text-[10px]"></i>
                                    @elseif($displayStatus === 'Resent')
                                        <i class="fas fa-redo-alt text-[10px]"></i>
                                    @elseif($displayStatus === 'Expired')
                                        <i class="fas fa-times-circle text-[10px]"></i>
                                    @else
                                        <i class="fas fa-clock text-[10px]"></i>
                                    @endif
                                    <span>{{ $displayStatus }}</span>
                                </span>
                                @if($displayStatus === 'Resent')
                                    <p class="text-[11px] text-indigo-600 mt-1">Resent {{ $item->resent_count }} {{ \Illuminate\Support\Str::plural('time', $item->resent_count) }}</p>
                                @elseif($displayStatus === 'Pending')
                                    <p class="text-[11px] text-amber-600 mt-1">Awaiting signature</p>
                                @endif
                            </td>

                            <td class="px-6 py-4 align-top text-xs">
                                @if($item->isSigned())
                                    <div class="font-bold text-gray-900">{{ $item->signer_name }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">
                                        {{ $item->signature_type === 'draw' ? 'Drawn Signature' : 'Typed Signature' }}
                                        @if($item->signer_ip)
                                            &bull; IP: {{ $item->signer_ip }}
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Not signed yet</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 align-top text-right">
                                <div class="inline-flex flex-wrap items-center justify-end gap-2">
                                    @if($item->isSigned())
                                        <a href="{{ route('admin.standalone-agreements.pdf', $item) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-sm">
                                            <i class="fas fa-eye text-[11px]"></i>
                                            <span>View Signed Copy</span>
                                        </a>
                                        <a href="{{ route('admin.standalone-agreements.pdf', ['agreement' => $item, 'download' => 1]) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition-colors">
                                            <i class="fas fa-download text-[11px]"></i>
                                            <span>Download PDF</span>
                                        </a>
                                    @else
                                        <button type="button"
                                                onclick="copySigningUrl('{{ $item->signing_url }}', this)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                                            <i class="fas fa-link text-[11px]"></i>
                                            <span>Copy Link</span>
                                        </button>

                                        <form method="POST" action="{{ route('admin.standalone-agreements.resend', $item) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 text-xs font-bold transition-colors">
                                                <i class="fas fa-redo-alt text-[11px]"></i>
                                                <span>Resend</span>
                                            </button>
                                        </form>

                                        @if(!$item->isExpired())
                                            <form method="POST" action="{{ route('admin.standalone-agreements.expire', $item) }}" class="inline"
                                                  onsubmit="return confirm('Mark agreement {{ $item->agreement_code }} as Expired so this signing link stops accepting signatures?');">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 text-xs font-bold transition-colors">
                                                    <i class="fas fa-ban text-[11px]"></i>
                                                    <span>Expire</span>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-file-signature text-lg"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-700">No standalone agreements found</p>
                                <p class="text-xs text-gray-400 mt-1">Use the form above to send a direct digital agreement link to any email address.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tableReady && $agreements instanceof \Illuminate\Pagination\AbstractPaginator && $agreements->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $agreements->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function copySigningUrl(url, button) {
        if (!navigator.clipboard) {
            window.prompt('Copy signing URL:', url);
            return;
        }
        navigator.clipboard.writeText(url).then(function () {
            const label = button.querySelector('span');
            const original = label.textContent;
            label.textContent = 'Copied!';
            button.classList.add('bg-emerald-100', 'text-emerald-700');
            setTimeout(function () {
                label.textContent = original;
                button.classList.remove('bg-emerald-100', 'text-emerald-700');
            }, 1800);
        });
    }
</script>
@endpush
@endsection
