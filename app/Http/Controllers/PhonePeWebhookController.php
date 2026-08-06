<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\PhonePeService;
use App\Services\PaymentFulfillmentService;

class PhonePeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('PhonePe S2S Webhook Received', ['payload' => $request->all()]);

        $transactionId = null;

        if ($request->has('response')) {
            $decoded = json_decode(base64_decode($request->response), true);
            $transactionId = $decoded['data']['merchantTransactionId'] ?? $decoded['data']['merchantOrderId'] ?? null;
        } elseif ($request->has('merchantOrderId')) {
            $transactionId = $request->merchantOrderId;
        } elseif ($request->has('merchantTransactionId')) {
            $transactionId = $request->merchantTransactionId;
        }

        if (!$transactionId) {
            Log::error('PhonePe Webhook: Could not extract transaction ID', ['payload' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Invalid Payload'], 400);
        }

        $phonePe = new PhonePeService();
        $statusResult = $phonePe->checkStatus($transactionId);

        Log::info('PhonePe Webhook Status Check', ['txn' => $transactionId, 'result' => $statusResult]);

        $isSuccess = $statusResult['success'] ?? false;
        $amountPaid = ($statusResult['amount'] ?? 0) / 100;

        PaymentFulfillmentService::fulfill(
            $transactionId,
            $isSuccess,
            $amountPaid,
            $statusResult['raw'] ?? [],
            $statusResult['transactionId'] ?? null
        );

        return response()->json(['success' => true]);
    }
}
