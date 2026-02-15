<?php

namespace App\Services;

use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create a charge via Midtrans Core API.
     *
     * @param TicketOrder $order
     * @param string $paymentType  qris|gopay|shopeepay|bank_transfer|echannel
     * @param string|null $bank    bca|bni|bri (for bank_transfer only)
     * @return object  Midtrans charge response
     */
    public function createCoreCharge(TicketOrder $order, string $paymentType, ?string $bank = null): object
    {
        try {
            $orderId = 'TICKET-' . $order->order_number . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $order->total_price,
                ],
                'item_details' => [
                    [
                        'id' => $order->ticket_id,
                        'price' => (int) ($order->total_price / $order->quantity),
                        'quantity' => $order->quantity,
                        'name' => substr($order->ticket->name, 0, 50),
                        'category' => 'Tiket Wisata',
                    ],
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                ],
            ];

            // Add payment-type-specific params
            switch ($paymentType) {
                case 'qris':
                    $params['payment_type'] = 'qris';
                    $params['qris'] = ['acquirer' => 'gopay'];
                    break;

                case 'gopay':
                    $params['payment_type'] = 'gopay';
                    $params['gopay'] = [
                        'enable_callback' => true,
                        'callback_url' => route('tickets.payment.success', $order->order_number),
                    ];
                    break;

                case 'shopeepay':
                    $params['payment_type'] = 'shopeepay';
                    $params['shopeepay'] = [
                        'callback_url' => route('tickets.payment.success', $order->order_number),
                    ];
                    break;

                case 'bank_transfer':
                    $params['payment_type'] = 'bank_transfer';
                    $params['bank_transfer'] = ['bank' => $bank];
                    break;

                case 'echannel':
                    $params['payment_type'] = 'echannel';
                    $params['echannel'] = [
                        'bill_info1' => 'Tiket Wisata',
                        'bill_info2' => $order->order_number,
                    ];
                    break;

                default:
                    throw new \InvalidArgumentException("Unsupported payment type: {$paymentType}");
            }

            // Add expiry
            $params['custom_expiry'] = [
                'expiry_duration' => 2,
                'unit' => 'minute',
            ];

            $response = CoreApi::charge($params);

            // Extract payment info
            $paymentData = $this->extractPaymentData($response, $paymentType, $bank);

            // Update order with Midtrans data
            $order->update([
                'payment_gateway_id' => $orderId,
                'payment_method_detail' => $paymentType,
                'payment_channel' => $bank ?? $paymentType,
                'payment_info' => $paymentData,
                'expiry_time' => $response->expiry_time ?? now()->addMinutes(2),
            ]);

            Log::info('Midtrans Core API charge created', [
                'order_number' => $order->order_number,
                'payment_type' => $paymentType,
                'bank' => $bank,
                'transaction_id' => $response->transaction_id ?? null,
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Failed to create Midtrans Core API charge', [
                'order_number' => $order->order_number,
                'payment_type' => $paymentType,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Extract payment instructions from Midtrans charge response.
     */
    public function extractPaymentData(object $response, string $paymentType, ?string $bank = null): array
    {
        $data = [
            'payment_type' => $paymentType,
            'bank' => $bank,
            'transaction_id' => $response->transaction_id ?? null,
            'expiry_time' => $response->expiry_time ?? null,
            'gross_amount' => $response->gross_amount ?? null,
        ];

        switch ($paymentType) {
            case 'qris':
                // QRIS returns actions array with QR URL
                $actions = $response->actions ?? [];
                foreach ($actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $data['qr_url'] = $action->url;
                    }
                }
                // Fallback to qr_string
                if (empty($data['qr_url']) && !empty($response->qr_string)) {
                    $data['qr_string'] = $response->qr_string;
                }
                break;

            case 'gopay':
                $actions = $response->actions ?? [];
                foreach ($actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $data['qr_url'] = $action->url;
                    }
                    if ($action->name === 'deeplink-redirect') {
                        $data['deeplink'] = $action->url;
                    }
                    if ($action->name === 'get-status') {
                        $data['status_url'] = $action->url;
                    }
                }
                break;

            case 'shopeepay':
                $actions = $response->actions ?? [];
                foreach ($actions as $action) {
                    if ($action->name === 'deeplink-redirect') {
                        $data['deeplink'] = $action->url;
                    }
                }
                break;

            case 'bank_transfer':
                // VA number extraction depends on the bank
                if (!empty($response->va_numbers) && count($response->va_numbers) > 0) {
                    $data['va_number'] = $response->va_numbers[0]->va_number;
                    $data['bank'] = $response->va_numbers[0]->bank;
                }
                // Permata returns differently
                if (!empty($response->permata_va_number)) {
                    $data['va_number'] = $response->permata_va_number;
                    $data['bank'] = 'permata';
                }
                break;

            case 'echannel':
                $data['bill_key'] = $response->bill_key ?? null;
                $data['biller_code'] = $response->biller_code ?? null;
                break;
        }

        return $data;
    }

    /**
     * Get transaction status from Midtrans.
     */
    public function getTransactionStatus($orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            Log::error('Failed to get Midtrans transaction status', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Cancel a Midtrans transaction.
     */
    public function cancelTransaction($orderId)
    {
        try {
            return Transaction::cancel($orderId);
        } catch (\Exception $e) {
            Log::error('Failed to cancel Midtrans transaction', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify the SHA-512 signature key from Midtrans notification.
     */
    public function verifySignatureKey(array $data): bool
    {
        $orderId = $data['order_id'] ?? '';
        $statusCode = $data['status_code'] ?? '';
        $grossAmount = $data['gross_amount'] ?? '';
        $serverKey = config('services.midtrans.server_key');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        $receivedSignature = $data['signature_key'] ?? '';

        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Handle Midtrans payment notification (webhook).
     */
    public function handleNotification(Request $request): bool
    {
        try {
            $notificationData = $request->all();

            Log::info('Midtrans notification received', [
                'body' => $notificationData,
            ]);

            // Verify signature
            if (!$this->verifySignatureKey($notificationData)) {
                Log::warning('Invalid Midtrans signature key');
                return false;
            }

            $orderId = $notificationData['order_id'] ?? '';
            $transactionStatus = $notificationData['transaction_status'] ?? '';
            $fraudStatus = $notificationData['fraud_status'] ?? 'accept';
            $paymentType = $notificationData['payment_type'] ?? null;

            // Extract order number from order_id (format: TICKET-{orderNumber}-{timestamp} or TICKET-{orderNumber})
            if (preg_match('/^TICKET-(.+)-\d+$/', $orderId, $matches)) {
                $orderNumber = $matches[1];
            } else {
                $orderNumber = str_replace('TICKET-', '', $orderId);
            }
            $order = TicketOrder::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::warning('Order not found for Midtrans notification', ['order_number' => $orderNumber]);
                return false;
            }

            // Already paid — skip processing
            if ($order->status === 'paid') {
                Log::info('Order already marked as paid', ['order_number' => $orderNumber]);
                return true;
            }

            // Determine outcome based on transaction_status
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($transactionStatus === 'capture' && $fraudStatus !== 'accept') {
                    Log::warning('Transaction captured but fraud status is not accept', [
                        'order_number' => $orderNumber,
                        'fraud_status' => $fraudStatus,
                    ]);
                    return true;
                }

                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_method_detail' => $paymentType,
                    'payment_channel' => $notificationData['bank'] ?? $notificationData['store'] ?? $paymentType,
                ]);

                // Generate Ticket Number
                $order->generateTicketNumber();

                Log::info('Order marked as paid via Midtrans', [
                    'order_number' => $orderNumber,
                    'payment_type' => $paymentType,
                    'transaction_status' => $transactionStatus,
                ]);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update([
                    'status' => 'cancelled',
                ]);

                Log::info('Order cancelled via Midtrans', [
                    'order_number' => $orderNumber,
                    'transaction_status' => $transactionStatus,
                ]);
            } elseif ($transactionStatus === 'pending') {
                $paymentData = $this->extractPaymentData((object) $notificationData, $paymentType, $notificationData['bank'] ?? null);
                
                $order->update([
                    'payment_method_detail' => $paymentType,
                    'payment_channel' => $notificationData['bank'] ?? $notificationData['store'] ?? $paymentType,
                    'payment_info' => $paymentData,
                    'expiry_time' => $notificationData['expiry_time'] ?? null,
                ]);

                Log::info('Order payment pending - details persisted', [
                    'order_number' => $orderNumber,
                    'transaction_status' => $transactionStatus,
                    'payment_type' => $paymentType,
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to handle Midtrans notification', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return false;
        }
    }
}
