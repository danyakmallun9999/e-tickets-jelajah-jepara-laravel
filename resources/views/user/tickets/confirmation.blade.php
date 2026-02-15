<x-public-layout :hideFooter="true">
    @push('styles')
        <!-- Fonts: Poppins for Headings, Inter for UI text -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            .font-inter { font-family: 'Inter', sans-serif; }
            h1, h2, h3, h4, .font-serif { font-family: 'Poppins', sans-serif; }
            
            @media print {
                .no-print { display: none !important; }
                body { background: white; -webkit-print-color-adjust: exact; }
                .ticket-container { box-shadow: none; margin: 0; }
                nav, footer, .pt-20 { padding-top: 0 !important; }
            }
            
            /* Custom Pattern for White/Blue Theme */
            .bg-luxury {
                background-color: #eff6ff; /* blue-50 */
                background-image: radial-gradient(#dbeafe 1px, transparent 1px);
                background-size: 24px 24px;
            }
        </style>
    @endpush
    <div class="bg-gray-50 dark:bg-background-dark min-h-screen -mt-20 pt-32 pb-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex text-xs md:text-sm text-gray-400 mb-6 space-x-2">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('tickets.my') }}" class="hover:text-primary transition-colors">Tiket Saya</a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $order->status == 'pending' ? 'Detail Pesanan' : 'Konfirmasi' }}</span>
            </nav>

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 md:p-8 text-center">
                @if($order->status == 'pending')
                    <!-- Pending: Clock Icon -->
                    <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-clock text-yellow-500 dark:text-yellow-400 text-4xl"></i>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-3">Menunggu Pembayaran</h1>
                    <p class="text-slate-500 dark:text-slate-400 mb-8">Selesaikan pembayaran untuk mengaktifkan tiket Anda</p>
                @else
                    <!-- Paid: Animated Success Icon -->
                    <div class="success-checkmark mx-auto mb-6">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="icon-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                    
                    <style>
                        .success-checkmark {
                            width: 80px;
                            height: 80px;
                        }
                        .check-icon {
                            width: 80px;
                            height: 80px;
                            position: relative;
                            border-radius: 50%;
                            box-sizing: content-box;
                            border: 4px solid #22c55e;
                        }
                        .check-icon::before {
                            top: 3px;
                            left: -2px;
                            width: 30px;
                            transform-origin: 100% 50%;
                            border-radius: 100px 0 0 100px;
                        }
                        .check-icon::after {
                            top: 0;
                            left: 30px;
                            width: 60px;
                            transform-origin: 0 50%;
                            border-radius: 0 100px 100px 0;
                            animation: rotate-circle 4.25s ease-in;
                        }
                        .check-icon::before, .check-icon::after {
                            content: '';
                            height: 100px;
                            position: absolute;
                            background: #fff;
                            transform: rotate(-45deg);
                        }
                        .dark .check-icon::before, .dark .check-icon::after {
                            background: #1e293b;
                        }
                        .icon-line {
                            height: 5px;
                            background-color: #22c55e;
                            display: block;
                            border-radius: 2px;
                            position: absolute;
                            z-index: 10;
                        }
                        .icon-line.line-tip {
                            top: 46px;
                            left: 14px;
                            width: 25px;
                            transform: rotate(45deg);
                            animation: icon-line-tip 0.75s;
                        }
                        .icon-line.line-long {
                            top: 38px;
                            right: 8px;
                            width: 47px;
                            transform: rotate(-45deg);
                            animation: icon-line-long 0.75s;
                        }
                        .icon-circle {
                            top: -4px;
                            left: -4px;
                            z-index: 10;
                            width: 80px;
                            height: 80px;
                            border-radius: 50%;
                            position: absolute;
                            box-sizing: content-box;
                            border: 4px solid rgba(34, 197, 94, 0.5);
                            animation: icon-circle-pulse 1s ease-out;
                        }
                        .icon-fix {
                            top: 8px;
                            width: 5px;
                            left: 26px;
                            z-index: 1;
                            height: 85px;
                            position: absolute;
                            transform: rotate(-45deg);
                            background-color: #fff;
                        }
                        .dark .icon-fix {
                            background-color: #1e293b;
                        }
                        @keyframes rotate-circle {
                            0% { transform: rotate(-45deg); }
                            5% { transform: rotate(-45deg); }
                            12% { transform: rotate(-405deg); }
                            100% { transform: rotate(-405deg); }
                        }
                        @keyframes icon-line-tip {
                            0% { width: 0; left: 1px; top: 19px; }
                            54% { width: 0; left: 1px; top: 19px; }
                            70% { width: 50px; left: -8px; top: 37px; }
                            84% { width: 17px; left: 21px; top: 48px; }
                            100% { width: 25px; left: 14px; top: 46px; }
                        }
                        @keyframes icon-line-long {
                            0% { width: 0; right: 46px; top: 54px; }
                            65% { width: 0; right: 46px; top: 54px; }
                            84% { width: 55px; right: 0px; top: 35px; }
                            100% { width: 47px; right: 8px; top: 38px; }
                        }
                        @keyframes icon-circle-pulse {
                            0% { transform: scale(0.8); opacity: 0; }
                            50% { transform: scale(1.2); opacity: 0.5; }
                            100% { transform: scale(1); opacity: 1; }
                        }
                    </style>

                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-3">Pesanan Berhasil!</h1>
                    <p class="text-slate-500 dark:text-slate-400 mb-8">Terima kasih telah memesan tiket wisata</p>
                @endif

                <!-- Order Info -->
                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-2xl p-5 mb-6 text-left">
                    <!-- Status Badge at Top -->
                    <div class="text-center mb-4 pb-4 border-b border-slate-200 dark:border-slate-600">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold rounded-xl
                            {{ $order->status == 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : '' }}
                            {{ $order->status == 'paid' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : '' }}">
                            @if($order->status == 'pending')
                                <i class="fa-solid fa-clock"></i>
                            @else
                                <i class="fa-solid fa-check-circle"></i>
                            @endif
                            {{ $order->status_label }}
                        </span>
                    </div>
                    
                    <!-- Order Number -->
                    <div class="space-y-3 mb-4 pb-4 border-b border-slate-200 dark:border-slate-600">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">No. Pesanan</p>
                            <p class="font-mono text-sm text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-600/50 px-3 py-2 rounded-lg">{{ $order->order_number }}</p>
                        </div>
                    </div>
                    
                    <!-- Ticket Info Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-4 pb-4 border-b border-slate-200 dark:border-slate-600">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Tiket</p>
                            <p class="font-semibold text-sm text-slate-900 dark:text-white">{{ $order->ticket->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Destinasi</p>
                            <p class="font-semibold text-sm text-slate-900 dark:text-white">{{ $order->ticket->place->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Tanggal Kunjungan</p>
                            <p class="font-semibold text-sm text-slate-900 dark:text-white">{{ $order->visit_date->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Jumlah</p>
                            <p class="font-semibold text-sm text-slate-900 dark:text-white">{{ $order->quantity }} tiket</p>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Pembayaran</span>
                        <span class="text-xl font-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-2xl p-5 mb-6 text-left">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-user text-primary"></i> Informasi Pemesan
                    </h3>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-600/50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-user text-xs text-slate-400"></i>
                            </span>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $order->customer_name }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-600/50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope text-xs text-slate-400"></i>
                            </span>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $order->customer_email }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-600/50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone text-xs text-slate-400"></i>
                            </span>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $order->customer_phone }}</span>
                        </div>
                    </div>
                </div>

                @if(in_array($order->status, ['paid', 'used']))
                    <!-- QR Code Section (only for paid orders) -->
                    <div class="bg-gradient-to-br from-primary/5 to-indigo-500/5 border border-primary/10 rounded-2xl p-6 mb-6">
                        <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-qrcode text-primary"></i> QR Code Tiket
                        </h3>
                        <div id="qrcode" class="flex justify-center mb-3 mx-auto" style="width: 200px; height: 200px; overflow: hidden;"></div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Tunjukkan QR code ini saat berkunjung</p>
                        <div class="mt-4">
                            <button onclick="downloadQR()" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2 transition-all">
                                <i class="fa-solid fa-download"></i> Download QR
                            </button>
                        </div>
                    </div>

                    <!-- Next Steps Info (only for paid orders) -->
                    <div class="bg-primary/5 border border-primary/10 rounded-2xl p-5 mb-8 text-left">
                        <div class="flex items-start">
                            <i class="fa-solid fa-info-circle text-primary mt-0.5 mr-3"></i>
                            <div class="text-sm text-slate-700 dark:text-slate-300">
                                <p class="font-bold mb-2 text-slate-900 dark:text-white">Langkah Selanjutnya</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Konfirmasi pesanan dikirim ke <strong>{{ $order->customer_email }}</strong></li>
                                    <li>Simpan QR code atau download tiket sebagai bukti</li>
                                    <li>Tunjukkan tiket saat berkunjung ke {{ $order->ticket->place->name }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @elseif($order->status == 'pending')
                    <!-- Payment Instructions (only for pending orders) -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl p-5 mb-8 text-left">
                        <div class="flex items-start">
                            <i class="fa-solid fa-wallet text-yellow-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-slate-700 dark:text-slate-300">
                                <p class="font-bold mb-2 text-slate-900 dark:text-white">Instruksi Pembayaran</p>
                                <p class="mb-2">Metode: <strong>{{ ucfirst($order->payment_method) }}</strong></p>
                                @if($order->payment_method === 'transfer')
                                    <p>Silakan transfer ke rekening berikut:</p>
                                    <p class="font-mono bg-white dark:bg-slate-800 p-3 rounded-xl mt-2 text-slate-900 dark:text-white">Bank BCA: 1234567890<br>a.n. Dinas Pariwisata Jepara</p>
                                @elseif($order->payment_method === 'cash')
                                    <p>Pembayaran dapat dilakukan di lokasi wisata saat kunjungan.</p>
                                @else
                                    <p>Instruksi pembayaran akan dikirim ke email Anda.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3" x-data="{ showCancelConfirm: false }">
                    @if($order->status == 'pending')
                        <a href="{{ route('tickets.payment', $order->order_number) }}" 
                           class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-primary/25 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                        </a>
                        <div class="flex gap-3">
                            <button onclick="
                                this.innerHTML = '<i class=\'fa-solid fa-spinner fa-spin\'></i> Mengecek...';
                                this.disabled = true;
                                const btn = this;
                                fetch('{{ route('tickets.check-status', $order->order_number) }}')
                                    .then(r => r.json())
                                    .then(d => {
                                        if(d.status === 'paid') { window.location.reload(); }
                                        else {
                                            btn.innerHTML = '<i class=\'fa-solid fa-circle-info\'></i> ' + d.message;
                                            setTimeout(() => { btn.innerHTML = '<i class=\'fa-solid fa-arrows-rotate\'></i> Cek Status'; btn.disabled = false; }, 3000);
                                        }
                                    })
                                    .catch(() => { btn.innerHTML = '<i class=\'fa-solid fa-arrows-rotate\'></i> Cek Status'; btn.disabled = false; });
                            " class="flex-1 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold py-3 rounded-2xl transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                                <i class="fa-solid fa-arrows-rotate"></i> Cek Status
                            </button>
                            <button @click="showCancelConfirm = true"
                                class="flex-1 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 font-semibold py-3 rounded-2xl transition-all duration-300 flex items-center justify-center gap-2 text-sm border border-red-200 dark:border-red-800">
                                <i class="fa-solid fa-xmark"></i> Batalkan
                            </button>
                        </div>
                        <a href="{{ route('tickets.my') }}" 
                           class="w-full bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold py-3 rounded-2xl transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Tiket Saya
                        </a>

                        {{-- Cancel Confirmation Modal --}}
                        <div x-show="showCancelConfirm" x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center"
                                 @click.away="showCancelConfirm = false">
                                <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
                                </div>
                                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Batalkan Pesanan?</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Pesanan <strong class="font-mono">{{ $order->order_number }}</strong> akan dibatalkan secara permanen.</p>
                                <div class="flex gap-3">
                                    <button @click="showCancelConfirm = false"
                                        class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-colors text-sm">
                                        Kembali
                                    </button>
                                    <form action="{{ route('tickets.cancel', $order->order_number) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-colors text-sm">
                                            Ya, Batalkan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        @if(in_array($order->status, ['paid', 'used']))
                        <button onclick="downloadTicketImage()" 
                           class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-primary/25 flex items-center justify-center gap-2"
                           id="downloadBtn">
                            <i class="fa-solid fa-download"></i> Download Tiket
                        </button>
                        @endif
                        <a href="{{ route('tickets.my') }}" 
                           class="w-full bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold py-3 rounded-2xl transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Tiket Saya
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Ticket Card for PNG Generation (off-screen) -->
    @if(in_array($order->status, ['paid', 'used']))
    <div style="position: fixed; left: -9999px; top: 0; z-index: -1;" class="font-inter text-slate-800">
        <!-- Ticket Container -->
        <div id="ticket-card" class="max-w-[400px] w-[400px] bg-white border border-blue-100 overflow-hidden relative ticket-card shadow-xl shadow-blue-100/50">
            
            <!-- Header / Brand Section -->
            <div class="bg-blue-600 text-white p-8 text-center relative overflow-hidden">
                <!-- Subtle Texture/Noise could go here -->
                <div class="relative z-10">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-blue-100 mb-2">@lang('tickets.department')</p>
                    <h1 class="text-3xl font-serif italic tracking-wide text-white">@lang('tickets.header')</h1>
                    <div class="w-16 h-px bg-blue-400 mx-auto mt-4"></div>
                </div>
                
                <!-- Abstract decorative circles for premium feel -->
                <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-2xl"></div>
                <div class="absolute bottom-0 right-0 w-48 h-48 bg-blue-800/20 rounded-full translate-x-1/3 translate-y-1/3 blur-xl"></div>
            </div>

            <!-- Ticket Body -->
            <div class="p-8 relative">
                 <!-- Decorative connectors mimicking a physical ticket tear-off line -->
                 <div class="absolute top-0 left-0 w-4 h-8 bg-blue-50 rounded-r-full -mt-4 z-20"></div>
                 <div class="absolute top-0 right-0 w-4 h-8 bg-blue-50 rounded-l-full -mt-4 z-20"></div>
                 
                 <!-- Primary Info -->
                 <div class="text-center mb-8">
                     <p class="text-slate-500 text-xs uppercase tracking-widest mb-1">@lang('tickets.destination')</p>
                     <h2 class="text-2xl font-serif font-bold text-slate-900 leading-tight">
                         {{ $order->ticket->place->name }}
                     </h2>
                 </div>

                <!-- QR Code Section -->
                <div class="flex justify-center mb-8">
                    <div class="p-4 border border-blue-100 bg-blue-50/50 rounded-xl">
                        <!-- ID changed to ticket-qrcode for unique targeting -->
                        <div id="ticket-qrcode" class="mix-blend-multiply opacity-90"></div>
                    </div>
                </div>
                <div class="text-center mb-8">
                    <p class="text-[10px] text-slate-400 tracking-widest uppercase mb-1">NO. TIKET / TICKET NO.</p>
                    <p class="font-mono text-lg font-bold text-slate-700 tracking-wider">{{ $order->ticket_number }}</p>
                    <div class="mt-2 inline-block px-4 py-1 border border-blue-100 bg-blue-50 rounded-full">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-2 gap-y-6 gap-x-4 border-t border-slate-100 pt-6">
                    
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-medium mb-1">@lang('tickets.visit_date')</p>
                        <p class="font-serif text-lg text-slate-800">{{ $order->visit_date->translatedFormat('d M Y') }}</p>
                    </div>

                    <div class="text-right">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-medium mb-1">@lang('tickets.visitors')</p>
                        <p class="font-serif text-lg text-slate-800">{{ $order->quantity }} @lang('tickets.people')</p>
                    </div>

                    <div class="col-span-2">
                         <p class="text-[10px] uppercase tracking-wider text-slate-400 font-medium mb-1">@lang('tickets.ticket_type')</p>
                         <div class="flex items-baseline gap-2">
                            <span class="font-serif text-lg text-slate-800">{{ $order->ticket->name }}</span>
                            <span class="text-xs text-slate-500 italic">({{ ucfirst($order->ticket->type) }})</span>
                         </div>
                    </div>
                </div>

                <!-- Total Section -->
                <div class="mt-8 pt-6 border-t border-dashed border-slate-300">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-medium mb-1">@lang('tickets.customer')</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $order->customer_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-medium mb-1">@lang('tickets.total_payment')</p>
                            <p class="font-serif text-2xl font-bold text-blue-600">RP {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer / Decorative Bottom -->
            <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
                <p class="text-[10px] text-slate-400 italic font-serif">@lang('tickets.footer_thanks')</p>
            </div>
        </div>
    </div>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // QR code for the visible confirmation page
    const qrcodeElement = document.getElementById("qrcode");
    if (qrcodeElement) {
        new QRCode(qrcodeElement, {
            text: "{{ $order->ticket_number }}",
            width: 800, // Reduced for more padding
            height: 800,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    // QR code for the hidden ticket card (for PNG download)
    const ticketQrcodeElement = document.getElementById("ticket-qrcode");
    if (ticketQrcodeElement) {
        new QRCode(ticketQrcodeElement, {
            text: "{{ $order->ticket_number }}",
            width: 120, // 120px to match the container
            height: 120,
            colorDark : "#1e40af", // Blue-800 to match theme
            colorLight : "#eff6ff", // Blue-50 to match theme
            correctLevel : QRCode.CorrectLevel.H
        });
    }
    
    // Scale down visible QR via CSS
    const styleQR = () => {
        const qrc = document.getElementById("qrcode");
        if (!qrc) return;
        
        const canvas = qrc.querySelector('canvas');
        const img = qrc.querySelector('img');
        if(canvas) { 
            canvas.style.width = '100%'; 
            canvas.style.height = '100%'; 
        }
        if(img) { 
            img.style.width = '100%'; 
            img.style.height = '100%'; 
            img.style.display = 'block';
        }
    };
    
    // Run immediately and after short delay
    styleQR();
    setTimeout(styleQR, 0);
});

function downloadQR() {
    const sourceCanvas = document.querySelector('#qrcode canvas');
    if (!sourceCanvas) return;

    // Create a new canvas for the final image with padding
    const padding = 100;
    const size = sourceCanvas.width;
    const newSize = size + (padding * 2);
    
    const finalCanvas = document.createElement('canvas');
    finalCanvas.width = newSize;
    finalCanvas.height = newSize;
    const ctx = finalCanvas.getContext('2d');

    // Fill with white background
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, newSize, newSize);

    // Draw original QR code centered
    ctx.drawImage(sourceCanvas, padding, padding);

    // Export as JPG
    const url = finalCanvas.toDataURL('image/jpeg', 1.0);
    const link = document.createElement('a');
    link.download = 'ticket-qr-{{ $order->ticket_number }}.jpg';
    link.href = url;
    link.click();
}

function downloadTicketImage() {
    const btn = document.getElementById('downloadBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...';
    btn.disabled = true;

    const ticketCard = document.getElementById('ticket-card');
    
    html2canvas(ticketCard, {
        scale: 3,
        useCORS: true,
        backgroundColor: '#ffffff'
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'E-Tiket-{{ $order->ticket_number }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();

        btn.innerHTML = originalText;
        btn.disabled = false;
    }).catch(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Gagal generate tiket. Silakan coba lagi.');
    });
}
</script>
</x-public-layout>

