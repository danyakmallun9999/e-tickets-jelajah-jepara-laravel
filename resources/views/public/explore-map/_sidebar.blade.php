{{-- Sidebar Component --}}
<aside class="w-full lg:w-[400px] xl:w-[450px] flex-shrink-0 flex flex-col bg-white dark:bg-slate-900 border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-slate-700 z-20 transition-transform duration-300 absolute lg:relative h-full"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    
    {{-- Header Section --}}
    <div class="p-6 pb-4 border-b border-slate-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm sticky top-0 z-10">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3">
                 <a href="{{ route('welcome') }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-sky-500 hover:text-white dark:hover:bg-sky-500 transition group" title="Kembali ke Beranda">
                    <span class="material-symbols-outlined text-sm group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                 </a>
                <h1 class="text-2xl font-bold leading-tight tracking-tight font-display text-slate-800 dark:text-white">Kabupaten Jepara</h1>
            </div>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="px-6 py-4">
        <div class="relative flex items-center w-full h-12 rounded-xl bg-slate-100 dark:bg-slate-800 focus-within:ring-2 focus-within:ring-sky-500/50 transition-all border border-transparent focus-within:border-sky-500/30">
            <div class="grid place-items-center h-full w-12 text-slate-400 dark:text-slate-500">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input class="peer h-full w-full bg-transparent border-none text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-0 text-base font-normal leading-normal font-sans" 
                   id="search" placeholder="Cari nama lokasi, jalan..." type="text"
                   x-model="searchQuery" @input.debounce.300ms="performSearch()">
                   
             {{-- Search Dropdown --}}
            <div x-show="searchResults.length > 0" @click.outside="searchResults = []"
                 class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 max-h-60 overflow-y-auto z-50 p-2" 
                 x-cloak x-transition>
                <template x-for="result in searchResults" :key="result.id">
                    <button @click="selectFeature(result)" class="w-full text-left px-3 py-2 hover:bg-sky-50 dark:hover:bg-sky-900/30 rounded-lg transition flex items-center gap-3">
                        <span class="material-symbols-outlined text-sky-500 text-sm">location_on</span>
                        <div>
                            <p class="font-bold text-sm text-slate-800 dark:text-white font-display" x-text="result.name"></p>
                            <p class="text-xs text-slate-400" x-text="result.type"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- Filter Dropdowns (Accordions) --}}
    <div class="px-6 py-4 border-b border-dashed border-slate-200 dark:border-slate-700">
        <div class="space-y-3">
            {{-- Categories Dropdown --}}
            <div x-data="{ expanded: true }" class="border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 overflow-hidden">
                <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-500 text-lg">category</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200 font-display">Kategori Lokasi</span>
                        <span class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-sky-100 dark:bg-sky-900/50 text-[10px] text-sky-600 dark:text-sky-400 font-bold" x-text="selectedCategories.length"></span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''">keyboard_arrow_down</span>
                </button>
                <div x-show="expanded" x-collapse class="p-3 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <div class="flex gap-2 flex-wrap">
                        @foreach($categories as $category)
                        <button @click="toggleCategory({{ $category->id }})" 
                                :class="selectedCategories.includes({{ $category->id }}) ? 'bg-sky-500 text-white border-sky-500' : 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 border-slate-200 dark:border-slate-600'"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all border text-xs font-medium active:scale-95 flex-grow justify-center">
                            <i class="{{ $category->icon_class ?? 'fa-solid fa-map-marker-alt' }} text-sm"></i>
                            <span>{{ $category->name }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scrollable Content Area --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar px-6 relative">
        
        {{-- Sticky Toolbar --}}
        <div class="sticky top-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md z-10 pb-4 pt-4 -mx-6 px-6 border-b border-slate-100 dark:border-slate-800 mb-4 transition-all sidebar-header">
            <div class="flex items-center justify-between">
                 <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-slate-800 dark:text-white font-display" x-text="visiblePlaces.length"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 font-display">Lokasi</span>
                 </div>
                 
                 <button @click="toggleSortNearby()" 
                         :class="sortByDistance ? 'bg-gradient-to-r from-sky-500 to-cyan-500 text-white ring-2 ring-sky-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'"
                         class="text-[10px] font-bold px-3 py-1.5 rounded-full transition-all flex items-center gap-1.5 active:scale-95 group">
                     <span class="material-symbols-outlined text-base transition-transform group-hover:scale-110" :class="sortByDistance ? 'text-white' : ''">near_me</span>
                     <span x-text="sortByDistance ? 'Terdekat' : 'Cari Terdekat'"></span>
                 </button>
            </div>
        </div>
        
         {{-- List of Places --}}
         <div class="pb-6 space-y-3">
             <template x-if="visiblePlaces.length === 0">
                 <div class="text-center py-12 px-4 flex flex-col items-center justify-center">
                     <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 text-slate-300 dark:text-slate-600">
                         <span class="material-symbols-outlined text-3xl">location_off</span>
                     </div>
                     <p class="text-slate-500 dark:text-slate-400 font-medium font-display">Tidak ada lokasi ditemukan</p>
                     <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-[200px]">Coba ubah kata kunci pencarian atau filter kategori.</p>
                 </div>
             </template>

             <template x-for="place in visiblePlaces" :key="place.id">
                <div @click="selectPlace(place)" 
                     :class="selectedFeature && selectedFeature.id === place.id ? 'border-sky-500 ring-1 ring-sky-500 bg-sky-50 dark:bg-sky-900/20' : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-slate-200 dark:hover:border-slate-600'"
                     class="flex gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-300 group relative overflow-hidden">
                    
                    {{-- Active Indicator Strip --}}
                    <div x-show="selectedFeature && selectedFeature.id === place.id" class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-sky-500 to-cyan-500"></div>

                    {{-- Image --}}
                    <div class="w-20 h-20 rounded-xl bg-slate-100 dark:bg-slate-700 shrink-0 relative overflow-hidden ring-1 ring-slate-200/50 dark:ring-slate-600/50 group-hover:ring-sky-500/30 transition-all">
                        <template x-if="place.image_path">
                            <img :src="'{{ url('/') }}/' + place.image_path" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </template>
                        <template x-if="!place.image_path">
                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-500 bg-slate-50 dark:bg-slate-700">
                                <i class="fa-solid fa-map-marker-alt text-2xl opacity-50"></i>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Content --}}
                    <div class="flex flex-col min-w-0 flex-1 py-0.5">
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="font-bold text-base text-slate-800 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors leading-tight line-clamp-2 font-display" x-text="place.name"></h4>
                        </div>
                        
                        <div class="mt-auto flex items-center justify-between">
                            {{-- Category Badge --}}
                            <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-700 w-fit">
                                 <span class="w-1.5 h-1.5 rounded-full" :style="`background-color: ${place.category?.color || '#0ea5e9'}`"></span>
                                 <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 truncate max-w-[80px]" x-text="place.category?.name"></p>
                            </div>

                            {{-- Distance Badge --}}
                            <template x-if="place.distance">
                                <div class="flex items-center gap-1 text-sky-600 dark:text-sky-400">
                                    <span class="material-symbols-outlined text-[14px]">directions_walk</span>
                                    <span class="text-xs font-bold" x-text="place.distance + ' km'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    {{-- Hover Arrow --}}
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                        <span class="material-symbols-outlined text-slate-300 dark:text-slate-500">chevron_right</span>
                    </div>
                </div>
             </template>
        </div>
        
        {{-- Footer --}}
        <div class="mt-4 pt-6 border-t border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500 font-sans">© 2025 SIG Dinas Pariwisata</span>
            </div>
        </div>
    </div>
    
</aside>
