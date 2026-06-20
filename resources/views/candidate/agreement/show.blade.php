@extends('layouts.app')

@section('content')
@include('candidate.partials.nav')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

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

        {{-- Terms Section --}}
        <div class="p-6 md:p-8 border-b border-card-border">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xs"><i class="fas fa-scroll"></i></span>
                <h2 class="text-lg font-bold text-text-main">Terms and Conditions</h2>
            </div>
            <div class="h-80 overflow-y-auto pr-4 text-sm text-text-dark/60 space-y-4 custom-scrollbar bg-secondary-bg/30 rounded-xl p-6 border border-card-border">
                <p>This Agreement is entered into between Vedanta Placement Agency ("Agency") and <strong class="text-text-main">{{ $user->name }}</strong> ("Candidate").</p>

                <h3 class="font-bold text-text-main mt-4">1. Services Provided</h3>
                <p>The Agency agrees to provide placement assistance services to the Candidate by matching their profile with suitable job openings available with associated schools and educational institutions.</p>

                <h3 class="font-bold text-text-main mt-4">2. Candidate Obligations</h3>
                <p>The Candidate affirms that all information provided in their profile, including educational qualifications and experience, is true and accurate. Any misrepresentation may lead to immediate termination of services.</p>

                <h3 class="font-bold text-text-main mt-4">3. Fees and Payment</h3>
                <p>The Candidate agrees to pay the non-refundable registration and processing fee as selected in the payment plan. Further, if placed successfully, the Candidate agrees to pay the stipulated service charge (e.g., 50% of the first month's salary) within 15 days of joining.</p>

                <h3 class="font-bold text-text-main mt-4">4. Confidentiality</h3>
                <p>Both parties agree to maintain strict confidentiality regarding job details, salary negotiations, and personal information shared during the process.</p>

                <h3 class="font-bold text-text-main mt-4">5. Termination</h3>
                <p>This agreement can be terminated by either party with a written notice. However, pending service charges for successful placements made prior to termination will remain payable.</p>

                <p class="mt-8 font-semibold text-accent-yellow italic border-l-2 border-accent-yellow/40 pl-4">By signing below, you acknowledge that you have read, understood, and agree to be bound by these terms.</p>
            </div>
        </div>

        {{-- Signature Section --}}
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
                        <i class="fas fa-file-signature"></i> Sign & Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
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
