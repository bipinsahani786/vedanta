<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\PaymentTransaction;
use App\Models\ServiceChargeInvoice;

class SystemAnomalyController extends Controller
{
    public function index()
    {
        $anomalies = User::where('role', 'candidate')
            ->whereHas('profile', function($q) {
                $q->where('plan_type', 'standard');
            })
            ->whereHas('paymentTransactions', function($q) {
                $q->whereIn('type', ['service_charge', 'placement_fee'])
                  ->where('amount', 500)
                  ->where('status', 'success');
            })
            ->whereDoesntHave('applications', function($q) {
                $q->where('status', 'hired');
            })
            ->with(['profile', 'paymentTransactions' => function($q) {
                $q->whereIn('type', ['service_charge', 'placement_fee'])
                  ->where('amount', 500)
                  ->where('status', 'success')
                  ->latest();
            }])
            ->get();

        return view('admin.anomalies.index', compact('anomalies'));
    }

    public function fix($id)
    {
        $candidate = User::findOrFail($id);
        $profile = $candidate->profile;

        if ($profile && $profile->plan_type === 'standard') {
            // Upgrade the plan to Premium
            $profile->update([
                'plan_type' => 'premium',
                'total_allowed_applications' => 3, 
                'paid_amount' => $profile->paid_amount + 500,
                'pending_amount' => 0, 
                'is_fee_paid' => true,
                'plan_started_at' => now()
            ]);
            
            // Fix the transaction: Change service_charge to registration_fee
            PaymentTransaction::where('candidate_id', $id)
                ->whereIn('type', ['service_charge', 'placement_fee'])
                ->where('amount', 500)
                ->update(['type' => 'registration_fee']);
                
            // Delete the mistakenly paid Service Charge invoice
            ServiceChargeInvoice::where('candidate_id', $id)
                ->where('status', 'paid')
                ->where('amount', 500)
                ->delete();

            // Send DB Notification
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\PlanUpgraded',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $candidate->id,
                'data' => json_encode([
                    'title' => 'Plan Upgraded to Premium',
                    'message' => 'Your payment was successful and your plan has been manually upgraded to Premium.',
                    'url' => '/candidate/dashboard'
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send Email Notification
            $emailBody = "
                <h3>Congratulations {$candidate->name}!</h3>
                <p>Your recent payment issue has been resolved.</p>
                <p>Your plan is now successfully <strong>upgraded to the Premium Plan</strong>.</p>
                <p>You now have access to priority processing and more applications.</p>
                <p>Log in to your dashboard to see your new plan details.</p>
                <br>
                <p>Regards,<br>Vedanta Team</p>
            ";

            \Illuminate\Support\Facades\Mail::to($candidate->email)->send(
                new \App\Mail\DynamicTemplateMail('Plan Upgraded to Premium', $emailBody)
            );
                
            return back()->with('success', "Successfully fixed and upgraded candidate: {$candidate->name}");
        }

        return back()->with('error', "Candidate is already Premium or could not be found.");
    }
}
