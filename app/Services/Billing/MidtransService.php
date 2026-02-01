<?php

namespace App\Services\Billing;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = config('services.midtrans.is_production', false);
        $this->baseUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    /**
     * Create a new transaction
     */
    public function createTransaction(User $user, Plan $plan): array
    {
        $orderId = 'SIP-' . $user->id . '-' . $plan->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $plan->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $plan->name,
                    'price' => (int) $plan->price,
                    'quantity' => 1,
                    'name' => 'Langganan ' . $plan->display_name . ' (1 Bulan)',
                ],
            ],
            'callbacks' => [
                'finish' => config('app.url') . '/subscription/success',
            ],
        ];

        try {
            $response = $this->request('POST', '/transactions', $params);
            
            return [
                'order_id' => $orderId,
                'snap_token' => $response['token'],
                'redirect_url' => $response['redirect_url'],
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans create transaction failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ]);
            throw $e;
        }
    }

    /**
     * Handle notification from Midtrans
     */
    public function handleNotification(array $data): ?array
    {
        // Verify signature
        $serverKey = $this->serverKey;
        $orderId = $data['order_id'];
        $statusCode = $data['status_code'];
        $grossAmount = $data['gross_amount'];
        $signatureKey = $data['signature_key'];

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans signature mismatch', [
                'order_id' => $orderId,
                'expected' => $expectedSignature,
                'received' => $signatureKey,
            ]);
            return null;
        }

        return $data;
    }

    /**
     * Check transaction status
     */
    public function checkStatus(string $orderId): ?array
    {
        try {
            return $this->request('GET', "/transactions/{$orderId}/status");
        } catch (\Exception $e) {
            Log::error('Midtrans check status failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            return null;
        }
    }

    /**
     * Make HTTP request to Midtrans API
     */
    protected function request(string $method, string $endpoint, array $params = []): array
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->serverKey . ':'),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \Exception("Midtrans API error: {$httpCode} - {$response}");
        }

        return json_decode($response, true);
    }

    /**
     * Get client key for frontend
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * Get snap URL for frontend
     */
    public function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
}
