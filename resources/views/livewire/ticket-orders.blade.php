<div wire:poll.5s>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-check-circle text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_orders']) }}</p>
                        <p class="text-xs text-gray-500 font-medium">Total Pesanan</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['pending_orders']) }}</p>
                        <p class="text-xs text-gray-500 font-medium">Menunggu Bayar</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['paid_orders']) }}</p>
                        <p class="text-xs text-gray-500 font-medium">Sudah Dibayar</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['revenue'] / 1000, 0) }}k</p>
                        <p class="text-xs text-gray-500 font-medium">Revenue (Total)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                    <select wire:model.live="status" class="w-full border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Pembayaran</option>
                        <option value="paid">Sudah Dibayar</option>
                        <option value="used">Sudah Digunakan</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tanggal Kunjungan</label>
                    <input type="date" wire:model.live="date" class="w-full border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cari</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="No. Pesanan, Nama, Email" class="w-full border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">&nbsp;</label>
                    <div class="flex gap-2">
                        <button type="button" wire:click="$refresh" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-medium text-sm transition-colors" title="Refresh Data">
                            <i class="fa-solid fa-sync mr-2"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-[2.5rem] border border-gray-200 p-1 relative">
            <div wire:loading.flex wire:target="search, status, date, gotoPage, nextPage, previousPage" class="absolute inset-0 bg-white/50 z-10 rounded-[2.5rem] flex items-center justify-center">
                <i class="fa-solid fa-circle-notch fa-spin text-blue-500 text-2xl"></i>
            </div>
            
            <div class="rounded-[2rem] border border-gray-100 overflow-hidden bg-white" id="table-wrapper">
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/50 text-gray-500 font-bold uppercase text-xs tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">No. Pesanan</th>
                                <th class="px-6 py-4">Tiket</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Tgl Kunjungan</th>
                                <th class="px-6 py-4">Jumlah</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-blue-50/30 transition-colors group {{ $loop->even ? 'bg-gray-50/30' : 'bg-white' }}">
                                    <td class="px-6 py-4">
                                        <div class="font-mono font-bold text-gray-800 text-xs">{{ $order->order_number }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $order->ticket->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->ticket->place_name ?? ($order->ticket->place->name ?? 'Lokasi via Relasi') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                                        <div class="text-xs text-gray-400">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $order->visit_date->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $order->visit_date->locale('id')->dayName }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 font-medium">
                                        {{ $order->quantity }} <span class="text-gray-400">tiket</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs rounded-lg px-3 py-1.5 font-bold inline-block border
                                            {{ $order->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                                            {{ $order->status == 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                            {{ $order->status == 'used' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                            {{ $order->status == 'cancelled' ? 'bg-red-50 text-red-700 border-red-100' : '' }}">
                                            {{ match($order->status) {
                                                'pending' => 'Menunggu',
                                                'paid' => 'Dibayar',
                                                'used' => 'Digunakan',
                                                'cancelled' => 'Batal',
                                                default => $order->status
                                            } }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">

                                            
                                            <button @click="$dispatch('open-qr', '{{ $order->order_number }}')" class="p-2.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Lihat QR Code">
                                                <i class="fa-solid fa-qrcode"></i>
                                            </button>
                                            
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                                <i class="fa-solid fa-inbox text-2xl text-gray-400"></i>
                                            </div>
                                            <p class="text-gray-500 mb-1 font-medium">Belum ada pesanan tiket</p>
                                            <p class="text-sm text-gray-400">Pesanan baru akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards (Stacked View) -->
                <div class="md:hidden space-y-4 p-4">
                    @forelse ($orders as $order)
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm relative">
                            <div class="flex justify-between items-start mb-3 border-b border-gray-50 pb-3">
                                <div>
                                    <div class="font-mono font-bold text-gray-800 text-xs bg-gray-50 px-2 py-1 rounded-lg inline-block mb-1">{{ $order->order_number }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <span class="text-[10px] rounded-lg px-2 py-1 font-bold inline-block border
                                    {{ $order->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                                    {{ $order->status == 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                    {{ $order->status == 'used' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-50 text-red-700 border-red-100' : '' }}">
                                    {{ match($order->status) {
                                        'pending' => 'Menunggu',
                                        'paid' => 'Dibayar',
                                        'used' => 'Digunakan',
                                        'cancelled' => 'Batal',
                                        default => $order->status
                                    } }}
                                </span>
                            </div>
                            
                            <div class="flex gap-4 mb-3">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-ticket"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $order->ticket->title }}</h3>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-gray-400"></i>
                                        <span>{{ $order->visit_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2">
                                        <i class="fa-solid fa-user-group text-gray-400"></i>
                                        <span>{{ $order->quantity }} Tiket</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3 mb-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-500">Pemesan</span>
                                    <span class="text-xs font-bold text-gray-900 text-right">{{ $order->customer_name }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Total</span>
                                    <span class="text-sm font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="flex gap-2 justify-end pt-2 border-t border-gray-50">
                                <button @click="$dispatch('open-qr', '{{ $order->order_number }}')" class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors font-bold text-xs" title="Lihat QR Code">
                                    <i class="fa-solid fa-qrcode"></i> QR
                                </button>
                            </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="fa-solid fa-inbox text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">Belum ada pesanan tiket</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>


</div>
