<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">E-Tiket</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Pesanan Masuk
                </h2>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors shadow-sm font-medium text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            @php
                $totalOrders = $orders->total();
                $pendingOrders = $orders->where('status', 'pending')->count();
                $paidOrders = $orders->whereIn('status', ['paid', 'used'])->count();
                $totalRevenue = $orders->whereIn('status', ['paid', 'used'])->sum('total_price');
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalOrders) }}</p>
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
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($pendingOrders) }}</p>
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
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($paidOrders) }}</p>
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
                            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue / 1000, 0) }}k</p>
                            <p class="text-xs text-gray-500 font-medium">Revenue (Halaman Ini)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('admin.tickets.orders') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Sudah Digunakan</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tanggal Kunjungan</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="w-full border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Pesanan, Nama, Email" class="w-full border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">&nbsp;</label>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-medium text-sm transition-colors">
                                <i class="fa-solid fa-search mr-2"></i>Filter
                            </button>
                            <button type="button" onclick="window.location.reload()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-medium text-sm transition-colors" title="Refresh Data">
                                <i class="fa-solid fa-sync"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider border-b border-gray-100">
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
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="font-mono font-bold text-gray-800 text-xs">{{ $order->order_number }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $order->ticket->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->ticket->place->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                                        <div class="text-xs text-gray-400">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">{{ $order->visit_date->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $order->visit_date->locale('id')->dayName }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 font-medium">
                                        {{ $order->quantity }} <span class="text-gray-400">tiket</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs rounded-full px-3 py-1.5 font-bold inline-block
                                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $order->status == 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $order->status == 'used' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
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
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showQR('{{ $order->order_number }}')" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Lihat QR Code">
                                                <i class="fa-solid fa-qrcode"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fa-solid fa-inbox text-2xl text-gray-400"></i>
                                            </div>
                                            <p class="text-gray-500">Belum ada pesanan tiket.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($orders->hasPages())
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>



    <!-- QR Code Modal -->
    <div id="qrModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">QR Code Tiket</h3>
                <button onclick="closeQR()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div id="qrContent" class="text-center"></div>
            <div class="mt-4 text-center">
                <button onclick="downloadQR()" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-medium text-sm transition-colors">
                    <i class="fa-solid fa-download mr-2"></i>Download QR Code
                </button>
            </div>
        </div>
    </div>

    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
    let currentOrderNumber = '';

    function showQR(orderNumber) {
        currentOrderNumber = orderNumber;
        document.getElementById('qrModal').classList.remove('hidden');
        
        const qrContent = document.getElementById('qrContent');
        qrContent.innerHTML = `
            <div class="text-sm text-gray-500 mb-4 font-mono">Order: ${orderNumber}</div>
            <div id="qrcode" class="flex justify-center mb-2 mx-auto" style="width: 200px; height: 200px; overflow: hidden;"></div>
            <p class="text-xs text-gray-400 mt-2">Scan QR code ini untuk verifikasi tiket</p>
        `;
        
        // Render QR in high resolution (500px) but display smaller via CSS
        const qrContainer = document.getElementById("qrcode");
        
        new QRCode(qrContainer, {
            text: orderNumber,
            width: 800,  // Reduced for more padding
            height: 800, 
            colorDark : "#000000", // Pure black
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        // Force display size with CSS styles injected into the generated elements
        // We use a MutationObserver or simply style the children immediately after creation
        // But since QRCode.js is synchronous, we can style immediately.
        // Also the container constraint (width: 200px) handles the layout, but the canvas inside needs to fit.
        
        const styleQR = () => {
            const canvas = qrContainer.querySelector('canvas');
            const img = qrContainer.querySelector('img');
            if(canvas) {
                canvas.style.width = '100%';
                canvas.style.height = '100%';
            }
            if(img) {
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.display = 'block'; // Remove extra space
            }
        };

        // Run immediately and after a short delay to catch DOM updates
        styleQR();
        setTimeout(styleQR, 0);
    }

    function closeQR() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    function downloadQR() {
        const sourceCanvas = document.querySelector('#qrcode canvas');
        if (!sourceCanvas) return;

        // Create a new canvas for the final image with padding
        // Target 1000x1000 total
        const padding = 100; // Increased padding
        const size = sourceCanvas.width; // Should be 800
        const newSize = size + (padding * 2); // 800 + 200 = 1000
        
        const finalCanvas = document.createElement('canvas');
        finalCanvas.width = newSize;
        finalCanvas.height = newSize;
        const ctx = finalCanvas.getContext('2d');

        // Fill with white background (JPG doesn't support transparency)
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, newSize, newSize);

        // Draw original QR code centered
        ctx.drawImage(sourceCanvas, padding, padding);

        // Export as JPG
        const url = finalCanvas.toDataURL('image/jpeg', 1.0); // High quality JPG
        const link = document.createElement('a');
        link.download = `ticket-qr-${currentOrderNumber}.jpg`;
        link.href = url;
        link.click();
    }
    </script>
</x-app-layout>
