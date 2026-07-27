<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PhonePeService
{
    private string $clientId;
    private string $clientSecret;
    private string $clientVersion;
    private string $env;
    private bool $isProd;

    public function __construct()
    {
        $this->clientId = env('PHONEPE_CLIENT_ID', env('PHONEPE_MERCHANT_ID', ''));
        $this->clientSecret = env('PHONEPE_CLIENT_SECRET', env('PHONEPE_SALT_KEY', ''));
        $this->clientVersion = env('PHONEPE_CLIENT_VERSION', '1');
        $this->env = env('PHONEPE_ENV', 'sandbox');
        $this->isProd = $this->env === 'production';
    }

    /**
     * Get OAuth access token from PhonePe (cached for 10 minutes)
     */
    public function getAccessToken(): ?string
    {
        $cacheKey = 'phonepe_access_token_' . $this->env;

        return Cache::remember($cacheKey, 600, function () {
            $tokenUrl = $this->isProd
                ? 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token'
                : 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token';

            $http = Http::asForm();
            if (!$this->isProd) {
                $http = $http->withoutVerifying();
            }

            $response = $http->post($tokenUrl, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'client_version' => $this->clientVersion,
                'grant_type' => 'client_credentials',
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['access_token'])) {
                return $data['access_token'];
            }

            Log::error('PhonePe OAuth Token Failed', [
                'status' => $response->status(),
                'response' => $data,
                'raw' => $response->body(),
            ]);

            // Clear cache so we retry next time
            Cache::forget('phonepe_access_token_' . $this->env);
            return null;
        });
    }

    /**
     * Initiate a payment using PhonePe V2 Standard Checkout
     *
     * @param string $orderId  Unique merchant order ID
     * @param int    $amount   Amount in rupees (will be converted to paise)
     * @param string $redirectUrl  URL to redirect after payment
     * @return array ['success' => bool, 'redirect_url' => string|null, 'error' => string|null, 'raw' => array]
     */
    public function initiatePay(string $orderId, int $amount, string $redirectUrl): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [
                'success' => false,
                'redirect_url' => null,
                'error' => 'Failed to get PhonePe OAuth token. Check Client ID/Secret.',
                'raw' => [],
            ];
        }

        // Force HTTPS in production
        if ($this->isProd) {
            $redirectUrl = str_replace('http://', 'https://', $redirectUrl);
        }

        $payUrl = $this->isProd
            ? 'https://api.phonepe.com/apis/pg/checkout/v2/pay'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay';

        $payload = [
            'merchantOrderId' => $orderId,
            'amount' => $amount * 100, // Convert to paise
            'expireAfter' => 1200, // 20 minutes
            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
                'message' => 'Payment for Vedanta Placement Agency',
                'merchantUrls' => [
                    'redirectUrl' => $redirectUrl,
                ],
            ],
        ];

        $http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'O-Bearer ' . $token,
        ]);

        if (!$this->isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($payUrl, $payload);
        $rData = $response->json();

        Log::info('PhonePe V2 Pay Response', [
            'orderId' => $orderId,
            'status' => $response->status(),
            'response' => $rData,
        ]);

        // V2 success: check for redirectUrl in response
        if ($response->successful() && isset($rData['redirectUrl'])) {
            return [
                'success' => true,
                'redirect_url' => $rData['redirectUrl'],
                'error' => null,
                'raw' => $rData,
            ];
        }

        $error = $rData['message'] ?? $rData['code'] ?? ('HTTP ' . $response->status() . ': ' . $response->body());

        Log::error('PhonePe V2 Pay Initiation Failed', [
            'orderId' => $orderId,
            'clientId' => $this->clientId,
            'http_status' => $response->status(),
            'response' => $rData,
            'raw_body' => $response->body(),
        ]);

        // If token expired, clear cache and retry once
        if ($response->status() === 401) {
            Cache::forget('phonepe_access_token_' . $this->env);
            Log::info('PhonePe token expired, retrying...');

            $token = $this->getAccessToken();
            if ($token) {
                $http2 = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'O-Bearer ' . $token,
                ]);
                if (!$this->isProd) {
                    $http2 = $http2->withoutVerifying();
                }
                $response2 = $http2->post($payUrl, $payload);
                $rData2 = $response2->json();

                if ($response2->successful() && isset($rData2['redirectUrl'])) {
                    return [
                        'success' => true,
                        'redirect_url' => $rData2['redirectUrl'],
                        'error' => null,
                        'raw' => $rData2,
                    ];
                }
            }
        }

        return [
            'success' => false,
            'redirect_url' => null,
            'error' => $error,
            'raw' => $rData ?? [],
        ];
    }

    /**
     * Check payment status using V2 Order Status API
     *
     * @param string $orderId  The merchantOrderId used during payment
     * @return array ['success' => bool, 'state' => string|null, 'amount' => int, 'transactionId' => string|null, 'raw' => array]
     */
    public function checkStatus(string $orderId): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [
                'success' => false,
                'state' => null,
                'amount' => 0,
                'transactionId' => null,
                'raw' => [],
            ];
        }

        $statusUrl = $this->isProd
            ? "https://api.phonepe.com/apis/pg/checkout/v2/order/{$orderId}/status"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/order/{$orderId}/status";

        $http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'O-Bearer ' . $token,
        ]);

        if (!$this->isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->get($statusUrl);
        $rData = $response->json();

        Log::info('PhonePe V2 Status Response', [
            'orderId' => $orderId,
            'status' => $response->status(),
            'response' => $rData,
        ]);

        $state = $rData['state'] ?? ($rData['data']['state'] ?? null);
        $isCompleted = $state === 'COMPLETED';
        $amountPaise = $rData['amount'] ?? ($rData['data']['amount'] ?? 0);
        $txnId = $rData['orderId'] ?? ($rData['data']['transactionId'] ?? $orderId);

        return [
            'success' => $isCompleted,
            'state' => $state,
            'amount' => $amountPaise, // in paise
            'transactionId' => $txnId,
            'raw' => $rData,
        ];
    }

    /**
     * Check if running in production mode
     */
    public function isProd(): bool
    {
        return $this->isProd;
    }
}
