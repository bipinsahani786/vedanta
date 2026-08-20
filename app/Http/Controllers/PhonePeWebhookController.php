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
        Log::info('PhonePe S2S Webhook Received', [
            'payload' => $request->all(),
            'raw_content' => $request->getContent()
        ]);

        $rawContent = $request->getContent();
        $jsonData = json_decode($rawContent, true) ?: $request->all();

        $transactionId = null;

        // 1. Check if base64 encoded 'response' is present (Standard PhonePe V1 / V2)
        if ($request->has('response')) {
            $decoded = json_decode(base64_decode($request->response), true);
            if (is_array($decoded)) {
                $transactionId = $decoded['data']['merchantOrderId'] 
                    ?? $decoded['data']['merchantTransactionId'] 
                    ?? $decoded['payload']['merchantOrderId']
                    ?? $decoded['payload']['orderId']
                    ?? $decoded['data']['orderId']
                    ?? $decoded['merchantOrderId']
                    ?? null;
            }
        }

        // 2. Check direct and nested JSON payload fields (PhonePe V2 PG_CHECKOUT)
        if (!$transactionId && is_array($jsonData)) {
            $transactionId = $jsonData['payload']['merchantOrderId']
                ?? $jsonData['payload']['orderId']
                ?? $jsonData['data']['merchantOrderId'] 
                ?? $jsonData['data']['merchantTransactionId'] 
                ?? $jsonData['data']['orderId']
                ?? $jsonData['merchantOrderId'] 
                ?? $jsonData['merchantTransactionId'] 
                ?? $jsonData['orderId'] 
                ?? null;
        }

        // 3. Check query/input fallback
        if (!$transactionId) {
            $transactionId = $request->input('merchantOrderId')
                ?? $request->input('merchantTransactionId')
                ?? $request->input('orderId');
        }

        if (!$transactionId) {
            Log::error('PhonePe Webhook: Could not extract transaction ID', [
                'payload' => $request->all(),
                'raw_content' => $rawContent
            ]);
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
