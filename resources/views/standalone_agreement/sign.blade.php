@extends('layouts.app')

@section('title', 'Service Agreement (' . $agreement->agreement_code . ') — Vedanta Placement Agency')

@section('content')
<style>
    .standalone-terms .text-text-main {
        color: #0f172a !important;
    }
    .standalone-terms .text-text-muted,
    .standalone-terms p,
    .standalone-terms li {
        color: #334155 !important;
    }
    .standalone-terms .text-brand-teal {
        color: #0b6e87 !important;
    }
    .standalone-terms h3,
    .standalone-terms h4 {
        color: #031b4e !important;
    }
</style>
<div class="bg-slate-50 min-h-screen py-8 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Document Header Banner -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-[#031b4e] via-[#072b75] to-[#0b6e87] px-6 py-6 text-white flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Vedanta Placement Agency" class="h-12 w-auto bg-white rounded-xl p-1.5 shadow-sm">
                    <div>
                        <p class="text-[11px] uppercase tracking-widest text-blue-100 font-bold">Vedanta Placement Agency®</p>
                        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Service Agreement — Digital Execution</h1>
                    </div>
                </div>

                @php
                    $statusLabel = $agreement->display_status;
                    $statusClasses = match($statusLabel) {
                        'Signed' => 'bg-emerald-500/20 text-emerald-100 border-emerald-300/40',
                        'Expired' => 'bg-rose-500/20 text-rose-100 border-rose-300/40',
                        'Resent' => 'bg-indigo-400/25 text-white border-indigo-200/40',
                        default => 'bg-amber-400/25 text-amber-50 border-amber-200/40',
                    };
                @endphp
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $statusClasses }}">
                    <i class="fas {{ $isSigned ? 'fa-check-circle' : ($isExpired ? 'fa-clock' : 'fa-file-signature') }}"></i>
                    <span>Status: {{ $statusLabel }}</span>
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 px-6 py-4 bg-slate-50/70 border-b border-slate-200 text-xs">
                <div>
                    <span class="uppercase font-bold tracking-wider text-slate-400 block">Agreement ID</span>
                    <span class="font-mono font-bold text-[#031b4e] text-sm">{{ $agreement->agreement_code }}</span>
                </div>
                <div>
                    <span class="uppercase font-bold tracking-wider text-slate-400 block">Recipient Email</span>
                    <span class="font-bold text-slate-800 text-sm break-all">{{ $agreement->recipient_email }}</span>
                </div>
                <div>
                    <span class="uppercase font-bold tracking-wider text-slate-400 block">{{ $isSigned ? 'Signed On' : ($isExpired ? 'Expired On' : 'Valid Until') }}</span>
                    <span class="font-semibold text-slate-800 text-sm">{{ $isSigned ? $signedDateIst : ($expiresDateIst ?: 'Active') }}</span>
                </div>
            </div>

            @if(!empty($agreement->admin_note))
                <div class="mx-6 mt-5 p-4 rounded-xl bg-blue-50 border border-blue-200 text-xs sm:text-sm text-blue-900">
                    <p class="font-bold mb-0.5"><i class="fas fa-info-circle mr-1 text-blue-600"></i> Note from Vedanta Placement Agency:</p>
                    <p>{{ $agreement->admin_note }}</p>
                </div>
            @endif

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-rose-600 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <p class="font-bold mb-1">Please fix the following before submitting:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($isSigned)
                    <!-- Executed State -->
                    <div class="bg-emerald-50/70 border border-emerald-200 rounded-2xl p-6 text-center space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto text-2xl">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-emerald-950">Agreement Digitally Signed &amp; Submitted</h2>
                            <p class="text-sm text-emerald-800 mt-1">
                                Signed by <strong>{{ $agreement->signer_name }}</strong> on {{ $signedDateIst }}.
                            </p>
                        </div>

                        @if($agreement->signature_type === 'draw' && !empty($agreement->signature_data))
                            <div class="inline-block bg-white border border-emerald-200 rounded-xl px-6 py-3">
                                <img src="{{ $agreement->signature_data }}" alt="Submitted Signature" class="max-h-20 mx-auto">
                            </div>
                        @elseif(!empty($agreement->signature_data))
                            <div class="inline-block bg-white border border-emerald-200 rounded-xl px-6 py-3 font-serif italic text-xl font-bold text-slate-900">
                                {{ $agreement->signature_data }}
                            </div>
                        @endif

                        <div>
                            <a href="{{ route('standalone-agreement.download', ['token' => $agreement->token]) }}"
                               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-sm transition-colors">
                                <i class="fas fa-file-pdf"></i>
                                <span>Download Completed Agreement PDF</span>
                            </a>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-600 mb-3">Executed Agreement Copy</h3>
                        <div class="standalone-terms border border-slate-200 rounded-2xl p-5 max-h-96 overflow-y-auto bg-white">
                            @include('candidate.partials.agreement-text')
                        </div>
                    </div>

                @elseif($isExpired)
                    <!-- Expired State -->
                    <div class="bg-rose-50/70 border border-rose-200 rounded-2xl p-8 text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto text-2xl">
                            <i class="fas fa-hourglass-end"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-rose-950">This Signing Link Has Expired</h2>
                        <p class="text-sm text-rose-800 max-w-lg mx-auto">
                            The signing window for agreement <strong>{{ $agreement->agreement_code }}</strong> closed on {{ $expiresDateIst ?? 'an earlier date' }}.
                            For security reasons, expired links cannot be signed.
                        </p>
                        <p class="text-xs text-rose-700 pt-2">
                            Please contact Vedanta Placement Agency at
                            <a href="mailto:info@vedantaplacementagency.in" class="underline font-bold">info@vedantaplacementagency.in</a>
                            or <strong>+91-7070938975</strong> to request a fresh signing link.
                        </p>
                    </div>

                @else
                    <!-- Active Review & Sign Form -->
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700">1. Review Agreement Terms &amp; Conditions</h2>
                                <span class="text-xs text-slate-400">Scroll to read complete terms</span>
                            </div>
                            <div class="standalone-terms border border-slate-200 rounded-2xl p-5 max-h-[420px] overflow-y-auto bg-white shadow-inner">
                                @include('candidate.partials.agreement-text')
                            </div>
                        </div>

                        <form id="standalone-sign-form" method="POST" action="{{ route('standalone-agreement.submit', ['token' => $agreement->token]) }}" class="space-y-5 border-t border-slate-200 pt-6">
                            @csrf
                            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700">2. Signatory Details &amp; Digital Signature</h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="signer_name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Full Legal Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="signer_name" name="signer_name" required
                                           value="{{ old('signer_name', $agreement->recipient_name) }}"
                                           placeholder="Enter your full name"
                                           class="w-full rounded-xl border border-slate-300 bg-white text-slate-900 px-3.5 py-2.5 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Verified Email Address
                                    </label>
                                    <input type="email" value="{{ $agreement->recipient_email }}" readonly
                                           class="w-full rounded-xl border border-slate-200 bg-slate-100 text-slate-600 px-3.5 py-2.5 text-sm cursor-not-allowed">
                                </div>

                                <div>
                                    <label for="recipient_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Phone Number <span class="text-slate-400 font-normal">(Optional)</span>
                                    </label>
                                    <input type="text" id="recipient_phone" name="recipient_phone"
                                           value="{{ old('recipient_phone', $agreement->recipient_phone) }}"
                                           placeholder="+91 98XXXXXXXX"
                                           class="w-full rounded-xl border border-slate-300 bg-white text-slate-900 px-3.5 py-2.5 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                                </div>
                            </div>

                            <!-- Signature Mode Switcher -->
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600">
                                        Digital Signature <span class="text-red-500">*</span>
                                    </label>
                                    <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs font-bold">
                                        <button type="button" id="tab-draw-btn"
                                                class="px-3.5 py-1.5 rounded-lg bg-white text-[#031b4e] shadow-sm transition-all">
                                            <i class="fas fa-pen-nib mr-1"></i> Draw Signature
                                        </button>
                                        <button type="button" id="tab-type-btn"
                                                class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                                            <i class="fas fa-keyboard mr-1"></i> Type Signature
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="signature_type" id="signature_type_input" value="{{ old('signature_type', 'draw') }}">
                                <input type="hidden" name="drawn_signature" id="drawn_signature_input" value="{{ old('drawn_signature') }}">

                                <div id="draw-panel" class="space-y-2">
                                    <div class="border-2 border-dashed border-slate-300 rounded-2xl bg-white p-2">
                                        <canvas id="standalone-sig-pad" width="760" height="200"
                                                class="w-full h-44 bg-white rounded-xl cursor-crosshair block"
                                                style="touch-action: none;"></canvas>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span id="sig-status-text" class="text-slate-500">
                                            <i class="fas fa-pen mr-1"></i> Sign inside the white box using your mouse, trackpad, or finger.
                                        </span>
                                        <button type="button" id="clear-sig-btn"
                                                class="px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold">
                                            <i class="fas fa-eraser mr-1"></i> Clear
                                        </button>
                                    </div>
                                </div>

                                <div id="type-panel" class="hidden space-y-3">
                                    <input type="text" id="typed_signature" name="typed_signature"
                                           value="{{ old('typed_signature', $agreement->recipient_name) }}"
                                           placeholder="Type your full name as your electronic signature"
                                           class="w-full rounded-xl border border-slate-300 bg-white text-slate-900 px-4 py-3 text-sm focus:border-[#031b4e] focus:ring-2 focus:ring-[#031b4e]/15 outline-none">
                                    <div class="border border-slate-200 rounded-xl bg-slate-50 px-5 py-4 text-center">
                                        <span class="text-[11px] uppercase tracking-wider text-slate-400 block mb-1">Signature Preview</span>
                                        <div id="typed-preview" class="font-serif italic text-2xl font-bold text-slate-900 min-h-[2rem]">
                                            {{ old('typed_signature', $agreement->recipient_name) ?: 'Your Signature' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                                <input type="checkbox" name="terms_accepted" id="terms_accepted" value="1"
                                       {{ old('terms_accepted') ? 'checked' : '' }} required
                                       class="mt-1 h-4 w-4 rounded border-slate-300 text-[#031b4e] focus:ring-[#031b4e]">
                                <span class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                                    I confirm that I have read, understood, and voluntarily accept all terms of this <strong>Vedanta Placement Agency Service Agreement</strong>, and I consent to executing it electronically via this secure digital signing link.
                                </span>
                            </label>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                                <p class="text-xs text-slate-500">
                                    <i class="fas fa-lock text-emerald-600 mr-1"></i>
                                    Signed timestamp and audit details are recorded automatically upon submission.
                                </p>
                                <button type="submit" id="submit-agreement-btn"
                                        class="inline-flex items-center gap-2 bg-[#031b4e] hover:bg-blue-900 text-white font-bold text-sm px-7 py-3 rounded-xl shadow-md transition-all">
                                    <i class="fas fa-check-double"></i>
                                    <span>Sign &amp; Submit Agreement</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!$isSigned && !$isExpired)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('standalone-sign-form');
    const canvas = document.getElementById('standalone-sig-pad');
    const ctx = canvas.getContext('2d');
    const sigTypeInput = document.getElementById('signature_type_input');
    const drawnInput = document.getElementById('drawn_signature_input');
    const tabDrawBtn = document.getElementById('tab-draw-btn');
    const tabTypeBtn = document.getElementById('tab-type-btn');
    const drawPanel = document.getElementById('draw-panel');
    const typePanel = document.getElementById('type-panel');
    const clearBtn = document.getElementById('clear-sig-btn');
    const statusText = document.getElementById('sig-status-text');
    const typedInput = document.getElementById('typed_signature');
    const typedPreview = document.getElementById('typed-preview');
    const signerNameInput = document.getElementById('signer_name');

    let drawing = false;
    let hasStroke = false;

    ctx.lineWidth = 2.6;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#0f172a';

    function setMode(mode) {
        sigTypeInput.value = mode;
        if (mode === 'type') {
            drawPanel.classList.add('hidden');
            typePanel.classList.remove('hidden');
            tabTypeBtn.classList.add('bg-white', 'text-[#031b4e]', 'shadow-sm');
            tabTypeBtn.classList.remove('text-slate-600');
            tabDrawBtn.classList.remove('bg-white', 'text-[#031b4e]', 'shadow-sm');
            tabDrawBtn.classList.add('text-slate-600');
            if (!typedInput.value.trim() && signerNameInput.value.trim()) {
                typedInput.value = signerNameInput.value.trim();
                typedPreview.textContent = typedInput.value;
            }
        } else {
            typePanel.classList.add('hidden');
            drawPanel.classList.remove('hidden');
            tabDrawBtn.classList.add('bg-white', 'text-[#031b4e]', 'shadow-sm');
            tabDrawBtn.classList.remove('text-slate-600');
            tabTypeBtn.classList.remove('bg-white', 'text-[#031b4e]', 'shadow-sm');
            tabTypeBtn.classList.add('text-slate-600');
        }
    }

    setMode(sigTypeInput.value === 'type' ? 'type' : 'draw');

    tabDrawBtn.addEventListener('click', function () { setMode('draw'); });
    tabTypeBtn.addEventListener('click', function () { setMode('type'); });

    typedInput.addEventListener('input', function () {
        typedPreview.textContent = typedInput.value.trim() || 'Your Signature';
    });

    function getCanvasPos(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: (clientX - rect.left) * (canvas.width / rect.width),
            y: (clientY - rect.top) * (canvas.height / rect.height),
        };
    }

    function startDraw(e) {
        drawing = true;
        const pos = getCanvasPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        e.preventDefault();
    }

    function moveDraw(e) {
        if (!drawing) return;
        const pos = getCanvasPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        if (!hasStroke) {
            hasStroke = true;
            statusText.innerHTML = '<i class="fas fa-check-circle text-emerald-600 mr-1"></i> Signature captured.';
        }
        e.preventDefault();
    }

    function endDraw() {
        if (!drawing) return;
        drawing = false;
        ctx.closePath();
        if (hasStroke) {
            drawnInput.value = canvas.toDataURL('image/png');
        }
    }

    if (window.PointerEvent) {
        canvas.addEventListener('pointerdown', startDraw);
        canvas.addEventListener('pointermove', moveDraw);
        window.addEventListener('pointerup', endDraw);
    } else {
        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', moveDraw);
        window.addEventListener('mouseup', endDraw);
        canvas.addEventListener('touchstart', startDraw, { passive: false });
        canvas.addEventListener('touchmove', moveDraw, { passive: false });
        window.addEventListener('touchend', endDraw);
    }

    clearBtn.addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasStroke = false;
        drawnInput.value = '';
        statusText.innerHTML = '<i class="fas fa-pen mr-1"></i> Sign inside the white box using your mouse, trackpad, or finger.';
    });

    form.addEventListener('submit', function (e) {
        if (sigTypeInput.value === 'draw') {
            if (!hasStroke) {
                e.preventDefault();
                alert('Please draw your signature in the signature box (or switch to Type Signature).');
                return;
            }
            drawnInput.value = canvas.toDataURL('image/png');
        } else if (!typedInput.value.trim()) {
            e.preventDefault();
            alert('Please type your signature name.');
            typedInput.focus();
        }
    });
});
</script>
@endpush
@endif
@endsection
