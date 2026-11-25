<?php

namespace App\Http\Controllers;

use App\Models\JobAlertSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobAlertSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $normalizedEmail = strtolower(trim((string) $request->input('email')));

        $validator = Validator::make(
            ['email' => $normalizedEmail],
            ['email' => 'required|email|max:255'],
            [
                'email.required' => 'Please enter your email address to subscribe.',
                'email.email' => 'Please enter a valid email address.',
            ]
        );

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first('email'),
                ], 422);
            }

            return redirect($this->previousFooterUrl())
                ->withErrors($validator, 'jobAlert')
                ->withInput();
        }

        $subscriber = JobAlertSubscriber::where('email', $normalizedEmail)->first();

        if (! $subscriber) {
            JobAlertSubscriber::create([
                'email' => $normalizedEmail,
                'is_active' => true,
                'unsubscribe_token' => JobAlertSubscriber::generateUniqueToken(),
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            $state = 'subscribed';
            $message = 'Subscribed! You will now receive free email alerts when new jobs are posted.';
        } elseif (! $subscriber->is_active) {
            $subscriber->update([
                'is_active' => true,
                'unsubscribe_token' => JobAlertSubscriber::generateUniqueToken(),
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            $state = 'reactivated';
            $message = 'Welcome back! Your free daily job updates subscription is active again.';
        } else {
            if (empty($subscriber->unsubscribe_token)) {
                $subscriber->update(['unsubscribe_token' => JobAlertSubscriber::generateUniqueToken()]);
            }

            $state = 'already_subscribed';
            $message = 'You are already subscribed! New job alerts will be sent to your email.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'state' => $state,
                'message' => $message,
            ]);
        }

        return redirect($this->previousFooterUrl())
            ->with('job_alert_success', $message);
    }

    public function unsubscribe(string $token)
    {
        $subscriber = JobAlertSubscriber::where('unsubscribe_token', $token)->first();

        if (! $subscriber) {
            return view('job_alerts.unsubscribed', [
                'found' => false,
                'subscriber' => null,
                'resubscribed' => false,
            ]);
        }

        if ($subscriber->is_active) {
            $subscriber->update([
                'is_active' => false,
                'unsubscribed_at' => now(),
            ]);
        }

        return view('job_alerts.unsubscribed', [
            'found' => true,
            'subscriber' => $subscriber,
            'resubscribed' => false,
        ]);
    }

    public function resubscribe(string $token)
    {
        $subscriber = JobAlertSubscriber::where('unsubscribe_token', $token)->first();

        if (! $subscriber) {
            return view('job_alerts.unsubscribed', [
                'found' => false,
                'subscriber' => null,
                'resubscribed' => false,
            ]);
        }

        $subscriber->update([
            'is_active' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
        ]);

        return view('job_alerts.unsubscribed', [
            'found' => true,
            'subscriber' => $subscriber,
            'resubscribed' => true,
        ]);
    }

    protected function previousFooterUrl(): string
    {
        $previous = url()->previous() ?: route('home');
        $base = strtok($previous, '#') ?: route('home');

        $appHost = parse_url(url('/'), PHP_URL_HOST);
        $prevHost = parse_url($base, PHP_URL_HOST);

        if ($prevHost && $appHost && strcasecmp($prevHost, $appHost) !== 0) {
            return route('home') . '#footer-job-alerts';
        }

        return $base . '#footer-job-alerts';
    }
}
