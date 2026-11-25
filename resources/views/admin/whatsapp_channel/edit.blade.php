@extends('layouts.admin')

@section('title', 'WhatsApp Channel Promotion')
@section('subtitle', 'Configure your official WhatsApp Channel link so visitors can join directly from public pages.')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-card-bg rounded-2xl border border-card-border shadow-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-card-border flex items-center justify-between gap-4 bg-secondary-bg/40">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#25D366]/15 text-[#16a34a] flex items-center justify-center text-2xl">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 class="font-bold text-text-main text-base">WhatsApp Channel Settings</h3>
                    <p class="text-xs text-text-dark/70">Displays dismissible promotion cards on job listings, the footer, and public pages.</p>
                </div>
            </div>
            @if($isEnabled && \App\Models\SiteSetting::isValidWhatsAppChannelUrl($channelUrl))
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold uppercase tracking-wider">Active</span>
            @else
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-extrabold uppercase tracking-wider">Hidden</span>
            @endif
        </div>

        <form action="{{ route('admin.whatsapp-channel.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="whatsapp_channel_url" class="block text-xs font-bold text-text-dark uppercase tracking-wider mb-2">
                    WhatsApp Channel Join URL
                </label>
                <input type="url"
                       id="whatsapp_channel_url"
                       name="whatsapp_channel_url"
                       value="{{ old('whatsapp_channel_url', $channelUrl) }}"
                       placeholder="https://whatsapp.com/channel/0029Va..."
                       class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/40 focus:border-accent-blue transition-all">
                <p class="text-xs text-text-dark/70 mt-2 leading-relaxed">
                    <i class="fas fa-info-circle text-accent-blue mr-1"></i>
                    Enter your official WhatsApp Channel URL starting with <code class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 font-semibold">https://whatsapp.com/channel/</code>. Direct chat links (<code class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 font-semibold">wa.me</code>) are blocked so the Join button always opens your channel page.
                </p>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl bg-secondary-bg/60 border border-card-border">
                <div>
                    <div class="text-sm font-bold text-text-main">Show WhatsApp Channel Promotion</div>
                    <div class="text-xs text-text-dark/70">Enable to show the dismissible channel banner near job listings, in the footer, and as a compact floating card.</div>
                </div>
                <label class="inline-flex items-center gap-2 cursor-pointer shrink-0">
                    <input type="checkbox" name="is_enabled" value="1" {{ old('_token') ? (old('is_enabled') ? 'checked' : '') : ($isEnabled ? 'checked' : '') }}
                           class="w-5 h-5 rounded border-card-border text-accent-blue focus:ring-accent-blue bg-white">
                    <span class="text-xs font-bold text-text-main">Enabled</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-card-border">
                <button type="submit" class="px-6 py-2.5 bg-accent-blue hover:bg-accent-blue-hover text-white rounded-xl text-sm font-bold transition-all shadow-lg cursor-pointer">
                    Save Channel Settings
                </button>
            </div>
        </form>
    </div>

    {{-- Live Card Preview --}}
    <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm space-y-5">
        <h4 class="text-xs font-bold uppercase tracking-wider text-text-dark">Visitor Widget Preview</h4>
        <div class="max-w-sm rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-lg">
            <div class="bg-gradient-to-r from-[#031b4e] via-[#072a6e] to-[#0a368a] px-4 py-3 flex items-center justify-between gap-2.5 text-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center shrink-0">
                        <i class="fas fa-bell text-amber-300 text-xs"></i>
                    </div>
                    <div>
                        <div class="text-[13px] font-extrabold text-white leading-tight">Vedanta Job Alerts</div>
                        <div class="text-[10.5px] text-blue-100/85 font-medium">Verified School Vacancies • PAN-India</div>
                    </div>
                </div>
                <span class="w-7 h-7 rounded-lg bg-white/10 text-white/80 flex items-center justify-center text-xs">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <div class="p-3 bg-white">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50/80 border border-emerald-200">
                    <div class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="text-[13px] font-extrabold text-[#040e2d]">WhatsApp Channel</span>
                            <i class="fas fa-check-circle text-[#16a34a] text-[11px]"></i>
                        </div>
                        <p class="text-[11px] font-medium text-slate-600 m-0 leading-snug">
                            Join our WhatsApp Channel to get daily vacancies.
                        </p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <span class="px-2.5 py-1 rounded-lg bg-[#16a34a] text-white font-bold text-[11px]">Join</span>
                        <i class="fas fa-chevron-right text-[#16a34a] text-[10px]"></i>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 border-t border-slate-100 px-3.5 py-1.5 flex items-center justify-center gap-1.5 text-[10.5px] font-medium text-slate-500">
                <i class="fas fa-lock text-amber-500 text-[9px]"></i>
                <span>Official Channel • No Spam • Daily Updates</span>
            </div>
        </div>
    </div>
</div>
@endsection
