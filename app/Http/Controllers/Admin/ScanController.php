<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    /**
     * Show the scanner page.
     */
    public function index()
    {
        return view('admin.scan.index');
    }

    /**
     * Validate scanned QR code.
     */
    public function store(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        try {
            // Flexible QR Parsing: Handle both JSON and Plain Text
            $inputData = $request->qr_data;
            \Illuminate\Support\Facades\Log::info('QR Scan Input:', ['data' => $inputData]); // DEBUG LOG

            $orderNumber = null;

            // 1. Try to decode as JSON
            $qrJson = json_decode($inputData, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($qrJson) && isset($qrJson['order_number'])) {
                $orderNumber = $qrJson['order_number'];
            } else {
                // 2. Assume Plain Text (Order Number direct)
                $orderNumber = trim($inputData, '"\' ');
            }
            
            \Illuminate\Support\Facades\Log::info('Parsed Order Number:', ['order_number' => $orderNumber]); // DEBUG LOG

            if (empty($orderNumber)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Format QR tidak dikenali!',
                ], 400);
            }

            // Find the order
            $order = TicketOrder::with('ticket.place')
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Not Found', ['order_number' => $orderNumber]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket tidak ditemukan di sistem!',
                ], 404);
            }

            // Check Payment Status
            if ($order->status !== 'paid') {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Unpaid', ['status' => $order->status]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket belum dibayar! Status: ' . ucfirst($order->status),
                ], 400);
            }

            // Check Visit Date - Allow checking D-1 or D+1 for testing flexibilty if needed, but strict for now
            if (!$order->visit_date->isToday()) {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Wrong Date', ['visit_date' => $order->visit_date]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tanggal tiket tidak sesuai! Tiket untuk: ' . $order->visit_date->format('d M Y'),
                ], 400);
            }

            // Check if Already Used
            if ($order->check_in_time !== null) {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Already Used', ['check_in_time' => $order->check_in_time]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket SUDAH DIGUNAKAN pada ' . $order->check_in_time->format('H:i'),
                    'detail' => $order,
                ], 400);
            }

            // Mark as Used
            $order->update([
                'check_in_time' => now(),
                // We keep status as 'paid' or ideally change to 'used'. 
                // Let's stick to 'check_in_time' as the indicator of usage to preserve payment history status context if needed.
                // Or update status to 'used' if that's the convention. The plan said "update status to used".
                'status' => 'used',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Tiket Valid! Silakan Masuk.',
                'data' => [
                    'customer_name' => $order->customer_name,
                    'ticket_name' => $order->ticket->name,
                    'quantity' => $order->quantity,
                    'place_name' => $order->ticket->place->name,
                    'order_number' => $order->order_number,
                    'check_in_time' => $order->check_in_time->format('H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }
}
