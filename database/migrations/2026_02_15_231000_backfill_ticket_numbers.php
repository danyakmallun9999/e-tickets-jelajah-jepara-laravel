<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TicketOrder;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find all paid/used orders without a ticket number
        $orders = TicketOrder::whereIn('status', ['paid', 'used'])
            ->whereNull('ticket_number')
            ->get();

        foreach ($orders as $order) {
            $ticketNumber = null;
            do {
                $random = strtoupper(Str::random(8));
                $ticketNumber = 'TIX-' . $random;
            } while (TicketOrder::where('ticket_number', $ticketNumber)->exists());

            $order->ticket_number = $ticketNumber;
            // Also update QR code to ticket number if it was Order Number
            $order->qr_code = $ticketNumber; 
            $order->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed as we are filling data
    }
};
