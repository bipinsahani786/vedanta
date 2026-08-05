@extends('layouts.admin')

@section('title', 'Upgrade Anomalies')
@section('subtitle', 'Candidates who experienced the Service Charge upgrade issue.')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-800">
            <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i> Problematic Upgrade Payments Detected ({{ $anomalies->count() }})
        </h3>
        <p class="text-sm text-gray-500 mt-1">These candidates paid ₹500 which was incorrectly recorded as a Service Charge instead of upgrading their plan to Premium.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Candidate</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Current Plan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaction Amount</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($anomalies as $candidate)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $candidate->name }} <span class="text-xs text-gray-400">#{{ $candidate->id }}</span></div>
                        <div class="text-xs text-gray-500 mt-1">{{ $candidate->email }}</div>
                        <div class="text-xs text-gray-400 mt-1"><i class="fas fa-phone mr-1"></i>{{ $candidate->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold border border-blue-100 uppercase tracking-wide">
                            {{ $candidate->profile->plan_type ?? 'Unknown' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $txn = $candidate->paymentTransactions->first();
                        @endphp
                        @if($txn)
                            <div class="font-bold text-emerald-600">₹{{ number_format($txn->amount) }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5 break-all max-w-[200px]">{{ $txn->transaction_id }}</div>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.anomalies.fix', $candidate->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to upgrade this candidate to Premium and fix their transaction history?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg shadow-sm transition-all hover:-translate-y-0.5" style="background-color: #10b981; color: #ffffff; font-weight: bold; font-size: 0.875rem;">
                                <i class="fas fa-wrench"></i> Fix Profile
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 text-2xl mx-auto mb-4">
                            <i class="fas fa-check-circle text-emerald-400"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No anomalies found.</p>
                        <p class="text-gray-400 text-xs mt-1">All standard candidates' upgrades look correct!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
