{{-- Payment Methods — Doughnut + Interactive Legend --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-full">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-200">
            <i class="fa-solid fa-credit-card text-white text-sm"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-800">Metode Pembayaran</h3>
            <p class="text-xs text-gray-500">Distribusi pendapatan</p>
        </div>
    </div>

    @if(count($paymentMethods) > 0)
        @php
            $chartColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'];
            $totalRevenue = $summary['gross_revenue'] > 0 ? $summary['gross_revenue'] : 1;
        @endphp

        {{-- Donut Chart --}}
        <div id="paymentMethodChart" class="mx-auto"></div>

        {{-- Legend --}}
        <div class="mt-4 space-y-2">
            @foreach($paymentMethods as $index => $method)
                @php $pct = round(($method->revenue / $totalRevenue) * 100, 1); @endphp
                <div class="group p-3 rounded-xl hover:bg-gray-50 transition-all duration-200 cursor-default">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full flex-shrink-0 ring-2 ring-white shadow" style="background-color: {{ $chartColors[$index % count($chartColors)] }}"></span>
                            <span class="text-sm text-gray-700 font-medium">{{ $method->payment_method ?: 'Lainnya' }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-800">Rp {{ number_format($method->revenue, 0, ',', '.') }}</span>
                    </div>
                    {{-- Progress bar --}}
                    <div class="flex items-center gap-2 pl-5">
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-1.5 rounded-full transition-all duration-700" style="width: {{ $pct }}%; background-color: {{ $chartColors[$index % count($chartColors)] }}"></div>
                        </div>
                        <span class="text-xs text-gray-500 font-semibold w-12 text-right">{{ $pct }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Enhanced Empty State --}}
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mb-3">
                <i class="fa-solid fa-credit-card text-2xl text-amber-300"></i>
            </div>
            <p class="text-gray-600 font-medium mb-1">Belum Ada Data</p>
            <p class="text-sm text-gray-400">Ubah periode untuk melihat distribusi pembayaran</p>
        </div>
    @endif
</div>
