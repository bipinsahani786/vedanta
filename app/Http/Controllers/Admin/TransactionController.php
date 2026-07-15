<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentTransaction::with('candidate');

        if ($search = $request->input('search')) {
            $query->whereHas('candidate', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('transaction_id', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function approve($id)
    {
        $transaction = PaymentTransaction::findOrFail($id);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be approved.');
        }

        $transaction->update([
            'status' => 'success'
        ]);

        $user = $transaction->candidate;
        if ($user) {
            $profile = $user->profile;
            $amountPaid = $transaction->amount;
            
            if ($profile->plan_type === 'standard' && $amountPaid == 500) {
                $profile->update([
                    'initial_fee_paid' => true,
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'payment_id' => $transaction->transaction_id,
                    'registration_completed_at' => now(),
                ]);
            } else {
                $profile->update([
                    'initial_fee_paid' => true,
                    'is_fee_paid' => true,
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'pending_amount' => 0,
                    'payment_id' => $transaction->transaction_id,
                    'registration_completed_at' => now()
                ]);
            }
        }

        return back()->with('success', 'Offline payment approved and candidate profile activated successfully.');
    }
}
