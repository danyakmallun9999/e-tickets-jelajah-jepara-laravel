{{-- Sidebar Component --}}
<aside class="w-full lg:w-[400px] xl:w-[450px] flex-shrink-0 flex flex-col bg-white dark:bg-[#24211b] border-b lg:border-b-0 lg:border-r border-[#e5e7eb] dark:border-[#3a3630] z-20 shadow-xl transition-transform duration-300 absolute lg:relative h-full"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    
    {{-- Header Section --}}
    <div class="p-6 pb-4 border-b border-surface-light dark:border-stone-800 bg-white/50 dark:bg-[#24211b]/50 backdrop-blur-sm sticky top-0 z-10">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3">
                 <a href="{{ route('welcome') }}" class="flex items-center justify-center w-8 h-8 rounded-full bg-surface-light hover:bg-primary hover:text-white transition group" title="Kembali ke Beranda">
                    <span class="material-symbols-outlined text-sm group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                 </a>
                <h1 class="text-3xl font-bold leading-tight tracking-tight font-display bg-gradient-to-r from-text-light to-text-light/70 dark:from-text-dark dark:to-text-dark/70 bg-clip-text text-transparent">Kabupaten Jepara</h1>
            </div>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="px-6 py-4">
        <div class="relative flex items-center w-full h-12 rounded-lg bg-[#f0eeea] dark:bg-[#322f29] focus-within:ring-2 focus-within:ring-primary/50 transition-all">
            <div class="grid place-items-center h-full w-12 text-text-light/50 dark:text-text-dark/50">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input class="peer h-full w-full bg-transparent border-none text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:ring-0 text-base font-normal leading-normal font-sans" 
                   id="search" placeholder="Cari nama lokasi, jalan..." type="text"
                   x-model="searchQuery" @input.debounce.300ms="performSearch()">
                   
             {{-- Search Dropdown --}}
            <div x-show="searchResults.length > 0" @click.outside="searchResults = []"
                 class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-surface-light max-h-60 overflow-y-auto z-50 p-2" 
                 x-cloak x-transition>
                <template x-for="result in searchResults" :key="result.id">
                    <button @click="selectFeature(result)" class="w-full text-left px-3 py-2 hover:bg-surface-light rounded-lg transition flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-sm">location_on</span>
                        <div>
                            <p class="font-bold text-sm text-text-light dark:text-text-dark font-display" x-text="result.name"></p>
                            <p class="text-xs text-text-light/50" x-text="result.type"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- Filter Dropdowns (Accordions) --}}
    <div class="px-6 py-4 border-b border-dashed border-stone-200 dark:border-stone-800">
        <div class="space-y-3">
            {{-- Categories Dropdown --}}
            <div x-data="{ expanded: true }" class="border border-stone-200 dark:border-stone-700 rounded-xl bg-white dark:bg-[#2c2923] overflow-hidden">
                <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-3 bg-stone-50 dark:bg-white/5 hover:bg-stone-100 dark:hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-stone-500 text-lg">category</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-text-light dark:text-text-dark font-display">Kategori Lokasi</span>
                        <span class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-stone-200 dark:bg-stone-700 text-[10px] text-text-light dark:text-text-dark font-bold" x-text="selectedCategories.length"></span>
                    </div>
                    <span class="material-symbols-outlined text-stone-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''">keyboard_arrow_down</span>
                </button>
                <div x-show="expanded" x-collapse class="p-3 border-t border-stone-100 dark:border-stone-700 bg-white dark:bg-[#2c2923]">
                    <div class="flex gap-2 flex-wrap">
                        @foreach($categories as $category)
                        <button @click="toggleCategory({{ $category->id }})" 
                                :class="selectedCategories.includes({{ $category->id }}) ? 'bg-primary text-white border-primary shadow-sm' : 'bg-white dark:bg-[#322f29] text-text-light dark:text-text-dark hover:bg-stone-50 border-stone-200 dark:border-stone-700'"
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
        <div class="sticky top-0 bg-white/95 dark:bg-[#24211b]/95 backdrop-blur-md z-10 pb-4 pt-4 -mx-6 px-6 border-b border-stone-100 dark:border-stone-800 mb-4 transition-all sidebar-header">
            <div class="flex items-center justify-between">
                 <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-text-light dark:text-text-dark font-display" x-text="visiblePlaces.length"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-text-light/40 dark:text-text-dark/40 font-display">Lokasi</span>
                 </div>
                 
                 <button @click="toggleSortNearby()" 
                         :class="sortByDistance ? 'bg-primary text-white shadow-primary/30 shadow-lg ring-2 ring-primary/20' : 'bg-stone-100 dark:bg-white/5 text-text-light/60 dark:text-text-dark/60 hover:bg-stone-200 dark:hover:bg-white/10'"
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
                     <div class="w-16 h-16 bg-stone-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4 text-stone-300 dark:text-stone-600">
                         <span class="material-symbols-outlined text-3xl">location_off</span>
                     </div>
                     <p class="text-text-light/50 dark:text-text-dark/50 font-medium font-display">Tidak ada lokasi ditemukan</p>
                     <p class="text-xs text-text-light/30 dark:text-text-dark/30 mt-1 max-w-[200px]">Coba ubah kata kunci pencarian atau filter kategori.</p>
                 </div>
             </template>

             <template x-for="place in visiblePlaces" :key="place.id">
                <div @click="selectPlace(place)" 
                     :class="selectedFeature && selectedFeature.id === place.id ? 'border-primary ring-1 ring-primary bg-primary/5 dark:bg-primary/10' : 'border-transparent bg-white dark:bg-[#2c2923] hover:border-stone-200 dark:hover:border-stone-700'"
                     class="flex gap-4 p-4 rounded-2xl border shadow-[0_2px_8px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-[0_8px_24px_rgba(0,0,0,0.06)] dark:hover:shadow-none cursor-pointer transition-all duration-300 group relative overflow-hidden">
                    
                    {{-- Active Indicator Strip --}}
                    <div x-show="selectedFeature && selectedFeature.id === place.id" class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>

                    {{-- Image --}}
                    <div class="w-20 h-20 rounded-xl bg-gray-200 shrink-0 relative overflow-hidden ring-1 ring-black/5 dark:ring-white/10 group-hover:ring-primary/30 transition-all">
                        <template x-if="place.image_path">
                            <img :src="'{{ url('/') }}/' + place.image_path" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </template>
                        <template x-if="!place.image_path">
                            <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-white/5">
                                <i class="fa-solid fa-map-marker-alt text-2xl opacity-50"></i>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Content --}}
                    <div class="flex flex-col min-w-0 flex-1 py-0.5">
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="font-bold text-base text-text-light dark:text-text-dark group-hover:text-primary transition-colors leading-tight line-clamp-2 font-display" x-text="place.name"></h4>
                        </div>
                        
                        <div class="mt-auto flex items-center justify-between">
                            {{-- Category Badge --}}
                            <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-stone-100 dark:bg-white/5 w-fit">
                                 <span class="w-1.5 h-1.5 rounded-full" :style="`background-color: ${place.category?.color || '#ccc'}`"></span>
                                 <p class="text-[10px] font-bold uppercase tracking-wider text-text-light/60 dark:text-text-dark/60 truncate max-w-[80px]" x-text="place.category?.name"></p>
                            </div>

                            {{-- Distance Badge --}}
                            <template x-if="place.distance">
                                <div class="flex items-center gap-1 text-primary">
                                    <span class="material-symbols-outlined text-[14px]">directions_walk</span>
                                    <span class="text-xs font-bold" x-text="place.distance + ' km'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    {{-- Hover Arrow --}}
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                        <span class="material-symbols-outlined text-stone-300 dark:text-stone-600">chevron_right</span>
                    </div>
                </div>
             </template>
        </div>
        
        {{-- Footer --}}
        <div class="mt-4 pt-6 border-t border-[#e5e7eb] dark:border-[#3a3630]">
            <div class="flex items-center justify-between">
                <span class="text-xs text-text-light/40 dark:text-text-dark/40 font-sans">© 2025 SIG Dinas Pariwisata</span>
            </div>
        </div>
    </div>
    
</aside>
