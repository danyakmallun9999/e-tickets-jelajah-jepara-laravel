<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="hidden md:block text-sm text-gray-500 mb-0.5">Admin Panel</p>
                <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight">
                    Kelola Kategori
                </h2>
            </div>
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 md:px-5 md:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl font-semibold text-xs md:text-sm text-white hover:shadow-blue-500/40 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:-translate-y-0.5" wire:navigate>
                <i class="fa-solid fa-plus text-xs"></i>
                <span class="hidden md:inline">Tambah Kategori</span>
                <span class="md:hidden">Kategori</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-1 gap-4">
                <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200">
                    <div class="flex items-center gap-4 p-5 rounded-[2rem] border border-gray-100 bg-gray-50/30 h-full">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100">
                            <i class="fa-solid fa-tags text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $categories->total() }}</p>
                            <p class="text-sm text-gray-500 font-medium">Total Kategori</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <div class="mb-6" 
                x-data="{ 
                    query: '{{ request('search') }}',
                    updateList() {
                        const params = new URLSearchParams();
                        if (this.query) params.set('search', this.query);
                        
                        const url = `${window.location.pathname}?${params.toString()}`;
                        history.pushState(null, '', url);

                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newContent = doc.getElementById('table-wrapper').innerHTML;
                                document.getElementById('table-wrapper').innerHTML = newContent;
                            });
                    }
                }"
            >
                <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200">
                    <div class="p-4 rounded-[2rem] border border-gray-100 bg-gray-50/30">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            <input 
                                type="text" 
                                x-model="query"
                                @input.debounce.500ms="updateList()"
                                class="block w-full pl-11 pr-10 py-3 border-0 bg-white rounded-xl text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:bg-white sm:text-sm transition-all placeholder-gray-400 shadow-sm" 
                                placeholder="Cari kategori..."
                            >
                            <button 
                                type="button" 
                                x-show="query.length > 0" 
                                @click="query = ''; updateList()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 cursor-pointer transition-colors"
                                style="display: none;"
                            >
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200">
                <div class="rounded-[2rem] border border-gray-100 overflow-hidden bg-white" id="table-wrapper">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Identitas</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Total Destinasi</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($categories as $category)
                                    <tr class="hover:bg-blue-50/30 transition-colors duration-200 group {{ $loop->even ? 'bg-gray-50/30' : 'bg-white' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm text-white group-hover:scale-110 transition-transform duration-300" style="background-color: {{ $category->color }}">
                                                    <i class="{{ $category->icon_class }} text-xl"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $category->name }}</div>
                                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $category->slug }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 item-center">
                                            <div class="flex items-center gap-3">
                                                 <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold border border-gray-200 bg-gray-50 text-gray-600">
                                                    <i class="{{ $category->icon_class }} text-gray-400"></i>
                                                    ICON: {{ $category->icon_class }}
                                                </span>
                                                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold border border-gray-200 bg-gray-50 text-gray-600">
                                                    <span class="w-3 h-3 rounded-full border border-gray-300 shadow-sm" style="background-color: {{ $category->color }}"></span>
                                                    {{ strtoupper($category->color) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-blue-50 text-blue-700 border border-blue-100">
                                                <i class="fa-solid fa-location-dot text-[10px]"></i>
                                                {{ $category->places_count }} Lokasi
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                                   class="p-2.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200" 
                                                   title="Edit" wire:navigate>
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200" 
                                                            title="Hapus">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 whitespace-nowrap text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                                    <i class="fa-solid fa-tags text-3xl text-gray-300"></i>
                                                </div>
                                                <p class="text-gray-600 font-medium mb-1">Belum ada kategori</p>
                                                <p class="text-sm text-gray-400 mb-4">Mulai dengan menambahkan kategori baru</p>
                                                <a href="{{ route('admin.categories.create') }}" 
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition-colors" wire:navigate>
                                                    <i class="fa-solid fa-plus"></i>
                                                    Tambah Kategori
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (Stacked View) -->
                    <div class="md:hidden space-y-4 p-4">
                        @forelse ($categories as $category)
                            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm relative">
                                <div class="flex gap-4">
                                    <!-- Icon Box -->
                                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl flex items-center justify-center text-white shadow-sm" style="background-color: {{ $category->color }}">
                                        <i class="{{ $category->icon_class }} text-2xl"></i>
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 mb-1 text-sm">{{ $category->name }}</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                                                {{ $category->places_count }} Lokasi
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-600 bg-gray-50 px-2 py-0.5 rounded">
                                                {{ strtoupper($category->color) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50 gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1.5 text-xs font-bold text-gray-700 bg-gray-100 rounded-lg" wire:navigate>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 rounded-lg">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="fa-solid fa-tags text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm">Tidak ada kategori</p>
                            </div>
                        @endforelse
                    </div>

                    @if($categories->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
