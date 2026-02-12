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
            // Decode the QR JSON data
            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || !isset($qrData['order_number'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Format QR Code tidak valid!',
                ], 400);
            }

            $orderNumber = $qrData['order_number'];

            // Find the order
            $order = TicketOrder::with('ticket.place')
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket tidak ditemukan di sistem!',
                ], 404);
            }

            // Check Payment Status
            if ($order->status !== 'paid') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket belum dibayar! Status: ' . ucfirst($order->status),
                ], 400);
            }

            // Check Visit Date
            if (!$order->visit_date->isToday()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tanggal tiket tidak sesuai! Tiket untuk: ' . $order->visit_date->format('d M Y'),
                ], 400);
            }

            // Check if Already Used
            if ($order->check_in_time !== null) {
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
