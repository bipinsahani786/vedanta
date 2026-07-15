<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class RegistrationWizardController extends Controller
{
    private $merchantId;
    private $saltKey;
    private $saltIndex;
    private $env;

    public function __construct()
    {
        $this->merchantId = env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT86');
        $this->saltKey = env('PHONEPE_SALT_KEY', '96434309-7796-489d-8924-ab56988a6076');
        $this->saltIndex = env('PHONEPE_SALT_INDEX', '1');
        $this->env = env('PHONEPE_ENV', 'sandbox');
    }

    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        if ($profile->initial_fee_paid || $profile->is_fee_paid) {
            return redirect()->route('candidate.dashboard');
        }

        // Load relationships if necessary
        $profile->load(['category', 'subject', 'highestQualification', 'preferredLocation']);
        
        $categories = \App\Models\Category::all();
        $subjects = \App\Models\Subject::all();
        $qualifications = \App\Models\Qualification::all();
        $locations = \App\Models\Location::all();

        return view('candidate.wizard', compact('user', 'profile', 'categories', 'subjects', 'qualifications', 'locations'));
    }

    public function saveStep1(Request $request)
    {
        $request->validate([
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'highest_qualification_id' => 'required|exists:qualifications,id',
            'preferred_location_id' => 'required|exists:locations,id',
            'experience_years' => 'required|integer|min:0',
            'current_salary' => 'nullable|string',
            'expected_salary' => 'nullable|string',
            'current_school' => 'nullable|string',
            'english_fluency' => 'nullable|in:beginner,intermediate,fluent',
            'residential_preference' => 'nullable|in:residential,day,both',
            'availability_to_join' => 'nullable|string',
            'resume' => 'required|mimes:pdf,doc,docx|max:2048',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'salary_slip' => 'nullable|mimes:pdf,doc,docx,jpg,png,jpeg|max:2048',
            'offer_letter' => 'nullable|mimes:pdf,doc,docx,jpg,png,jpeg|max:2048',
        ]);

        $profile = auth()->user()->profile;

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $profile->resume_path = $path;
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $profile->profile_photo_path = $path;
        }

        if ($request->hasFile('salary_slip')) {
            $path = $request->file('salary_slip')->store('salary_slips', 'public');
            $profile->salary_slip_path = $path;
        }

        if ($request->hasFile('offer_letter')) {
            $path = $request->file('offer_letter')->store('offer_letters', 'public');
            $profile->offer_letter_path = $path;
        }

        $profile->update([
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'subject_id' => $request->subject_id,
            'highest_qualification_id' => $request->highest_qualification_id,
            'preferred_location_id' => $request->preferred_location_id,
            'experience_years' => $request->experience_years,
            'current_salary' => $request->current_salary,
            'expected_salary' => $request->expected_salary,
            'current_school' => $request->current_school,
            'english_fluency' => $request->english_fluency,
            'residential_preference' => $request->residential_preference,
            'availability_to_join' => $request->availability_to_join,
            'is_profile_complete' => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function saveStep2(Request $request)
    {
        $request->validate([
            'agreed' => 'required|boolean|accepted',
        ]);

        $profile = auth()->user()->profile;

        $profile->update([
            'is_terms_agreed' => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function saveStep3(Request $request)
    {
        $request->validate([
            'signature_type' => 'required|in:draw,upload,type',
            'signature_data' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'live_photo' => 'required|string', // Base64 expected
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        $signatureData = $request->signature_data;

        if ($request->signature_type === 'upload' && $request->hasFile('signature_file')) {
            $path = $request->file('signature_file')->store('signatures', 'public');
            $signatureData = $path;
        }

        $livePhotoPath = null;
        if ($request->live_photo) {
            $image_parts = explode(";base64,", $request->live_photo);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'live_photo_' . $user->id . '_' . time() . '.jpg';
                $filePath = 'live_photos/' . $fileName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                $livePhotoPath = $filePath;
            }
        }

        $profile->update([
            'is_agreement_signed' => true,
            'signature_type' => $request->signature_type,
            'signature_data' => $signatureData,
            'signature_date_time' => now(),
            'signature_device_info' => $request->header('User-Agent'),
            'signature_ip_address' => $request->ip(),
            'live_photo_path' => $livePhotoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['success' => true]);
    }

    public function initiatePayment(Request $request)
    {
        $request->validate([
            'plan_type' => 'required|in:standard,premium',
            'payment_method' => 'required|in:online,upi,cash'
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        $profile->update([
            'plan_type' => $request->plan_type
        ]);

        $amount = $request->plan_type === 'standard' ? 500 : 1000;
        $transactionId = 'TXN_' . $user->id . '_' . time();

        if ($request->payment_method !== 'online') {
            \App\Models\PaymentTransaction::create([
                'candidate_id' => $user->id,
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'type' => 'registration_fee',
                'status' => 'pending',
                'gateway_response' => [
                    'payment_method' => $request->payment_method,
                    'notes' => 'Manual offline request submitted by candidate.'
                ]
            ]);

            return response()->json([
                'success' => true,
                'is_offline' => true,
                'message' => 'Payment request submitted successfully. It will be activated after admin verification.'
            ]);
        }

        $isProd = $this->env === 'production';

        $payload = [
            'merchantId' => $this->merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID_' . $user->id,
            'amount' => $amount * 100, // Amount in paise
            'redirectUrl' => route('candidate.wizard.callback'),
            'redirectMode' => 'REDIRECT',
            'callbackUrl' => $isProd ? route('candidate.wizard.callback') : 'https://webhook.site/phonepe-dummy-callback',
            'mobileNumber' => $user->phone ?? '9999999999',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $encode = base64_encode(json_encode($payload));
        $string = $encode . '/pg/v1/pay' . $this->saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $this->saltIndex;

        $url = $isProd 
            ? 'https://api.phonepe.com/apis/hermes/pg/v1/pay'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay';

        $http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $this->merchantId
        ]);

        if (!$isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($url, [
            'request' => $encode
        ]);

        $rData = $response->json();

        if (isset($rData['success']) && $rData['success'] === true) {
            session(['last_txn_id' => $transactionId]);
            return response()->json([
                'success' => true,
                'redirect_url' => $rData['data']['instrumentResponse']['redirectInfo']['url']
            ]);
        }

        return response()->json(['success' => false, 'message' => $rData['message'] ?? 'Failed to initiate payment.']);
    }

    public function callback(Request $request)
    {
        $code = $request->code;
        $transactionId = $request->transactionId ?? session('last_txn_id');

        // Verify status with PhonePe
        $string = "/pg/v1/status/{$this->merchantId}/{$transactionId}" . $this->saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $this->saltIndex;

        $isProd = $this->env === 'production';
        $url = $isProd 
            ? "https://api.phonepe.com/apis/hermes/pg/v1/status/{$this->merchantId}/{$transactionId}"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/{$this->merchantId}/{$transactionId}";

        $http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $this->merchantId
        ]);

        if (!$isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->get($url);

        $rData = $response->json();
        
        \Illuminate\Support\Facades\Log::info('PhonePe Callback Status', ['response' => $rData, 'txn' => $transactionId]);

        $user = auth()->user();

        if ($user) {
            \App\Models\PaymentTransaction::create([
                'candidate_id' => $user->id,
                'amount' => isset($rData['data']['amount']) ? $rData['data']['amount'] / 100 : 0,
                'transaction_id' => $transactionId,
                'type' => 'registration_fee',
                'status' => (isset($rData['success']) && $rData['success'] === true && $rData['data']['state'] === 'COMPLETED') ? 'success' : 'failed',
                'gateway_response' => $rData
            ]);
        }

        if (isset($rData['success']) && $rData['success'] === true && $rData['data']['state'] === 'COMPLETED') {
            $profile = $user->profile;
            
            $amountPaid = $rData['data']['amount'] / 100;

            if ($profile->plan_type === 'standard' && $amountPaid == 500) {
                // Initial payment for standard
                $profile->update([
                    'initial_fee_paid' => true,
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'payment_id' => $rData['data']['transactionId'],
                    'registration_completed_at' => now(),
                ]);
            } else {
                // Full payment (Premium or full standard)
                $profile->update([
                    'initial_fee_paid' => true,
                    'is_fee_paid' => true,
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'pending_amount' => 0,
                    'payment_id' => $rData['data']['transactionId'],
                    'registration_completed_at' => now()
                ]);
            }

            return redirect()->route('candidate.dashboard')->with('success', 'Payment successful! Your profile is now active.');
        }

        return redirect()->route('candidate.dashboard')->with('error', 'Payment failed or cancelled.');
    }
}
