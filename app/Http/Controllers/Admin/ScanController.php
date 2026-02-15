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
            // 2. Assume Plain Text (Ticket Number direct)
                $ticketNumber = trim($inputData, '"\' ');
            }
            
            \Illuminate\Support\Facades\Log::info('Parsed Ticket Number:', ['ticket_number' => $ticketNumber]); // DEBUG LOG

            if (empty($ticketNumber)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Format QR tidak dikenali!',
                    'data' => ['ticket_number' => $inputData] // Return raw input
                ], 400);
            }

            // Find the order by TICKET NUMBER
            $order = TicketOrder::with('ticket.place')
                ->where('ticket_number', $ticketNumber)
                ->first();

            if (!$order) {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Not Found', ['ticket_number' => $ticketNumber]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket tidak ditemukan di sistem!',
                    'data' => ['ticket_number' => $ticketNumber]
                ], 404);
            }

            // Prepare common data for error responses involving valid orders
            $orderData = [
                'customer_name' => $order->customer_name,
                'ticket_name' => $order->ticket->name,
                'quantity' => $order->quantity,
                'place_name' => $order->ticket->place->name,
                'order_number' => $order->order_number,
                'check_in_time' => $order->check_in_time ? $order->check_in_time->format('H:i:s') : null,
                'visit_date' => $order->visit_date->format('d M Y'),
                'status' => $order->status
            ];

            // Check Payment Status
            if ($order->status !== 'paid') {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Unpaid', ['status' => $order->status]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket belum dibayar! Status: ' . ucfirst($order->status),
                    'data' => $orderData
                ], 400);
            }

            // Check Visit Date - Allow checking D-1 or D+1 for testing flexibilty if needed, but strict for now
            if (!$order->visit_date->isToday()) {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Wrong Date', ['visit_date' => $order->visit_date]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tanggal tiket tidak sesuai! Tiket untuk: ' . $order->visit_date->format('d M Y'),
                    'data' => $orderData
                ], 400);
            }

            // Check if Already Used
            if ($order->check_in_time !== null) {
                \Illuminate\Support\Facades\Log::warning('Scan Failed: Already Used', ['check_in_time' => $order->check_in_time]); // DEBUG LOG
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tiket SUDAH DIGUNAKAN pada ' . $order->check_in_time->format('H:i'),
                    'detail' => $order, // Keep for backward compatibility if needed, but data is preferred
                    'data' => $orderData
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
                'data' => ['order_number' => $request->qr_data ?? 'Unknown']
            ], 500);
        }
    }
}
