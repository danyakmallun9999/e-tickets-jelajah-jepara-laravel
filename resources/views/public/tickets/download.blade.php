<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Tiket #{{ $order->order_number }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .ticket-card { box-shadow: none; border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden ticket-card">
        <!-- Header -->
        <div class="bg-slate-900 text-white p-6 text-center relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-xl font-bold tracking-tight mb-1">E-TIKET WISATA</h1>
                <p class="text-slate-400 text-xs uppercase tracking-widest">Tunjukan E-Tiket ini saat masuk</p>
            </div>
            <!-- Decorative circles -->
            <div class="absolute top-0 left-0 w-32 h-32 bg-white/5 rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-full translate-x-16 translate-y-16"></div>
        </div>

        <!-- QR Code Area -->
        <div class="bg-white p-8 flex flex-col items-center border-b border-dashed border-gray-200 relative">
            <!-- Cutout Circles -->
            <div class="absolute top-[-12px] left-[-12px] w-6 h-6 bg-gray-100 rounded-full"></div>
            <div class="absolute top-[-12px] right-[-12px] w-6 h-6 bg-gray-100 rounded-full"></div>

            <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-sm mb-4">
                <div id="qrcode"></div>
            </div>
            <p class="text-xs text-gray-400 font-mono">{{ $order->order_number }}</p>
            <span class="mt-3 px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full border border-green-100">
                {{ $order->status_label }}
            </span>
        </div>

        <!-- Ticket Details -->
        <!-- Ticket Details -->
        <div class="p-6">
            <!-- Grid Layout -->
            <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-6">
                <!-- Destinasi -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-5 h-5 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Destinasi</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800 pl-7">{{ $order->ticket->place->name }}</p>
                </div>

                <!-- Tanggal -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-5 h-5 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Tanggal</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800 pl-7">{{ $order->visit_date->translatedFormat('d M Y') }}</p>
                </div>

                <!-- Tipe Tiket -->
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-5 h-5 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Detail Tiket</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800 pl-7">{{ $order->ticket->name }} <span class="font-normal text-slate-500">({{ ucfirst($order->ticket->type) }})</span></p>
                </div>

                <!-- Harga per Tiket -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Harga Satuan</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800 pl-7">Rp {{ number_format($order->unit_price, 0, ',', '.') }}</p>
                </div>

                <!-- Jumlah -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-5 h-5 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Jml Pengunjung</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800 pl-7">{{ $order->quantity }} Orang</p>
                </div>
            </div>

            <!-- Total Banner -->
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex justify-between items-center mb-6">
                <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Total Pembayaran</p>
                    <p class="text-lg font-black text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <div class="h-8 w-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="pt-4 border-t border-dashed border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs ring-2 ring-white">
                        {{ substr($order->customer_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 leading-tight mb-0.5">Pemesan</p>
                        <p class="text-xs font-bold text-slate-700 leading-tight">{{ $order->customer_name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-3 no-print">
            <a href="{{ route('tickets.download-qr', $order->order_number) }}" class="flex-1 bg-white border border-gray-300 text-gray-700 font-semibold py-3 rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Save QR
            </a>
            <button onclick="downloadTicketImage()" class="flex-1 bg-slate-900 text-white font-semibold py-3 rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download
            </button>
        </div>
    </div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $order->order_number }}",
            width: 150,
            height: 150,
            colorDark : "#1e293b",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        function downloadTicketImage() {
            const ticketCard = document.querySelector('.ticket-card');
            
            // Hide actions before capture
            const actions = ticketCard.querySelector('.no-print');
            actions.style.display = 'none';

            html2canvas(ticketCard, {
                scale: 2, // Better resolution
                backgroundColor: null,
            }).then(canvas => {
                // Restore actions
                actions.style.display = 'flex';

                // Download
                const link = document.createElement('a');
                link.download = 'E-Tiket-{{ $order->order_number }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>
</html>

