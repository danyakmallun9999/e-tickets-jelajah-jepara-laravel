<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketOrder;
use App\Services\MidtransService;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    /**
     * Display a listing of available tickets.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Place::whereHas('tickets', function($q) {
            $q->active();
        })->with(['tickets' => function($q) {
            $q->active();
        }]);

        // Filter by place (if needed, though we are now listing places)
        if ($request->filled('place_id')) {
            $query->where('id', $request->place_id);
        }

        $places = $query->get();

        return view('public.tickets.index', compact('places'));
    }

    /**
     * Display the specified ticket and booking form.
     */
    public function show(Ticket $ticket)
    {
        if (!$ticket->is_active) {
            abort(404, 'Tiket tidak tersedia');
        }

        $ticket->load('place');
        
        return view('public.tickets.show', compact('ticket'));
    }

    /**
     * Verify that the authenticated user owns the order.
     */
    private function verifyOrderOwnership(TicketOrder $order)
    {
        $user = Auth::guard('web')->user();
        if ($user && $user->email !== $order->customer_email) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }
    }

    /**
     * Process ticket booking.
     */
    public function book(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'visit_date' => 'required|date|after_or_equal:today',
            'quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        // Force customer email to match authenticated user
        $validated['customer_email'] = $user->email;

        $ticket = Ticket::findOrFail($validated['ticket_id']);

        // Check if ticket is available
        if (!$ticket->isAvailableOn($validated['visit_date'], $validated['quantity'])) {
            return back()->withErrors([
                'quantity' => 'Kuota tiket tidak mencukupi untuk tanggal yang dipilih.'
            ])->withInput();
        }

        // Calculate total price based on date (weekend/weekday)
        $pricePerTicket = $ticket->getPriceForDate($validated['visit_date']);
        $validated['total_price'] = $pricePerTicket * $validated['quantity'];
        $validated['unit_price'] = $pricePerTicket;
        $validated['status'] = 'pending';
        $validated['payment_method'] = 'midtrans';

        // Create order
        $order = TicketOrder::create($validated);

        // Create Midtrans Snap transaction
        try {
            $this->midtransService->createSnapTransaction($order);
            
            // Redirect to payment page with Snap checkout
            return redirect()->route('tickets.payment', $order->order_number);
        } catch (\Exception $e) {
            // If transaction creation fails, still show confirmation but with manual payment
            return redirect()->route('tickets.confirmation', $order->order_number)
                ->with('warning', 'Pesanan dibuat, namun terjadi kesalahan pada sistem pembayaran. Silakan hubungi admin.');
        }
    }

    /**
     * Show booking confirmation.
     */
    public function confirmation($orderNumber)
    {
        $order = TicketOrder::with('ticket.place')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->verifyOrderOwnership($order);

        return view('user.tickets.confirmation', compact('order'));
    }

    /**
     * Show my tickets page — auto-loads orders for the logged-in user.
     */
    public function myTickets()
    {
        $user = Auth::guard('web')->user();

        $orders = TicketOrder::with('ticket.place')
            ->where('customer_email', $user->email)
            ->latest()
            ->get();

        // Auto-sync pending orders with Midtrans
        foreach ($orders->where('status', 'pending') as $order) {
            if ($order->payment_gateway_id) {
                try {
                    $status = $this->midtransService->getTransactionStatus($order->payment_gateway_id);
                    $transactionStatus = $status->transaction_status ?? null;
                    $fraudStatus = $status->fraud_status ?? 'accept';

                    if ($transactionStatus === 'settlement' || 
                        ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                        $order->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                            'payment_method_detail' => $status->payment_type ?? null,
                            'payment_channel' => $status->bank ?? $status->store ?? $status->payment_type ?? null,
                        ]);
                        $order->refresh();
                    } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                        $order->update(['status' => 'cancelled']);
                        $order->refresh();
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to sync order status', [
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return view('user.tickets.my-tickets', compact('orders'));
    }

    /**
     * Retrieve tickets by email.
     */
    public function retrieveTickets(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $orders = TicketOrder::with('ticket.place')
            ->where('customer_email', $validated['email'])
            ->latest()
            ->get();

        return view('user.tickets.my-tickets', compact('orders'));
    }

    /**
     * Show ticket view (for printing/downloading).
     */
    public function downloadTicket($orderNumber)
    {
        $order = TicketOrder::with('ticket.place')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->verifyOrderOwnership($order);

        return view('user.tickets.download', compact('order'));
    }

    /**
     * Download ticket QR code as PNG.
     */
    public function downloadQrCode($orderNumber)
    {
        $order = TicketOrder::where('order_number', $orderNumber)->firstOrFail();

        $this->verifyOrderOwnership($order);

        // Generate QR Code Matrix
        $matrix = Encoder::encode(
            $order->order_number,
            ErrorCorrectionLevel::H(),
            'UTF-8'
        )->getMatrix();

        // Render using GD
        $pixelSize = 10;
        $borderSize = 4;
        $matrixWidth = $matrix->getWidth();
        $imageWidth = ($matrixWidth + ($borderSize * 2)) * $pixelSize;
        
        $image = imagecreate($imageWidth, $imageWidth);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 30, 41, 59); // Slate-800 color

        // Fill background
        imagefill($image, 0, 0, $white);

        // Draw QR code
        for ($y = 0; $y < $matrixWidth; $y++) {
            for ($x = 0; $x < $matrixWidth; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    imagefilledrectangle(
                        $image,
                        ($x + $borderSize) * $pixelSize,
                        ($y + $borderSize) * $pixelSize,
                        ($x + $borderSize + 1) * $pixelSize,
                        ($y + $borderSize + 1) * $pixelSize,
                        $black
                    );
                }
            }
        }

        // Capture output buffer
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        // Return as download
        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="ticket-' . $order->order_number . '.png"');
    }

    /**
     * Show payment page with Midtrans Snap
     */
    public function payment($orderNumber)
    {
        $order = TicketOrder::with('ticket.place')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->verifyOrderOwnership($order);

        // If already paid, redirect to confirmation
        if ($order->status === 'paid') {
            return redirect()->route('tickets.confirmation', $orderNumber)
                ->with('info', 'Pesanan ini sudah dibayar.');
        }

        // If cancelled, redirect to failed page
        if ($order->status === 'cancelled') {
            return redirect()->route('tickets.payment.failed', $orderNumber);
        }

        // If no snap token, create one
        if (!$order->snap_token) {
            try {
                $this->midtransService->createSnapTransaction($order);
                $order->refresh();
            } catch (\Exception $e) {
                return redirect()->route('tickets.confirmation', $orderNumber)
                    ->with('error', 'Gagal membuat transaksi pembayaran.');
            }
        }

        $clientKey = config('services.midtrans.client_key');
        $isProduction = config('services.midtrans.is_production');

        return view('user.tickets.payment', compact('order', 'clientKey', 'isProduction'));
    }

    /**
     * Handle successful payment redirect — verify first, then show appropriate page
     */
    public function paymentSuccess(Request $request, $orderNumber)
    {
        $order = TicketOrder::with('ticket.place')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->verifyOrderOwnership($order);

        // If already paid, show success page
        if ($order->status === 'paid') {
            return view('user.tickets.payment-success', compact('order'));
        }

        // If user comes from Snap callback, trust Snap and show success page
        $fromSnap = $request->query('from') === 'snap';
        $isPending = $request->query('pending') === '1';

        if ($fromSnap && $order->status === 'pending') {
            // Try to get latest status and payment details from Midtrans API
            $paymentType = null;
            $paymentChannel = null;
            $midtransStatus = null;
            
            if ($order->payment_gateway_id) {
                try {
                    $status = $this->midtransService->getTransactionStatus($order->payment_gateway_id);
                    $midtransStatus = $status->transaction_status ?? null;
                    $paymentType = $status->payment_type ?? null;
                    $paymentChannel = $status->bank ?? $status->store ?? $status->payment_type ?? null;
                } catch (\Exception $e) {
                    Log::warning('Could not fetch payment details from Midtrans', [
                        'order_number' => $orderNumber,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Mark as paid if: Midtrans confirms settlement/capture, OR onSuccess without pending flag
            $shouldMarkPaid = false;
            if (in_array($midtransStatus, ['settlement', 'capture'])) {
                $shouldMarkPaid = true;
            } elseif (!$isPending) {
                // Snap onSuccess fired — payment truly succeeded even if API is delayed
                $shouldMarkPaid = true;
            }

            if ($shouldMarkPaid) {
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_method_detail' => $paymentType,
                    'payment_channel' => $paymentChannel,
                ]);

                Log::info('Payment marked as paid from Snap callback', [
                    'order_number' => $orderNumber,
                    'midtrans_status' => $midtransStatus,
                ]);

                $order->refresh();
            }

            // Always show success page when coming from Snap (paid or processing)
            return view('user.tickets.payment-success', compact('order'));
        }

        // For non-Snap redirects, verify payment status with Midtrans API
        if ($order->status === 'pending' && $order->payment_gateway_id) {
            try {
                $status = $this->midtransService->getTransactionStatus($order->payment_gateway_id);
                
                $transactionStatus = $status->transaction_status ?? null;
                $fraudStatus = $status->fraud_status ?? 'accept';

                if ($transactionStatus === 'settlement' || 
                    ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                    $order->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'payment_method_detail' => $status->payment_type ?? null,
                        'payment_channel' => $status->bank ?? $status->store ?? $status->payment_type ?? null,
                    ]);

                    $order->refresh();
                    return view('user.tickets.payment-success', compact('order'));
                }

                // If denied/cancelled/expired
                if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $order->update(['status' => 'cancelled']);
                    return redirect()->route('tickets.payment.failed', $orderNumber);
                }
            } catch (\Exception $e) {
                Log::error('Failed to verify payment status', [
                    'order_number' => $orderNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // If we couldn't verify, redirect to payment page
        return redirect()->route('tickets.payment', $orderNumber)
            ->with('info', 'Status pembayaran belum dapat dipastikan. Silakan coba bayar kembali.');
    }

    /**
     * Handle payment failed callback
     */
    public function paymentFailed($orderNumber)
    {
        $order = TicketOrder::with('ticket.place')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->verifyOrderOwnership($order);

        return view('user.tickets.payment-failed', compact('order'));
    }

    /**
     * Check payment status via Midtrans API (AJAX)
     */
    public function checkStatus($orderNumber)
    {
        $order = TicketOrder::with('ticket.place')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->verifyOrderOwnership($order);

        if ($order->status === 'paid') {
            return response()->json([
                'success' => true,
                'status' => 'paid',
                'message' => 'Pembayaran sudah diterima.',
            ]);
        }

        if (!$order->payment_gateway_id) {
            return response()->json([
                'success' => false,
                'status' => $order->status,
                'message' => 'Belum ada transaksi pembayaran.',
            ]);
        }

        try {
            $status = $this->midtransService->getTransactionStatus($order->payment_gateway_id);
            $transactionStatus = $status->transaction_status ?? 'unknown';
            $fraudStatus = $status->fraud_status ?? 'accept';

            // Update order if payment settled
            if ($transactionStatus === 'settlement' || 
                ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_method_detail' => $status->payment_type ?? null,
                    'payment_channel' => $status->bank ?? $status->store ?? $status->payment_type ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'message' => 'Pembayaran berhasil dikonfirmasi!',
                    'payment_type' => $status->payment_type ?? null,
                ]);
            }

            if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update(['status' => 'cancelled']);
                return response()->json([
                    'success' => true,
                    'status' => 'cancelled',
                    'message' => 'Transaksi dibatalkan/kedaluwarsa.',
                ]);
            }

            $statusMessages = [
                'pending' => 'Menunggu pembayaran...',
                'unknown' => 'Status tidak diketahui.',
            ];

            return response()->json([
                'success' => true,
                'status' => $transactionStatus,
                'message' => $statusMessages[$transactionStatus] ?? "Status: {$transactionStatus}",
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to check payment status', [
                'order_number' => $orderNumber,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'status' => $order->status,
                'message' => 'Gagal mengecek status pembayaran.',
            ]);
        }
    }

    /**
     * Cancel a pending order
     */
    public function cancelOrder($orderNumber)
    {
        $order = TicketOrder::where('order_number', $orderNumber)->firstOrFail();

        $this->verifyOrderOwnership($order);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan dengan status pending yang dapat dibatalkan.');
        }

        // Try to cancel in Midtrans
        if ($order->payment_gateway_id) {
            try {
                $this->midtransService->cancelTransaction($order->payment_gateway_id);
            } catch (\Exception $e) {
                Log::warning('Failed to cancel Midtrans transaction (may already be cancelled)', [
                    'order_number' => $orderNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $order->update([
            'status' => 'cancelled',
            'snap_token' => null,
        ]);

        Log::info('Order cancelled by user', ['order_number' => $orderNumber]);

        return redirect()->route('tickets.my')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Retry payment — regenerate snap token for a pending/cancelled order
     */
    public function retryPayment($orderNumber)
    {
        $order = TicketOrder::with('ticket.place')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->verifyOrderOwnership($order);

        // Only allow retry for pending or cancelled orders
        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Pembayaran tidak dapat diulang untuk pesanan ini.');
        }

        // Reset to pending if cancelled
        if ($order->status === 'cancelled') {
            $order->update(['status' => 'pending']);
        }

        // Clear old snap token and regenerate
        $order->update([
            'snap_token' => null,
            'payment_gateway_id' => null,
            'payment_gateway_url' => null,
        ]);

        try {
            $this->midtransService->createSnapTransaction($order);
            $order->refresh();
        } catch (\Exception $e) {
            return redirect()->route('tickets.confirmation', $orderNumber)
                ->with('error', 'Gagal membuat ulang transaksi pembayaran. Silakan coba lagi.');
        }

        return redirect()->route('tickets.payment', $order->order_number);
    }
}
