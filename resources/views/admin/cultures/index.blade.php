<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="hidden md:block text-sm text-gray-500 mb-0.5">Admin Panel</p>
                <h2 class="font-bold text-xl md:text-2xl text-gray-900 leading-tight">
                    Manajemen Budaya
                </h2>
            </div>
            <a href="{{ route('admin.cultures.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 md:px-5 md:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl font-semibold text-xs md:text-sm text-white hover:shadow-blue-500/40 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:-translate-y-0.5" wire:navigate>
                <i class="fa-solid fa-plus text-xs"></i>
                <span class="hidden md:inline">Tambah Budaya</span>
                <span class="md:hidden">Tambah</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats Overview -->
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200">
                    <div class="flex items-center gap-4 p-5 rounded-[2rem] border border-gray-100 bg-gray-50/30 h-full">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100">
                            <i class="fa-solid fa-masks-theater text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $cultures->total() }}</p>
                            <p class="text-sm text-gray-500 font-medium">Total Budaya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <form method="GET" action="{{ route('admin.cultures.index') }}" id="filter-form" class="flex flex-col sm:flex-row gap-4 w-full">
                
                <!-- Search Bar -->
                <div class="relative w-full max-w-sm group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                         <i class="fa-solid fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        class="block w-full pl-11 pr-11 py-3 border-0 rounded-2xl bg-white text-gray-900 ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 sm:text-sm transition-all shadow-sm placeholder-gray-400" 
                        placeholder="Cari budaya..."
                    >
                </div>

                <!-- Category Filter (Dropdown) -->
                <div class="relative min-w-[240px] z-20" x-data="{ 
                     selected: '{{ request('category') }}',
                     submitForm(val) {
                         this.selected = val;
                         setTimeout(() => document.getElementById('filter-form').submit(), 50);
                     }
                }">
                    <input type="hidden" name="category" :value="selected">
                    <x-dropdown align="left" width="64" contentClasses="py-1 bg-white max-h-60 overflow-y-auto">
                        <x-slot name="trigger">
                            <button type="button" class="flex items-center justify-between w-full pl-4 pr-3 py-3 ring-1 ring-gray-200 rounded-2xl bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 sm:text-sm transition-all shadow-sm hover:ring-blue-300">
                                <span class="truncate font-medium" x-text="selected || 'Semua Kategori'"></span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-2"></i>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <button type="button" @click="submitForm('')" class="block w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors" :class="!selected ? 'font-bold text-blue-600 bg-blue-50/50' : 'text-gray-700'">
                                Semua Kategori
                            </button>
                            <div class="border-t border-gray-100 my-1"></div>
                            @foreach($categories as $category)
                                <button type="button" @click="submitForm('{{ $category }}')" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors" :class="selected === '{{ $category }}' ? 'font-bold text-blue-600 bg-blue-50/50' : 'text-gray-700'">
                                    {{ $category }}
                                </button>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>
                
                <div class="flex items-center">
                    <button type="submit" class="hidden">Submit</button>
                    @if(request('search') || request('category'))
                        <a href="{{ route('admin.cultures.index') }}" class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors px-4 py-2 whitespace-nowrap">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>

            <!-- Main Content Card -->
            <div class="bg-white p-1 rounded-[2.5rem] border border-gray-200 shadow-sm relative z-10">
                <div class="rounded-[2rem] border border-gray-100 overflow-hidden bg-white">
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-50">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Budaya</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi & Waktu</th>
                                    <th class="px-8 py-5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                @forelse ($cultures as $culture)
                                    <tr class="group hover:bg-blue-50/30 transition-all duration-200 {{ $loop->even ? 'bg-gray-50/30' : 'bg-white' }}">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="h-14 w-20 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 border border-gray-200/50 shadow-sm">
                                                    @if($culture->image)
                                                        <img src="{{ $culture->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                                            <i class="fa-solid fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <a href="{{ route('admin.cultures.edit', $culture) }}" class="text-sm font-bold text-gray-900 hover:text-blue-600 transition-colors line-clamp-1" wire:navigate>
                                                        {{ $culture->name }}
                                                    </a>
                                                    @if($culture->description)
                                                        <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-1 max-w-xs">{{ $culture->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 line-clamp-1 max-w-[200px]" title="{{ $culture->category }}">
                                                {{ $culture->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            @if($culture->location || $culture->time)
                                                <div class="flex flex-col gap-1">
                                                    @if($culture->location)
                                                        <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                            <i class="fa-solid fa-location-dot text-red-400 w-3"></i>
                                                            <span class="line-clamp-1 max-w-[200px]" title="{{ $culture->location }}">{{ $culture->location }}</span>
                                                        </div>
                                                    @endif
                                                    @if($culture->time)
                                                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                                            <i class="fa-regular fa-clock w-3"></i>
                                                            <span class="line-clamp-1 max-w-[200px]">{{ $culture->time }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-[11px] text-gray-400 italic">Tidak ada info</span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-5 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <a href="{{ route('culture.show', $culture) }}" target="_blank"
                                                   class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all font-medium" 
                                                   title="Lihat">
                                                    <i class="fa-solid fa-external-link text-sm"></i>
                                                </a>
                                                <a href="{{ route('admin.cultures.edit', $culture) }}" 
                                                   class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all font-medium" 
                                                   title="Edit" wire:navigate>
                                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                                </a>
                                                <form action="{{ route('admin.cultures.destroy', $culture) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus budaya ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all font-medium" 
                                                            title="Hapus">
                                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 whitespace-nowrap text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                                                    <i class="fa-solid fa-masks-theater text-3xl text-gray-300"></i>
                                                </div>
                                                <p class="text-gray-600 font-medium mb-1">Tidak ada budaya ditemukan</p>
                                                <p class="text-sm text-gray-400 mb-4">Coba ubah filter pencarian atau tambahkan budaya baru</p>
                                                <a href="{{ route('admin.cultures.create') }}" 
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm" wire:navigate>
                                                    <i class="fa-solid fa-plus text-xs"></i>
                                                    Tambah Budaya
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (Stacked View) -->
                    <div class="md:hidden space-y-4 p-4 text-left">
                        @forelse ($cultures as $culture)
                            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm relative">
                                <div class="flex gap-4">
                                    <!-- Image -->
                                    <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 shadow-sm">
                                        @if($culture->image)
                                            <img src="{{ $culture->image_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <i class="fa-solid fa-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100 mb-1.5 line-clamp-1">
                                            {{ $culture->category }}
                                        </span>
                                        
                                        <h3 class="font-bold text-gray-900 line-clamp-2 mb-1 text-sm">{{ $culture->name }}</h3>
                                        
                                        @if($culture->location)
                                            <p class="text-[11px] text-gray-500 flex items-center gap-1.5 line-clamp-1 mt-1">
                                                <i class="fa-solid fa-location-dot text-red-400 text-[10px]"></i>
                                                {{ $culture->location }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center justify-end gap-2 mt-4 pt-3 border-t border-gray-50">
                                    <a href="{{ route('culture.show', $culture) }}" target="_blank" class="px-3 py-1.5 text-[11px] font-bold text-blue-600 bg-blue-50 rounded-lg">
                                        Lihat
                                    </a>
                                    <a href="{{ route('admin.cultures.edit', $culture) }}" class="px-3 py-1.5 text-[11px] font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors" wire:navigate>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.cultures.destroy', $culture) }}" method="POST" onsubmit="return confirm('Hapus budaya ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-[11px] font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="fa-solid fa-masks-theater text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm mb-1 font-medium">Tidak ada budaya</p>
                            </div>
                        @endforelse
                    </div>

                </div>
                
                @if($cultures->hasPages())
                    <div class="px-6 py-4 mt-2">
                        {{ $cultures->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
