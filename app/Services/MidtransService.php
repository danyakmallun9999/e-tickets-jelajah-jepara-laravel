<?php

namespace App\Services;

use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

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
     * Create Midtrans Snap transaction for ticket order.
     *
     * @return object { token, redirect_url }
     */
    public function createSnapTransaction(TicketOrder $order)
    {
        try {
            $orderId = 'TICKET-' . $order->order_number;

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
                        'name' => substr($order->ticket->name, 0, 50), // Midtrans max 50 chars
                        'category' => 'Tiket Wisata',
                    ],
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                ],
                'callbacks' => [
                    'finish' => route('tickets.payment.success', $order->order_number) . '?from=snap',
                ],
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit' => 'hours',
                    'duration' => 24,
                ],
            ];

            $snapResponse = Snap::createTransaction($params);

            // Update order with Midtrans data
            $order->update([
                'snap_token' => $snapResponse->token,
                'payment_gateway_id' => $orderId,
                'payment_gateway_url' => $snapResponse->redirect_url,
            ]);

            Log::info('Midtrans Snap transaction created', [
                'order_number' => $order->order_number,
                'snap_token' => $snapResponse->token,
            ]);

            return $snapResponse;
        } catch (\Exception $e) {
            Log::error('Failed to create Midtrans Snap transaction', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
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

            // Extract order number from order_id (remove 'TICKET-' prefix)
            $orderNumber = str_replace('TICKET-', '', $orderId);
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
                // For 'capture': only accept if fraud_status is 'accept'
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
                Log::info('Order payment pending', [
                    'order_number' => $orderNumber,
                    'transaction_status' => $transactionStatus,
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
