<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;

class RegistrationController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        // Paid Amount from profile
        $registrationPaidAmount = $profile ? ($profile->paid_amount ?? 0) : 0;
        
        // Payment History
        $paymentHistory = PaymentTransaction::where('candidate_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get();
                                
        // Notifications (Using Laravel's built-in notifications if trait exists)
        $notifications = method_exists($user, 'notifications') ? $user->notifications()->latest()->take(5)->get() : collect([]);

        return view('candidate.registration.show', compact('profile', 'registrationPaidAmount', 'paymentHistory', 'notifications'));
    }
}
