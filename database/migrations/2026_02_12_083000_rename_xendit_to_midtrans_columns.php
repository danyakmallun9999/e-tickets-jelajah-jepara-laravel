<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            // Rename Xendit-specific columns to generic payment gateway names
            $table->renameColumn('xendit_invoice_id', 'payment_gateway_id');
            $table->renameColumn('xendit_invoice_url', 'payment_gateway_url');
            $table->renameColumn('xendit_payment_method', 'payment_method_detail');
            $table->renameColumn('xendit_payment_channel', 'payment_channel');

            // Add Midtrans Snap token column
            $table->string('snap_token')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropColumn('snap_token');

            $table->renameColumn('payment_gateway_id', 'xendit_invoice_id');
            $table->renameColumn('payment_gateway_url', 'xendit_invoice_url');
            $table->renameColumn('payment_method_detail', 'xendit_payment_method');
            $table->renameColumn('payment_channel', 'xendit_payment_channel');
        });
    }
};
