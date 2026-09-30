@extends('layouts.candidate')

@section('candidate_content')
<div class="w-full space-y-6">

    {{-- Page Header --}}
    <div class="text-center mb-8 reveal">
        <div class="w-14 h-14 rounded-2xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-2xl mx-auto mb-4">
            <i class="fas fa-file-contract"></i>
        </div>
        <h1 class="text-2xl font-bold text-text-main">Candidate Agreement</h1>
        <p class="text-sm text-text-dark/50 mt-2 max-w-md mx-auto">Please read the terms and conditions carefully and provide your digital signature below.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl flex items-center gap-3 justify-center reveal">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span class="text-sm text-red-400 font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Agreement Card --}}
    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal reveal-delay-1">

        {{-- Terms / Document Section --}}
        <div class="p-6 md:p-8 border-b border-card-border">
            @if($profile->is_manual_agreement && $profile->agreement_pdf_path)
                <div class="flex items-center justify-between gap-4 mb-5 flex-wrap">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-lg border border-purple-500/20 shadow-sm">
                            <i class="fas fa-file-contract"></i>
                        </span>
                        <div>
                            <h2 class="text-lg font-bold text-text-main flex items-center gap-2">
                                Official Placement Agreement
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/15 text-purple-300 border border-purple-500/30">Admin Uploaded</span>
                            </h2>
                            <p class="text-xs text-text-dark/60 mt-0.5">Official agreement document assigned and uploaded by Vedanta Placement Agency Administration.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('candidate.agreement.preview') }}" target="_blank" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue/90 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-md shadow-accent-blue/20">
                            <i class="fas fa-external-link-alt text-[10px]"></i> Open in New Tab
                        </a>
                        <a href="{{ route('candidate.agreement.download') }}" class="px-4 py-2 bg-secondary-bg hover:bg-card-border/50 text-text-main rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-card-border">
                            <i class="fas fa-download text-[10px]"></i> Download PDF
                        </a>
                    </div>
                </div>

                {{-- Embedded PDF Viewer --}}
                <div class="rounded-xl overflow-hidden border border-card-border shadow-inner bg-secondary-bg/60">
                    <iframe src="{{ route('candidate.agreement.preview') }}#toolbar=0" class="w-full h-[650px] border-0" title="Candidate Agreement PDF"></iframe>
                </div>
            @else
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xs"><i class="fas fa-scroll"></i></span>
                    <h2 class="text-lg font-bold text-text-main">Terms and Conditions</h2>
                </div>
                <div class="h-96 overflow-y-auto pr-4 text-sm text-text-dark/80 space-y-4 custom-scrollbar bg-secondary-bg/30 rounded-xl p-6 border border-card-border">
                    @include('candidate.partials.agreement-text')
                    
                    <p class="mt-8 font-semibold text-accent-yellow italic border-l-2 border-accent-yellow/40 pl-4">By signing below, you acknowledge that you have read, understood, and agree to be bound by these terms.</p>
                </div>
            @endif
        </div>

        {{-- Signature Section --}}
        @if($profile->is_agreement_signed)
            <div class="p-6 md:p-8 bg-green-500/5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-text-main mb-1">
                            {{ $profile->is_manual_agreement ? 'Official Agreement Active & Binding' : 'Agreement Digitally Signed' }}
                        </h3>
                        <p class="text-sm text-text-dark/60 mb-6">
                            {{ $profile->is_manual_agreement ? 'Your official agreement has been verified and uploaded by the administration.' : 'You have accepted the terms and conditions.' }}
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-6 mb-6">
                            {{-- Digital Signature / Agreement Status --}}
                            <div class="bg-card-bg border border-card-border rounded-xl p-5 flex-1">
                                <h4 class="text-xs font-semibold text-text-main/50 uppercase tracking-wider mb-3">
                                    Agreement Verification Status
                                </h4>
                                
                                @if($profile->is_manual_agreement)
                                    <div class="flex items-center gap-2 text-purple-400 font-semibold mb-2 text-sm">
                                        <i class="fas fa-file-pdf text-base"></i> Custom Official Agreement PDF
                                    </div>
                                    <p class="text-xs text-text-dark/60 mb-3">
                                        This agreement has been verified, approved, and officially uploaded by Vedanta Administration.
                                    </p>
                                    <div class="text-xs text-text-dark/50 mt-4 pt-4 border-t border-card-border">
                                        <span class="block text-text-dark/30 mb-0.5">Uploaded / Effective Date</span>
                                        <span class="font-medium text-text-main/80">{{ $profile->agreement_signed_at ? \Carbon\Carbon::parse($profile->agreement_signed_at)->format('d M Y, h:i A') : $profile->updated_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                @elseif($profile->signature_data)
                                    @if($profile->signature_type === 'draw' || Str::startsWith($profile->signature_data, 'data:image'))
                                        <img src="{{ $profile->signature_data }}" alt="Digital Signature" class="h-20 bg-white rounded object-contain px-2 mb-3">
                                    @elseif($profile->signature_type === 'type')
                                        <div class="font-signature text-3xl text-text-main mb-3">{{ $profile->signature_data }}</div>
                                    @elseif($profile->signature_type === 'upload')
                                        <img src="{{ asset('storage/' . $profile->signature_data) }}" alt="Uploaded Signature" class="h-20 object-contain mb-3">
                                    @else
                                        <p class="text-lg font-medium text-text-main mb-3">{{ $profile->signature_data }}</p>
                                    @endif
                                    
                                    <div class="text-xs text-text-dark/50 mt-4 pt-4 border-t border-card-border">
                                        <span class="block text-text-dark/30 mb-0.5">Signed On</span>
                                        <span class="font-medium text-text-main/80">{{ $profile->signature_date_time ? \Carbon\Carbon::parse($profile->signature_date_time)->format('d M Y, h:i A') : $profile->updated_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                @else
                                    <p class="text-sm font-medium text-text-main mb-3">
                                        <i class="fas fa-file-pdf text-accent-blue mr-1"></i> Agreement recorded in system.
                                    </p>
                                    <div class="text-xs text-text-dark/50 mt-4 pt-4 border-t border-card-border">
                                        <span class="block text-text-dark/30 mb-0.5">Recorded On</span>
                                        <span class="font-medium text-text-main/80">{{ $profile->updated_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Live Photo --}}
                            @if($profile->live_photo_path)
                            <div class="bg-card-bg border border-card-border rounded-xl p-5 flex-1">
                                <h4 class="text-xs font-semibold text-text-main/50 uppercase tracking-wider mb-3">
                                    Identity Verification Photo
                                </h4>
                                <img src="{{ asset('storage/' . $profile->live_photo_path) }}" alt="Live Photo" class="h-20 w-auto rounded-lg object-cover mb-3 border border-card-border">
                                
                                <div class="text-xs text-text-dark/50 mt-4 pt-4 border-t border-card-border">
                                    <span class="block text-text-dark/30 mb-0.5">Location Captured</span>
                                    <span class="font-medium text-text-main/80">
                                        @if($profile->latitude && $profile->longitude)
                                            {{ number_format($profile->latitude, 4) }}, {{ number_format($profile->longitude, 4) }}
                                        @else
                                            Not Available
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            <a href="{{ route('candidate.agreement.preview') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent-blue text-white font-medium rounded-lg hover:bg-accent-blue/90 shadow-md shadow-accent-blue/20 transition-all text-sm">
                                <i class="fas fa-eye"></i> View Live PDF Preview
                            </a>
                            <a href="{{ route('candidate.agreement.download') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent-blue/10 text-accent-blue font-medium rounded-lg hover:bg-accent-blue/20 transition-colors text-sm">
                                <i class="fas fa-file-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-6 md:p-8">
                <form action="{{ route('candidate.agreement.sign') }}" method="POST" id="signature-form">
                @csrf
                <input type="hidden" name="signature" id="signature-data">

                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-xs"><i class="fas fa-pen-fancy"></i></span>
                        <div>
                            <h3 class="text-lg font-bold text-text-main">Digital Signature</h3>
                            <p class="text-xs text-text-dark/40 mt-0.5">Use your mouse or touchscreen to sign inside the box below.</p>
                        </div>
                    </div>

                    <div class="border-2 border-dashed border-card-border rounded-xl bg-secondary-bg/30 relative overflow-hidden group hover:border-accent-blue/30 transition-colors" style="width: 100%; max-width: 500px;">
                        <canvas id="signature-pad" class="w-full h-48 cursor-crosshair touch-none"></canvas>
                        <div class="absolute bottom-2 right-2 text-[10px] text-text-dark/20 pointer-events-none">Sign here</div>
                    </div>
                    <div class="mt-2.5">
                        <button type="button" id="clear-signature" class="text-xs text-red-400 hover:text-red-300 font-medium flex items-center gap-1.5 transition-colors">
                            <i class="fas fa-eraser"></i> Clear Signature
                        </button>
                    </div>
                </div>

                <div class="mb-6 bg-accent-blue/5 border border-accent-blue/10 p-4 rounded-xl flex items-start gap-3">
                    <input id="terms_accepted" name="terms_accepted" type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-card-border text-accent-blue focus:ring-accent-blue/50 bg-secondary-bg cursor-pointer">
                    <label for="terms_accepted" class="text-sm text-text-dark/60 cursor-pointer leading-relaxed">
                        I hereby declare that I agree to all the terms and conditions mentioned above and my digital signature is legally binding.
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="submit-btn" class="px-8 py-3 bg-accent-blue text-white font-semibold rounded-xl hover:bg-accent-blue-hover hover:-translate-y-0.5 transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-file-signature"></i> Sign Agreement
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    const clearBtn = document.getElementById('clear-signature');
    const form = document.getElementById('signature-form');
    const signatureDataInput = document.getElementById('signature-data');

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.lineWidth = 2;
        ctx.strokeStyle = getComputedStyle(document.documentElement).getPropertyValue('--theme-text-main').trim() || '#ffffff';
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    let isDrawing = false;
    let hasDrawn = false;
    let lastX = 0;
    let lastY = 0;

    function getCoordinates(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.clientX || e.touches[0].clientX;
        const clientY = e.clientY || e.touches[0].clientY;
        return [clientX - rect.left, clientY - rect.top];
    }

    function startDrawing(e) {
        isDrawing = true;
        hasDrawn = true;
        [lastX, lastY] = getCoordinates(e);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        const [x, y] = getCoordinates(e);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(x, y);
        ctx.stroke();
        [lastX, lastY] = [x, y];
    }

    function stopDrawing() { isDrawing = false; }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    canvas.addEventListener('touchstart', startDrawing, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDrawing);

    clearBtn.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasDrawn = false;
    });

    form.addEventListener('submit', (e) => {
        if (!hasDrawn) {
            e.preventDefault();
            alert('Please provide your signature before submitting.');
            return;
        }
        signatureDataInput.value = canvas.toDataURL('image/png');
    });
});
</script>
@endpush
@endsection
