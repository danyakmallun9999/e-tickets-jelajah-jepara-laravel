<x-public-layout :hideFooter="true">
    <div class="bg-gray-50 dark:bg-background-dark min-h-screen -mt-20 pt-32 pb-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex text-xs md:text-sm text-gray-400 mb-6 space-x-2">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors">{{ __('Tickets.Breadcrumb.Home') }}</a>
                <span>/</span>
                <a href="{{ route('tickets.my') }}" class="hover:text-primary transition-colors">{{ __('Tickets.Breadcrumb.MyTickets') }}</a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ __('Tickets.Breadcrumb.Payment') }}</span>
            </nav>

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 md:p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-credit-card text-primary text-2xl"></i>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-2">Pilih Metode Pembayaran</h1>
                    <p class="text-slate-500 dark:text-slate-400">Pilih metode pembayaran yang paling nyaman untuk Anda</p>
                </div>

                <!-- Order Summary -->
                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-2xl p-5 mb-8">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-200 dark:border-slate-600">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-0.5">Pesanan</p>
                            <p class="font-bold text-slate-900 dark:text-white font-mono text-sm">{{ $order->order_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-0.5">Total</p>
                            <p class="text-lg font-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="pt-3 flex items-center gap-3">
                        <i class="fa-solid fa-ticket text-primary"></i>
                        <div>
                            <p class="font-semibold text-sm text-slate-900 dark:text-white">{{ $order->ticket->name }} — {{ $order->ticket->place->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $order->quantity }}x tiket · {{ $order->visit_date->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Selection Form -->
                <form action="{{ route('tickets.process-payment', $order->order_number) }}" method="POST" id="payment-form" x-data="paymentSelector()" @submit="isSubmitting = true">
                    @csrf
                    <input type="hidden" name="payment_type" x-model="paymentType">
                    <input type="hidden" name="bank" x-model="bank">

                    <!-- E-Wallet & QRIS -->
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-primary"></i>
                            E-Wallet & QRIS
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- QRIS -->
                            <button type="button" @click="selectMethod('qris')"
                                :class="paymentType === 'qris' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-600 hover:border-primary/50'"
                                class="relative flex flex-col items-center gap-3 p-5 rounded-2xl border-2 transition-all duration-200 cursor-pointer group">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                                    <i class="fa-solid fa-qrcode text-white text-xl"></i>
                                </div>
                                <div class="text-center">
                                    <p class="font-bold text-sm text-slate-900 dark:text-white">QRIS</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Semua e-wallet</p>
                                </div>
                                <div x-show="paymentType === 'qris'" class="absolute top-2 right-2">
                                    <i class="fa-solid fa-circle-check text-primary text-lg"></i>
                                </div>
                            </button>

                            <!-- GoPay -->
                            <button type="button" @click="selectMethod('gopay')"
                                :class="paymentType === 'gopay' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-600 hover:border-primary/50'"
                                class="relative flex flex-col items-center gap-3 p-5 rounded-2xl border-2 transition-all duration-200 cursor-pointer group">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                    <span class="text-white font-bold text-xs">Go</span>
                                </div>
                                <div class="text-center">
                                    <p class="font-bold text-sm text-slate-900 dark:text-white">GoPay</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">QR & Deeplink</p>
                                </div>
                                <div x-show="paymentType === 'gopay'" class="absolute top-2 right-2">
                                    <i class="fa-solid fa-circle-check text-primary text-lg"></i>
                                </div>
                            </button>

                            <!-- ShopeePay -->
                            <button type="button" @click="selectMethod('shopeepay')"
                                :class="paymentType === 'shopeepay' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-600 hover:border-primary/50'"
                                class="relative flex flex-col items-center gap-3 p-5 rounded-2xl border-2 transition-all duration-200 cursor-pointer group">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/20">
                                    <i class="fa-solid fa-shop text-white text-lg"></i>
                                </div>
                                <div class="text-center">
                                    <p class="font-bold text-sm text-slate-900 dark:text-white">ShopeePay</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Deeplink</p>
                                </div>
                                <div x-show="paymentType === 'shopeepay'" class="absolute top-2 right-2">
                                    <i class="fa-solid fa-circle-check text-primary text-lg"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Virtual Account -->
                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-building-columns text-primary"></i>
                            Virtual Account (Transfer Bank)
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <!-- BCA -->
                            <button type="button" @click="selectMethod('bank_transfer', 'bca')"
                                :class="paymentType === 'bank_transfer' && bank === 'bca' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-600 hover:border-primary/50'"
                                class="relative flex flex-col items-center gap-3 p-4 rounded-2xl border-2 transition-all duration-200 cursor-pointer group">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-700 to-blue-900 rounded-xl flex items-center justify-center shadow-lg shadow-blue-700/20">
                                    <span class="text-white font-bold text-xs">BCA</span>
                                </div>
                                <p class="font-bold text-sm text-slate-900 dark:text-white">BCA</p>
                                <div x-show="paymentType === 'bank_transfer' && bank === 'bca'" class="absolute top-2 right-2">
                                    <i class="fa-solid fa-circle-check text-primary text-lg"></i>
                                </div>
                            </button>

                            <!-- BNI -->
                            <button type="button" @click="selectMethod('bank_transfer', 'bni')"
                                :class="paymentType === 'bank_transfer' && bank === 'bni' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-600 hover:border-primary/50'"
                                class="relative flex flex-col items-center gap-3 p-4 rounded-2xl border-2 transition-all duration-200 cursor-pointer group">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/20">
                                    <span class="text-white font-bold text-xs">BNI</span>
                                </div>
                                <p class="font-bold text-sm text-slate-900 dark:text-white">BNI</p>
                                <div x-show="paymentType === 'bank_transfer' && bank === 'bni'" class="absolute top-2 right-2">
                                    <i class="fa-solid fa-circle-check text-primary text-lg"></i>
                                </div>
                            </button>

                            <!-- BRI -->
                            <button type="button" @click="selectMethod('bank_transfer', 'bri')"
                                :class="paymentType === 'bank_transfer' && bank === 'bri' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-600 hover:border-primary/50'"
                                class="relative flex flex-col items-center gap-3 p-4 rounded-2xl border-2 transition-all duration-200 cursor-pointer group">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                                    <span class="text-white font-bold text-xs">BRI</span>
                                </div>
                                <p class="font-bold text-sm text-slate-900 dark:text-white">BRI</p>
                                <div x-show="paymentType === 'bank_transfer' && bank === 'bri'" class="absolute top-2 right-2">
                                    <i class="fa-solid fa-circle-check text-primary text-lg"></i>
                                </div>
                            </button>

                            <!-- Mandiri -->
                            <button type="button" @click="selectMethod('echannel')"
                                :class="paymentType === 'echannel' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-600 hover:border-primary/50'"
                                class="relative flex flex-col items-center gap-3 p-4 rounded-2xl border-2 transition-all duration-200 cursor-pointer group">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/20">
                                    <span class="text-white font-bold text-[10px] leading-tight text-center">Man<br>diri</span>
                                </div>
                                <p class="font-bold text-sm text-slate-900 dark:text-white">Mandiri</p>
                                <div x-show="paymentType === 'echannel'" class="absolute top-2 right-2">
                                    <i class="fa-solid fa-circle-check text-primary text-lg"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Secure Payment Badge -->
                    <div class="flex items-center justify-center gap-2 mb-6 text-sm text-slate-500 dark:text-slate-400">
                        <i class="fa-solid fa-shield-halved text-green-500"></i>
                        <span>Pembayaran aman diproses oleh Midtrans</span>
                    </div>

                    <!-- Pay Button -->
                    <button type="submit" id="pay-button"
                        :disabled="!paymentType || isSubmitting"
                        :class="paymentType && !isSubmitting ? 'bg-primary hover:bg-primary/90 shadow-lg shadow-primary/25 hover:shadow-xl' : 'bg-slate-300 dark:bg-slate-600 cursor-not-allowed'"
                        class="w-full text-white font-bold py-4 rounded-2xl transition-all duration-300 flex items-center justify-center gap-2">
                        <template x-if="isSubmitting">
                            <span><i class="fa-solid fa-spinner fa-spin mr-2"></i>Memproses pembayaran...</span>
                        </template>
                        <template x-if="!isSubmitting && paymentType">
                            <span>
                                Bayar Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                <i class="fa-solid fa-arrow-right ml-1"></i>
                            </span>
                        </template>
                        <template x-if="!isSubmitting && !paymentType">
                            <span>Pilih metode pembayaran</span>
                        </template>
                    </button>

                    <div class="mt-4 text-center">
                        <a href="{{ route('tickets.my') }}" class="text-slate-500 hover:text-primary text-sm font-medium transition-colors">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>

            <!-- QRIS Info -->
            <div class="mt-4 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-2xl p-4 flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                <p class="text-xs text-blue-700 dark:text-blue-400">
                    <strong>Tip:</strong> Pilih <strong>QRIS</strong> untuk membayar dengan semua e-wallet (GoPay, OVO, DANA, ShopeePay, LinkAja, dll). Cukup scan QR code dengan aplikasi favorit Anda.
                </p>
            </div>
        </div>
    </div>

<script>
function paymentSelector() {
    return {
        paymentType: '',
        bank: '',
        isSubmitting: false,
        selectMethod(type, bankName = '') {
            this.paymentType = type;
            this.bank = bankName;
        }
    }
}
</script>
</x-public-layout>
