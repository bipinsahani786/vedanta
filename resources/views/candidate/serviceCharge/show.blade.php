@extends('layouts.app')

@section('content')
    @include('candidate.partials.nav')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-text-main">Service Charge</h1>
                    <p class="text-sm text-text-dark/50 mt-0.5">View your service charge details and payment history.</p>
                </div>
            </div>
            @if($invoices->where('status', '!=', 'paid')->count() > 0)
                <div class="px-4 py-2 bg-accent-yellow/20 text-accent-yellow rounded-xl text-sm font-semibold border border-accent-yellow/30">
                    <i class="fas fa-exclamation-circle mr-1"></i> You have pending service charges
                </div>
            @endif
        </div>

        @forelse($invoices as $invoice)
            <div class="bg-card-bg rounded-2xl border {{ $invoice->status === 'paid' ? 'border-green-500/30' : 'border-card-border' }} overflow-hidden shadow-xl reveal reveal-delay-1 mb-8 relative">
                @if($invoice->status === 'paid')
                    <div class="absolute top-0 right-0 px-4 py-1 bg-green-500 text-white text-xs font-bold rounded-bl-xl shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Paid
                    </div>
                @endif
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        
                        {{-- Service Charge Amount --}}
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40 mb-2">Service Charge Amount</p>
                            <div class="text-2xl font-bold text-text-main">
                                ₹{{ number_format($invoice->amount ?? 0, 2) }}
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40 mb-2">Due Date</p>
                            <div class="text-lg font-semibold {{ (isset($invoice->due_date) && \Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status !== 'paid') ? 'text-red-500' : 'text-text-main' }}">
                                {{ isset($invoice->due_date) ? \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') : 'N/A' }}
                            </div>
                        </div>

                        {{-- Late Fee --}}
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40 mb-2">Late Fee</p>
                            <div class="text-lg font-semibold text-accent-yellow">
                                ₹{{ number_format($invoice->late_fee ?? 0, 2) }}
                            </div>
                        </div>

                        {{-- Pending Amount --}}
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40 mb-2">Pending Amount</p>
                            <div class="text-2xl font-bold text-accent-blue">
                                @php
                                    $pendingAmount = ($invoice->status === 'pending' || $invoice->status === 'overdue') ? ($invoice->amount + $invoice->late_fee) : 0;
                                @endphp
                                ₹{{ number_format($pendingAmount, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-card-border flex flex-wrap gap-4 items-center justify-between">
                        {{-- Invoice PDF Button --}}
                        <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 bg-secondary-bg text-text-main rounded-xl text-sm font-semibold hover:bg-card-border/50 transition-colors border border-card-border">
                            <i class="fas fa-file-pdf text-red-400"></i> Download Invoice PDF
                        </a>
                        
                        @if($invoice->status !== 'paid')
                            <form action="{{ route('candidate.serviceCharge.pay') }}" method="POST" class="inline m-0 p-0">
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                <button type="submit" class="px-5 py-2.5 bg-green-500 text-white rounded-xl text-sm font-semibold hover:bg-green-600 hover:-translate-y-0.5 transition-all shadow-lg flex items-center gap-2">
                                    <i class="fas fa-credit-card text-xs"></i> Pay ₹{{ number_format($pendingAmount, 2) }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-card-bg rounded-2xl border border-card-border p-8 text-center text-text-dark/50 mb-8 shadow-sm">
                <div class="w-16 h-16 bg-secondary-bg rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-text-main mb-1">No Service Charges</h3>
                <p class="text-sm">You do not have any service charge invoices at the moment.</p>
            </div>
        @endforelse

        {{-- Payment History --}}
        <h2 class="text-lg font-bold text-text-main mb-4 reveal reveal-delay-2">Payment History</h2>
        <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal reveal-delay-2">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-card-border bg-secondary-bg/20">
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Date</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Transaction ID</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Amount</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-card-border">
                        @if(isset($paymentHistory) && count($paymentHistory) > 0)
                            @foreach($paymentHistory as $payment)
                                <tr class="hover:bg-secondary-bg/30 transition-colors">
                                    <td class="px-6 py-4 text-text-dark/70 font-medium">
                                        {{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-text-main font-semibold">
                                        {{ $payment->transaction_id ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-text-main font-bold">
                                        ₹{{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                            <i class="fas fa-check-circle mr-1 text-[9px]"></i> Successful
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('candidate.payment.invoice', $payment->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue hover:bg-accent-blue hover:text-white transition-all shadow-sm" title="Download Invoice">
                                            <i class="fas fa-download text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 bg-card-border/30 rounded-xl flex items-center justify-center text-text-dark/20 text-xl mx-auto mb-3">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <h3 class="text-sm font-semibold text-text-main mb-1">No Payment History</h3>
                                    <p class="text-xs text-text-dark/40">You haven't made any payments yet.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection