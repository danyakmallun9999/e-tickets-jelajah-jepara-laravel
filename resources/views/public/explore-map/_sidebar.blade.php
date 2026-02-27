{{-- Desktop Sidebar Component - Enhanced with GSAP --}}
<aside id="desktop-sidebar" 
       class="fixed left-0 top-0 bottom-0 w-[420px] flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700 z-20"
       style="transform: translateX(-100%); opacity: 0;"
       x-show="!isNavigating"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       x-init="$nextTick(() => animateSidebar())">
    
    {{-- Header Section --}}
    <div class="p-6 pb-4 border-b border-slate-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm sidebar-header" style="opacity: 0; transform: translateY(-20px);">
        <div class="flex items-center gap-3">
             <a href="{{ route('welcome') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-sky-500 hover:text-white dark:hover:bg-sky-500 transition group" title="{{ __('Map.BackToHome') }}">
                <span class="material-symbols-outlined text-lg group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
             </a>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">{{ __('Map.Title') }}</h1>
                <p class="text-xs text-slate-400">{{ __('Map.Subtitle') }}</p>
            </div>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="px-6 py-4 sidebar-search relative z-[60]" style="opacity: 0; transform: translateY(10px);">
        
        {{-- Normal Search Bar (Hidden when routingUIMode is true) --}}
        <div x-show="!routingUIMode" class="flex gap-2" x-transition>
            <div class="flex-1 flex items-center h-12 rounded-xl bg-slate-100 dark:bg-slate-800 focus-within:ring-2 focus-within:ring-sky-500/50 transition-all border border-transparent focus-within:border-sky-500/30">
                <div class="grid place-items-center h-full w-12 text-slate-400 dark:text-slate-500">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input class="peer h-full w-full bg-transparent border-none text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-0 text-sm" 
                       placeholder="{{ __('Map.SearchPlaceholder') }}" type="text"
                       x-model="searchQuery" @input.debounce.300ms="performSearch()">
            </div>
            <button @click="routingUIMode = true" 
                    title="Cari Rute (A ke B)"
                    class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/50 border border-sky-100 dark:border-sky-800 transition-colors flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined">directions</span>
            </button>
        </div>
        
        {{-- Routing Planner UI (Shown when routingUIMode is true) --}}
        <div x-show="routingUIMode" class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-3 border border-slate-200 dark:border-slate-700 relative" x-cloak x-transition>
            <div class="flex items-start gap-3">
                <div class="flex flex-col items-center mt-3">
                    <div class="w-3 h-3 rounded-full border-2 border-sky-500"></div>
                    <div class="w-0.5 h-8 bg-slate-300 dark:bg-slate-600 my-1"></div>
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                </div>
                <div class="flex-1 space-y-2 relative">
                    {{-- Origin Input --}}
                    <div class="relative">
                        <input type="text" 
                               placeholder="Pilih Titik Awal (A)" 
                               x-model="routeSearchQuery"
                               @focus="routingFocus = 'origin'; routeSearchQuery = ''"
                               @input.debounce.300ms="performRouteSearch()"
                               :value="routingFocus === 'origin' ? routeSearchQuery : (routeOrigin ? routeOrigin.name : '')"
                               class="w-full h-10 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm pl-3 pr-8 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500/50 placeholder:text-slate-400 transition-colors">
                        <button x-show="routeOrigin && routingFocus !== 'origin'" 
                                @click="routeOrigin = null; clearRoute()" 
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                                title="Hapus Titik Awal">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                    
                    {{-- Destination Input --}}
                    <div class="relative">
                        <input type="text" 
                               placeholder="Pilih Tujuan (B)" 
                               x-model="routeSearchQuery"
                               @focus="routingFocus = 'destination'; routeSearchQuery = ''"
                               @input.debounce.300ms="performRouteSearch()"
                               :value="routingFocus === 'destination' ? routeSearchQuery : (routeDestination ? routeDestination.name : '')"
                               class="w-full h-10 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm pl-3 pr-8 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500/50 placeholder:text-slate-400 transition-colors">
                        <button x-show="routeDestination && routingFocus !== 'destination'" 
                                @click="routeDestination = null; clearRoute()" 
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                                title="Hapus Tujuan">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <button @click="routingUIMode = false; routeSearchQuery = ''; routeSearchResults = []; routingFocus = null;" 
                            class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-300 dark:hover:bg-slate-600 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                    <button @click="swapRoutePoints()" 
                            class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors mt-1">
                        <span class="material-symbols-outlined text-[18px]">swap_vert</span>
                    </button>
                </div>
            </div>
            
            {{-- Search Dropdown for Routing --}}
            <div x-show="routeSearchResults.length > 0 && routingFocus" @click.outside="routeSearchResults = [];"
                 class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xl max-h-60 overflow-y-auto z-[100] p-1.5" 
                 x-cloak x-transition>
                <template x-for="result in routeSearchResults" :key="result.unique_id">
                    <button @click="selectRouteLocation(result)" class="w-full text-left px-3 py-2 hover:bg-sky-50 dark:hover:bg-sky-900/30 rounded-lg transition flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-sm text-slate-500" x-text="result.isUserLocation ? 'my_location' : 'place'"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-[13px] text-slate-800 dark:text-white truncate" x-text="result.name"></p>
                            <p class="text-[11px] text-slate-400 truncate" x-text="result.category?.name || 'Lokasi'"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        {{-- Normal Search Dropdown - Positioned relative to search section --}}
        <div x-show="searchResults.length > 0 && !routingUIMode" @click.outside="searchResults = []"
             class="absolute top-full left-6 right-6 mt-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 max-h-60 overflow-y-auto z-[100] p-2" 
             x-cloak x-transition>
            <template x-for="result in searchResults" :key="result.unique_id">
                <button @click="selectFeature(result); searchResults = []" class="w-full text-left px-3 py-2.5 hover:bg-sky-50 dark:hover:bg-sky-900/30 rounded-lg transition flex items-center gap-3">
                    {{-- Image / Icon --}}
                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 overflow-hidden flex-shrink-0 flex items-center justify-center">
                        <template x-if="result.image_url">
                            <img :src="result.image_url" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!result.image_url">
                            <span class="material-symbols-outlined text-slate-400 text-lg">image</span>
                        </template>
                    </div>
                    
                    {{-- Text --}}
                    <div class="min-w-0">
                        <p class="font-bold text-sm text-slate-800 dark:text-white truncate" x-text="result.name"></p>
                        <p class="text-xs text-slate-400 truncate" x-text="result.category?.name || 'Destinasi'"></p>
                    </div>
                </button>
            </template>
        </div>
    </div>

    {{-- Scrollable Content Area --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar px-6 py-4 sidebar-content">
        
        {{-- Category Filter --}}
        <div class="mb-4 pb-4 border-b border-slate-100 dark:border-slate-800 sidebar-categories relative z-[50]" style="opacity: 0; transform: translateY(10px);">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">{{ __('Map.Category') }}</p>
            <div class="flex gap-2 flex-wrap">
                @foreach($categories as $index => $category)
                <button @click="toggleCategory({{ $category->id }})" 
                        :class="selectedCategories.includes({{ $category->id }}) ? 'bg-sky-500 text-white border-sky-500' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-sky-500'"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all border text-xs font-medium active:scale-95 category-btn"
                        style="opacity: 0; transform: scale(0.8);">
                    <i class="{{ $category->icon_class ?? 'fa-solid fa-map-marker-alt' }} text-sm"></i>
                    <span>{{ $category->name }}</span>
                </button>
                @endforeach
            </div>
        </div>
        
        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
             <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-800 dark:text-white" x-text="visiblePlaces.length"></span>
                <span class="text-sm text-slate-400">{{ __('Nav.Destinations') }}</span>
             </div>
             
             <button @click="toggleSortNearby()" 
                     :class="sortByDistance ? 'bg-gradient-to-r from-sky-500 to-cyan-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'"
                     class="text-xs font-bold px-4 py-2 rounded-full transition-all flex items-center gap-2 active:scale-95">
                 <span class="material-symbols-outlined text-base">near_me</span>
                 <span x-text="sortByDistance ? '{{ __('Map.Sort.Nearest') }}' : '{{ __('Map.Sort.SearchNearest') }}'"></span>
             </button>
        </div>
        
         {{-- List of Places --}}
         <div class="space-y-3">
             <template x-if="visiblePlaces.length === 0">
                 <div class="text-center py-12">
                     <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                         <span class="material-symbols-outlined text-3xl text-slate-300">location_off</span>
                     </div>
                     <p class="text-slate-500 font-medium">{{ __('Map.Empty.Title') }}</p>
                     <p class="text-xs text-slate-400 mt-1">{{ __('Map.Empty.Subtitle') }}</p>
                 </div>
             </template>

             <template x-for="(place, index) in visiblePlaces" :key="place.unique_id">
                <div @click="selectPlace(place)" 
                     :class="selectedFeature && selectedFeature.unique_id === place.unique_id ? 'ring-2 ring-sky-500 bg-sky-50 dark:bg-sky-900/20' : 'bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700'"
                     class="flex gap-4 p-4 rounded-2xl cursor-pointer transition-all group place-card"
                     :style="`animation-delay: ${index * 50}ms`" x-show="true">
                    
                    {{-- Image --}}
                    <div class="w-20 h-20 rounded-xl bg-slate-200 dark:bg-slate-700 shrink-0 overflow-hidden">
                        <template x-if="place.image_url">
                            <img :src="place.image_url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </template>
                        <template x-if="!place.image_url">
                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-500">
                                <i class="fa-solid fa-map-marker-alt text-2xl"></i>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Content --}}
                    <div class="flex flex-col min-w-0 flex-1 py-0.5">
                        <h4 class="font-bold text-base text-slate-800 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors leading-tight line-clamp-2" x-text="place.name"></h4>
                        
                        <div class="mt-auto flex items-center gap-3">
                            <span class="text-xs px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300" x-text="place.category?.name"></span>
                            <template x-if="place.distance">
                                <span class="text-xs text-sky-500 font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">directions_walk</span>
                                    <span x-text="place.distance + ' km'"></span>
                                </span>
                            </template>
                        </div>
                    </div>
                    
                    {{-- Arrow --}}
                    <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                    </div>
                </div>
             </template>
        </div>
    </div>
    
    {{-- Footer --}}
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 sidebar-footer" style="opacity: 0;">
        <p class="text-xs text-slate-400 text-center">{{ __('Map.Footer', ['year' => date('Y')]) }}</p>
    </div>
</aside>

<script>
function animateSidebar() {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    
    // Sidebar slide in
    tl.to('#desktop-sidebar', {
        x: 0,
        opacity: 1,
        duration: 0.6,
        ease: 'power4.out'
    });
    
    // Header
    tl.to('.sidebar-header', {
        opacity: 1,
        y: 0,
        duration: 0.4
    }, '-=0.3');
    
    // Search
    tl.to('.sidebar-search', {
        opacity: 1,
        y: 0,
        duration: 0.4
    }, '-=0.2');
    
    // Categories container
    tl.to('.sidebar-categories', {
        opacity: 1,
        y: 0,
        duration: 0.3
    }, '-=0.2');
    
    // Category buttons staggered
    tl.to('.category-btn', {
        opacity: 1,
        scale: 1,
        duration: 0.3,
        stagger: 0.05,
        ease: 'back.out(1.7)'
    }, '-=0.1');
    
    // Content
    tl.to('.sidebar-content', {
        opacity: 1,
        duration: 0.4
    }, '-=0.2');
    
    // Footer
    tl.to('.sidebar-footer', {
        opacity: 1,
        duration: 0.3
    }, '-=0.1');
}
</script>
