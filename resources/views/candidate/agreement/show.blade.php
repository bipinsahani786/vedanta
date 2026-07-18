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
            <div class="h-96 overflow-y-auto pr-4 text-sm text-text-dark/80 space-y-4 custom-scrollbar bg-secondary-bg/30 rounded-xl p-6 border border-card-border">
                <h4 class="font-bold text-text-main mb-4 text-center">Vedanta Placement Agency – Candidate Overview / Terms & Conditions</h4>
                
                <p>This document sets forth the official Terms & Conditions, policies, responsibilities, and professional expectations applicable to all candidates registering with Vedanta Placement Agency.</p>
                <p>By registering with the Agency and proceeding further, the candidate acknowledges and enters into a legal and professional agreement governed by these Terms & Conditions.</p>
                
                <h5 class="font-bold text-text-main mt-4 mb-2">Purpose of This Document</h5>
                <p>The objective of this document is to ensure clarity, transparency, and mutual understanding between the candidate and Vedanta Placement Agency throughout the recruitment and placement process.</p>

                <h5 class="font-bold text-text-main mt-4 mb-2">TERMS & CONDITIONS (SUMMARY)</h5>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Registration:</strong> A non-refundable registration fee of ₹1,000 is payable. Registration remains valid for 3 job applications.</li>
                    <li><strong>Eligibility:</strong> Candidates must meet eligibility criteria as prescribed by the hiring institution. Documents: Submission of genuine and verifiable documents is mandatory. Any misrepresentation may result in cancellation without refund.</li>
                    <li><strong>Interviews & Demos:</strong> Attendance as scheduled is compulsory. Non-attendance may lead to removal from opportunities.</li>
                    <li><strong>Selection & Joining:</strong> Final selection rests solely with the hiring institution. Candidates must honor joining commitments once selected.</li>
                    <li><strong>Service Charges:</strong> The candidate agrees to pay the applicable service charge within 12 hours of receiving the first month's salary: Teaching Staff – 50% of one month's gross salary | Management/Non-Teaching Staff – 66.67% of one month's gross salary (20 days' salary).</li>
                    <li><strong>Refund Policy:</strong> Registration fees are strictly non-refundable under any circumstances.</li>
                    <li><strong>Payment Default:</strong> Delay or failure in payment may attract penalties, service suspension, or legal action.</li>
                    <li><strong>Job Commitment:</strong> A minimum service period of 90 working days is required unless otherwise agreed in writing.</li>
                    <li>These terms shall be deemed legally binding and enforceable, subject to the jurisdiction of Patna, Bihar.</li>
                </ul>

                <h5 class="font-bold text-text-main mt-4 mb-2">PAYMENT, CONFIDENTIALITY & LEGAL COMPLIANCE</h5>
                <ul class="list-disc pl-5 space-y-2">
                    <li>The candidate agrees to remit the applicable service charge within twelve (12) hours of receipt of the first salary.</li>
                    <li>Failure to make payment within the stipulated period shall attract a late penalty of ₹300 per day until the outstanding amount is cleared in full.</li>
                    <li>Non-payment beyond seven (7) days shall be treated as a material breach of contract under the Indian Contract Act, 1872, and may result in recovery proceedings, blacklisting, and suspension or termination of all placement services.</li>
                    <li>The candidate shall maintain strict confidentiality and shall not misuse, disclose, or share any employer, school, or Agency information. Any such violation may attract action under applicable laws, including the Information Technology Act, 2000, wherever applicable.</li>
                    <li>These terms shall be deemed legally binding and enforceable, subject to the exclusive jurisdiction of Patna, Bihar.</li>
                </ul>

                <h5 class="font-bold text-text-main mt-4 mb-2">Candidates must:</h5>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Follow the school’s internal guidelines and rules be punctual and cooperative and maintain decorum and professionalism at all times.</li>
                    <li>Candidates must not share or misuse School contact information, Job leads and agency reference letters or documents.</li>
                    <li>Approaching a school directly or through any third party after receiving the lead from the Agency will result in Immediate blacklisting and legal action under data breach or professional misconduct.</li>
                </ul>

                <h5 class="font-bold text-text-main mt-4 mb-2">Registration fee is strictly non-refundable under any condition:</h5>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Rejection by school.</li>
                    <li>Voluntary withdrawal by candidate.</li>
                    <li>Change of mind.</li>
                    <li>The service charge is also non-refundable once the candidate has received their salary and the due period for payment has begun.</li>
                    <li>Refunds will not be entertained for dissatisfaction with salary, location, or working conditions post joining.</li>
                </ul>

                <h5 class="font-bold text-text-main mt-4 mb-2">BEHAVIORAL CODE OF CONDUCT</h5>
                <p><strong>Candidates must always:</strong></p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Be respectful and honest in communication.</li>
                    <li>Maintain professional appearance and behavior.</li>
                    <li>Refrain from abusive language or harassment.</li>
                    <li>Avoid any disputes with the employer during tenure.</li>
                    <li>Complaints from employers regarding attitude, communication, or ethics will be taken seriously and may result in blacklisting.</li>
                </ul>

                <h5 class="font-bold text-text-main mt-4 mb-2">COMMUNICATION GUIDELINES</h5>
                <p>All communication from the Agency will be done via: WhatsApp (only through registered numbers), Email (vedantaplacementagency@gmail.com), Direct phone calls.</p>
                <p><strong>Candidates must:</strong></p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Respond within 24–48 hours to all official communications</li>
                    <li>Keep their registered mobile number and email active</li>
                    <li>Inform the Agency of any number/email changes.</li>
                    <li>Failure to communicate may result in cancellation of interview or job opportunity.</li>
                </ul>

                <hr class="my-6 border-card-border">

                <h4 class="font-bold text-text-main mb-4 text-center">DECLARATION & ACCEPTANCE</h4>
                <div class="bg-card-bg p-5 rounded-lg border border-card-border space-y-3 text-sm">
                    <p>I, <strong>{{ $user->name }}</strong>, hereby solemnly declare that I have thoroughly read, understood, and willingly accepted all the terms and conditions stated in this document of Vedanta Placement Agency.</p>
                    <p>I confirm that all personal, academic, and professional details provided by me are true, accurate, and complete. I understand that any false or misleading information may result in immediate cancellation of my registration without any refund.</p>
                    <p>I hereby agree to pay a service/registration fee of <strong>₹ 1000 (Rupees One Thousand only)</strong> to Vedanta Placement Agency, as mutually agreed, for availing recruitment and placement assistance services.</p>
                    <p>I clearly acknowledge and accept that the aforesaid amount is non-refundable under any circumstances once paid, irrespective of selection, joining, delay, or personal decision.</p>
                    <p>I further understand that Vedanta Placement Agency functions solely as a placement facilitation and consultancy service provider and does not guarantee employment, salary structure, job continuity, or service conditions, which are solely governed by the hiring institution.</p>
                    <p>I agree to abide by all rules, policies, and professional ethics of the agency. Any breach, non-compliance, or misconduct on my part may lead to termination of services and may attract legal action, if deemed necessary.</p>
                    <p>This declaration shall be deemed to constitute a lawful and binding agreement, enforceable in accordance with applicable laws, and subject exclusively to the jurisdiction of Patna, Bihar.</p>
                    <p class="font-semibold text-accent-red mt-2">Candidates agree to resolve disputes through formal communication before taking legal recourse.</p>
                </div>
                
                <p class="mt-8 font-semibold text-accent-yellow italic border-l-2 border-accent-yellow/40 pl-4">By signing below, you acknowledge that you have read, understood, and agree to be bound by these terms.</p>
            </div>
        </div>

        {{-- Signature Section --}}
        @if($profile->is_agreement_signed)
            <div class="p-6 md:p-8 bg-green-500/5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-text-main mb-1">Agreement Digitally Signed</h3>
                        <p class="text-sm text-text-dark/60 mb-6">You have accepted the terms and conditions.</p>
                        
                        <div class="flex flex-col sm:flex-row gap-6 mb-6">
                            {{-- Digital Signature --}}
                            <div class="bg-card-bg border border-card-border rounded-xl p-5 flex-1">
                                <h4 class="text-xs font-semibold text-text-main/50 uppercase tracking-wider mb-3">
                                    {{ $profile->signature_data ? 'Your Digital Signature' : 'Agreement Status' }}
                                </h4>
                                
                                @if($profile->signature_data)
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
                                        <i class="fas fa-file-pdf text-accent-blue mr-1"></i> Agreement manually uploaded by Admin.
                                    </p>
                                    <div class="text-xs text-text-dark/50 mt-4 pt-4 border-t border-card-border">
                                        <span class="block text-text-dark/30 mb-0.5">Uploaded On</span>
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

                        <div class="mt-6">
                            <a href="{{ route('candidate.agreement.download') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent-blue/10 text-accent-blue font-medium rounded-lg hover:bg-accent-blue/20 transition-colors text-sm">
                                <i class="fas fa-file-pdf"></i> Download PDF
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
