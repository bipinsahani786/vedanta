@extends('layouts.candidate')

@section('candidate_content')
<div class="space-y-6 pb-10">

    {{-- Page Header --}}
    <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-2xl bg-accent-blue/15 border border-accent-blue/25 text-accent-blue flex items-center justify-center text-xl shadow-sm shrink-0">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Service Charge</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Track your service charge status, details and payment history.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-500/15 border border-red-500/30 text-red-300 text-xs px-4 py-3 rounded-2xl flex items-center gap-2.5 shadow-md">
            <i class="fas fa-exclamation-circle text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('success'))
        <div class="bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs px-4 py-3 rounded-2xl flex items-center gap-2.5 shadow-md">
            <i class="fas fa-check-circle text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Top Overview Metrics (Row of 4 Cards) --}}
    @php
        $hasPaid = $invoices->where('status', 'paid')->count() > 0;
        $pendingInvoice = $invoices->whereIn('status', ['pending', 'overdue'])->first();
        $hasPending = !is_null($pendingInvoice);

        if ($hasPaid) {
            $statusText = 'Paid & Cleared';
            $statusSub = 'All service charges cleared.';
            $statusIcon = 'fa-check-circle';
            $statusColor = 'emerald';
        } elseif ($hasPending) {
            $statusText = 'Pending Payment';
            $statusSub = 'Invoice due: ₹' . number_format($pendingInvoice->amount + $pendingInvoice->late_fee, 0);
            $statusIcon = 'fa-clock';
            $statusColor = 'amber';
        } else {
            $statusText = 'Not Applicable Yet';
            $statusSub = 'Will be applicable after joining.';
            $statusIcon = 'fa-check';
            $statusColor = 'emerald';
        }

        $categoryName = $profile->category->name ?? 'Teaching Staff';
        $subjectName = $profile->subject->name ?? 'All Teaching Roles';
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        {{-- Card 1: Service Charge Status --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-emerald-500/40 transition-all">
            <div class="flex items-center gap-3 mb-2.5">
                <div class="w-11 h-11 rounded-2xl bg-{{ $statusColor }}-500/15 border border-{{ $statusColor }}-500/25 text-{{ $statusColor }}-400 flex items-center justify-center text-lg shadow-sm shrink-0">
                    <i class="fas {{ $statusIcon }}"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Service Charge Status</span>
                    <div class="text-sm sm:text-base font-black text-white tracking-tight mt-0.5 truncate">{{ $statusText }}</div>
                </div>
            </div>
            <div class="text-[11px] text-slate-400 font-medium">{{ $statusSub }}</div>
        </div>

        {{-- Card 2: Applicable After --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-sky-500/40 transition-all">
            <div class="flex items-center gap-3 mb-2.5">
                <div class="w-11 h-11 rounded-2xl bg-sky-500/15 border border-sky-500/25 text-sky-400 flex items-center justify-center text-lg shadow-sm shrink-0">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Applicable After</span>
                    <div class="text-sm sm:text-base font-black text-white tracking-tight mt-0.5 truncate">After Joining</div>
                </div>
            </div>
            <div class="text-[11px] text-slate-400 font-medium">After successful joining.</div>
        </div>

        {{-- Card 3: Staff Category --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-purple-500/40 transition-all">
            <div class="flex items-center gap-3 mb-2.5">
                <div class="w-11 h-11 rounded-2xl bg-purple-500/15 border border-purple-500/25 text-purple-400 flex items-center justify-center text-lg shadow-sm shrink-0">
                    <i class="fas fa-users"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Staff Category</span>
                    <div class="text-sm sm:text-base font-black text-white tracking-tight mt-0.5 truncate">{{ $categoryName }}</div>
                </div>
            </div>
            <div class="text-[11px] text-slate-400 font-medium truncate">({{ $subjectName }})</div>
        </div>

        {{-- Card 4: Charge Type --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-amber-500/40 transition-all">
            <div class="flex items-center gap-3 mb-2.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center text-lg shadow-sm shrink-0">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Charge Type</span>
                    <div class="text-sm sm:text-base font-black text-white tracking-tight mt-0.5 truncate">Variable</div>
                </div>
            </div>
            <div class="text-[11px] text-slate-400 font-medium">As per company policy</div>
        </div>
    </div>

    {{-- Middle Section: Service Charge Details (Left) + How It Works (Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        {{-- Left: Service Charge Details Card (~58% / col-span-7) --}}
        <div class="lg:col-span-7 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between">
            <div>
                {{-- Header inside card --}}
                <div class="flex items-center gap-2.5 mb-1.5">
                    <div class="w-8 h-8 rounded-xl bg-accent-blue/15 text-accent-blue flex items-center justify-center text-xs">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="text-base font-bold text-white">Service Charge Details</h3>
                </div>
                <p class="text-xs text-slate-400 mb-6">Service charge will be applicable as per company policy after you join the job.</p>

                {{-- 3 Feature Columns / Sub-boxes --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-6">
                    {{-- Box 1: Charge Percentage --}}
                    <div class="p-4 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.05] transition-all">
                        <div class="w-9 h-9 rounded-xl bg-purple-500/15 border border-purple-500/25 text-purple-400 flex items-center justify-center text-sm font-black mb-3">
                            <i class="fas fa-percent"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Charge Percentage</span>
                        <div class="text-sm font-black text-white mt-1">As per Policy</div>
                        <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">Variable percentage based on staff category.</p>
                    </div>

                    {{-- Box 2: Payment Terms --}}
                    <div class="p-4 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.05] transition-all">
                        <div class="w-9 h-9 rounded-xl bg-sky-500/15 border border-sky-500/25 text-sky-400 flex items-center justify-center text-sm mb-3">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Payment Terms</span>
                        <div class="text-sm font-black text-white mt-1">As per Policy</div>
                        <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">Payment terms as per company policy.</p>
                    </div>

                    {{-- Box 3: Payment Method --}}
                    <div class="p-4 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.05] transition-all">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-sm mb-3">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Payment Method</span>
                        <div class="text-sm font-black text-emerald-400 mt-1">Online Payment</div>
                        <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">UPI / Net Banking / Debit / Credit Card</p>
                    </div>
                </div>

                {{-- Active Invoice Action Callout if Pending --}}
                @if($hasPending && $pendingInvoice)
                    @php
                        $gross = $pendingInvoice->amount + $pendingInvoice->late_fee;
                        $discount = (float) ($pendingInvoice->discount_amount ?? 0);
                        $netPayable = max(0, $gross - $discount);
                    @endphp
                    <div class="mb-4 p-4 rounded-xl bg-gradient-to-r from-amber-500/15 via-[#0c1e50] to-[#120f38] border border-amber-500/30 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                                    Pending Invoice
                                </span>
                                <span class="text-xs text-slate-300 font-medium">Due by {{ \Carbon\Carbon::parse($pendingInvoice->due_date)->format('d M, Y') }}</span>
                            </div>
                            <div class="text-lg font-black text-white mt-1">
                                ₹{{ number_format($netPayable, 2) }}
                                @if($discount > 0)
                                    <span class="text-xs text-emerald-400 font-semibold ml-2">(-₹{{ number_format($discount, 0) }} wallet discount)</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 w-full sm:w-auto">
                            <a href="{{ route('candidate.serviceCharge.invoicePdf', $pendingInvoice->id) }}" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15">
                                <i class="fas fa-file-pdf mr-1 text-red-400"></i> Invoice PDF
                            </a>
                            <form action="{{ route('candidate.serviceCharge.pay') }}" method="POST" class="m-0 p-0">
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $pendingInvoice->id }}">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-1.5">
                                    <i class="fas fa-credit-card text-[10px]"></i>
                                    <span>Pay ₹{{ number_format($netPayable, 0) }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Bottom Notice Strip --}}
            <div class="p-3 rounded-xl bg-[#0b1b4d]/80 border border-white/10 flex items-center gap-2.5 text-xs text-blue-200 shadow-inner">
                <i class="fas fa-info-circle text-sky-400 text-sm shrink-0"></i>
                <span>No service charges before selection or joining.</span>
            </div>
        </div>

        {{-- Right: How It Works Timeline Card (~42% / col-span-5) --}}
        <div class="lg:col-span-5 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-white mb-5">How It Works</h3>

                {{-- Vertical Timeline with Continuous Connector Line --}}
                <div class="relative pl-6 space-y-6 before:absolute before:left-3.5 before:top-3 before:bottom-3 before:w-0.5 before:bg-white/10">
                    {{-- Step 1 --}}
                    <div class="relative flex items-start gap-3.5">
                        <div class="absolute -left-6 top-0.5 w-7 h-7 rounded-full bg-emerald-500 text-white font-black text-xs flex items-center justify-center ring-4 ring-[#0a1e4a] shadow-md">
                            1
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-sm shrink-0">
                            <i class="fas fa-university"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Join the Job</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">You join the school/organization through Vedanta Placement Agency.</p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="relative flex items-start gap-3.5">
                        <div class="absolute -left-6 top-0.5 w-7 h-7 rounded-full bg-sky-500 text-white font-black text-xs flex items-center justify-center ring-4 ring-[#0a1e4a] shadow-md">
                            2
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-sky-500/15 border border-sky-500/25 text-sky-400 flex items-center justify-center text-sm shrink-0">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Service Charge Applicable</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">Service charge will be applicable after successful joining.</p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="relative flex items-start gap-3.5">
                        <div class="absolute -left-6 top-0.5 w-7 h-7 rounded-full bg-amber-500 text-slate-950 font-black text-xs flex items-center justify-center ring-4 ring-[#0a1e4a] shadow-md">
                            3
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center text-sm shrink-0">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Pay Service Charge</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">Pay as per company policy and selected plan.</p>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="relative flex items-start gap-3.5">
                        <div class="absolute -left-6 top-0.5 w-7 h-7 rounded-full bg-purple-500 text-white font-black text-xs flex items-center justify-center ring-4 ring-[#0a1e4a] shadow-md">
                            4
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-purple-500/15 border border-purple-500/25 text-purple-400 flex items-center justify-center text-sm shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Process Complete</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">Your service charge process will be completed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Lower-Middle Section: Important Points (Left) + Agreement Box (Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        {{-- Left: Important Points (~65% / col-span-7) --}}
        <div class="lg:col-span-7 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg bg-sky-500/15 text-sky-400 flex items-center justify-center text-xs">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-base font-bold text-white">Important Points</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="space-y-3">
                        <div class="flex items-start gap-2.5 text-slate-300">
                            <i class="fas fa-check-circle text-sky-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Service charge is applicable only after joining.</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-slate-300">
                            <i class="fas fa-check-circle text-sky-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Percentage and terms as per company policy.</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-slate-300">
                            <i class="fas fa-check-circle text-sky-400 text-xs mt-0.5 shrink-0"></i>
                            <span>No charges before selection or joining.</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start gap-2.5 text-slate-300">
                            <i class="fas fa-check-circle text-sky-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Payment method as per available options.</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-slate-300">
                            <i class="fas fa-check-circle text-sky-400 text-xs mt-0.5 shrink-0"></i>
                            <span>All payments are securely recorded and receipted.</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-slate-300">
                            <i class="fas fa-check-circle text-sky-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Please refer to your agreement for complete terms.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Agreement Box (~35% / col-span-5) --}}
        <div class="lg:col-span-5 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between">
            <div>
                <div class="flex items-start gap-3.5 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-xl text-sky-400 shrink-0 shadow-inner">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Agreement</h3>
                        <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">View and download your service charge agreement.</p>
                    </div>
                </div>
            </div>

            <div class="pt-3">
                <a href="{{ route('candidate.agreement.show') }}" 
                   class="w-full py-2.5 px-4 rounded-xl bg-accent-blue/15 hover:bg-accent-blue text-accent-blue hover:text-white border border-accent-blue/30 font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-sm">
                    <i class="fas fa-download text-xs"></i>
                    <span>View Agreement (PDF)</span>
                </a>
            </div>
        </div>

    </div>

    {{-- Bottom Section: Service Charge Transactions Table --}}
    <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)]">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fas fa-file-invoice text-purple-400 text-sm"></i>
                <span>Service Charge Transactions</span>
            </h3>
        </div>

        @if(count($paymentHistory) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-[11px] font-bold text-slate-400 border-b border-white/[0.08]">
                            <th class="pb-3 font-semibold">Date</th>
                            <th class="pb-3 font-semibold">Transaction ID</th>
                            <th class="pb-3 font-semibold">Applicable On</th>
                            <th class="pb-3 font-semibold">Percentage / Amount</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold">Payment Method</th>
                            <th class="pb-3 font-semibold">Invoice</th>
                            <th class="pb-3 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.06]">
                        @foreach($paymentHistory as $payment)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 text-slate-300">
                                    {{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y') }}
                                </td>
                                <td class="py-3 text-white font-mono font-semibold">
                                    {{ $payment->transaction_id ?? 'N/A' }}
                                </td>
                                <td class="py-3 text-slate-300">
                                    Placement Joining
                                </td>
                                <td class="py-3 font-black text-white">
                                    ₹{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="py-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                        Successful
                                    </span>
                                </td>
                                <td class="py-3 text-slate-400">
                                    UPI / Online
                                </td>
                                <td class="py-3">
                                    <span class="text-sky-400 font-semibold">INV-SC{{ $payment->id }}</span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('candidate.payment.invoice', $payment->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 hover:bg-accent-blue hover:text-white text-slate-300 transition-all shadow-sm" title="Download Invoice">
                                        <i class="fas fa-download text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Professional Illustrated Empty State Matching Screenshot --}}
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-white/[0.04] border border-white/10 flex items-center justify-center text-2xl text-sky-400 mb-3 shadow-inner">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h4 class="text-sm font-bold text-white">No transactions yet</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-sm">Your service charge will be applicable after you join the job.</p>
            </div>
        @endif
    </div>

</div>
@endsection