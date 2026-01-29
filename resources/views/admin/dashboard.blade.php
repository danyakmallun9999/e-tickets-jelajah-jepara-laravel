@pushOnce('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endPushOnce

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Admin Panel</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            </div>
            <div class="flex items-center gap-3">

                <a href="{{ route('admin.places.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Destinasi
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Places Card -->
                <a href="{{ route('admin.places.index') }}" class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1 border border-slate-100">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-blue-50 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Destinasi Wisata</p>
                            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['places_count'] }}</h3>
                            <p class="text-xs text-blue-600 font-medium mt-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                Lihat Data <i class="fa-solid fa-arrow-right"></i>
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-sm group-hover:shadow-md transition-shadow">
                            <i class="fa-solid fa-map-marker-alt"></i>
                        </div>
                    </div>
                </a>

                <!-- Categories Card -->
                <a href="{{ route('admin.categories.index') }}" class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1 border border-slate-100">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-pink-50 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Kategori</p>
                            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ \App\Models\Category::count() }}</h3>
                            <p class="text-xs text-pink-600 font-medium mt-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                Kelola <i class="fa-solid fa-arrow-right"></i>
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center text-xl shadow-sm group-hover:shadow-md transition-shadow">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                    </div>
                </a>



                <!-- Posts Card -->
                <a href="{{ route('admin.posts.index') }}" class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1 border border-slate-100">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-purple-50 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Berita & Agenda</p>
                            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ \App\Models\Post::count() }}</h3>
                            <p class="text-xs text-purple-600 font-medium mt-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                Kelola <i class="fa-solid fa-arrow-right"></i>
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-sm group-hover:shadow-md transition-shadow">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                    </div>
                </a>

                <!-- Boundaries Card -->
                <a href="{{ route('admin.boundaries.index') }}" class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1 border border-slate-100">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-green-50 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Batas Wilayah</p>
                            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['boundaries_count'] }}</h3>
                            <p class="text-xs text-green-600 font-medium mt-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                Lihat Data <i class="fa-solid fa-arrow-right"></i>
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-xl shadow-sm group-hover:shadow-md transition-shadow">
                            <i class="fa-solid fa-map"></i>
                        </div>
                    </div>
                </a>


            </div>

            <!-- Additional Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-700">Total Luas Batas Wilayah</h3>
                        <i class="fa-solid fa-map text-green-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_boundary_area'] ?? 0, 2) }} ha</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['boundaries_count'] }} batas wilayah</p>
                </div>


            </div>

            <!-- Charts and Mini Map -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart: Categories -->
                <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Destinasi per Kategori</h3>
                    <div class="relative h-96">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                </div>


            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Places -->
                <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Destinasi Terbaru</h3>
                        <a href="{{ route('admin.places.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($stats['recent_places'] as $place)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            @if($place->image_path)
                            <img src="{{ asset($place->image_path) }}" alt="{{ $place->name }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $place->category->color }}20">
                                <i class="{{ $place->category->icon_class ?? 'fa-solid fa-map-marker-alt' }}" style="color: {{ $place->category->color }}"></i>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $place->name }}</p>
                                <p class="text-xs text-gray-500">{{ $place->category->name }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $place->created_at->diffForHumans() }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada destinasi</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Boundaries -->
                <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Batas Wilayah Terbaru</h3>
                        <a href="{{ route('admin.boundaries.index') }}" class="text-sm text-green-600 hover:text-green-800">Lihat Semua</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($stats['recent_boundaries'] as $boundary)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="fa-solid fa-map text-green-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $boundary->name }}</p>
                                <p class="text-xs text-gray-500">{{ $boundary->type }} @if($boundary->area_hectares) · {{ number_format($boundary->area_hectares, 2) }} ha @endif</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $boundary->created_at->diffForHumans() }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada batas wilayah</p>
                        @endforelse
                    </div>
                </div>
            </div>



            <!-- Places Table -->
            <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Destinasi Wisata</h3>
                    <p class="text-sm text-gray-500">Total: {{ $places->total() }} destinasi</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Koordinat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($places as $place)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            @if($place->image_path)
                                                <img src="{{ asset($place->image_path) }}" alt="{{ $place->name }}" class="w-12 h-12 rounded-lg object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                    <i class="fa-solid fa-map-location-dot"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $place->name }}</p>
                                                <p class="text-xs text-gray-500 line-clamp-1">{{ $place->description }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold text-white" style="background-color: {{ $place->category->color }}">
                                            <i class="{{ $place->category->icon_class ?? 'fa-solid fa-map-marker-alt' }}"></i>
                                            {{ $place->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $place->latitude }}, {{ $place->longitude }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.places.edit', $place) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.places.destroy', $place) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada data destinasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $places->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Categories Chart
            const categoriesCtx = document.getElementById('categoriesChart');
            if (categoriesCtx) {
                new Chart(categoriesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($stats['categories']->pluck('name')->values()),
                        datasets: [{
                            data: @json($stats['categories']->pluck('places_count')->values()),
                            backgroundColor: @json($stats['categories']->pluck('color')->values()),
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }




        });
    </script>
</x-app-layout>

