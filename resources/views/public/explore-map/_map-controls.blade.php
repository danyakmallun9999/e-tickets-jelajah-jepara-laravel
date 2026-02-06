{{-- Map Controls --}}

{{-- Floating Controls (Top Right) - Layer Toggle --}}
<div class="absolute top-4 right-4 flex flex-col gap-2 z-[400]">
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center justify-center h-10 w-10 bg-white dark:bg-[#2c2923] rounded-lg shadow-md text-text-light dark:text-text-dark hover:bg-stone-50 dark:hover:bg-stone-700 transition-colors">
            <span class="material-symbols-outlined">layers</span>
        </button>
        {{-- Dropdown --}}
        <div x-show="open" @click.outside="open = false" class="absolute top-0 right-12 w-48 bg-white dark:bg-[#2c2923] p-3 rounded-lg shadow-xl border border-stone-100 dark:border-stone-800" x-cloak x-transition>
             <p class="text-[10px] font-bold uppercase text-text-light/50 mb-2 font-display">Peta Dasar</p>
             <div class="flex gap-1">
                 <button @click="setBaseLayer('streets')" :class="currentBaseLayer === 'streets' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-300'" class="flex-1 py-1.5 text-[10px] rounded-lg font-bold font-display transition-colors">Jalan</button>
                 <button @click="setBaseLayer('satellite')" :class="currentBaseLayer === 'satellite' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-300'" class="flex-1 py-1.5 text-[10px] rounded-lg font-bold font-display transition-colors">Satelit</button>
             </div>
        </div>
    </div>
</div>

{{-- Floating Controls (Bottom Right) - Zoom & Navigation --}}
<div class="absolute bottom-6 right-6 flex flex-col gap-4 z-[400]">
    {{-- Zoom Controls --}}
    <div class="flex flex-col rounded-lg shadow-lg overflow-hidden bg-white dark:bg-[#2c2923] divide-y divide-stone-100 dark:divide-stone-700">
        <button @click="map.zoomIn()" class="flex items-center justify-center h-10 w-10 text-text-light dark:text-text-dark hover:bg-stone-50 dark:hover:bg-stone-700 active:bg-stone-100">
            <span class="material-symbols-outlined">add</span>
        </button>
        <button @click="map.zoomOut()" class="flex items-center justify-center h-10 w-10 text-text-light dark:text-text-dark hover:bg-stone-50 dark:hover:bg-stone-700 active:bg-stone-100">
            <span class="material-symbols-outlined">remove</span>
        </button>
    </div>
    
    {{-- Navigation Toggle --}}
    <button @click="toggleLiveNavigation()" 
            :class="isNavigating ? 'bg-red-500 text-white animate-pulse' : 'bg-white dark:bg-[#2c2923] text-text-light dark:text-text-dark'"
            class="flex items-center justify-center h-12 w-12 rounded-full shadow-lg hover:scale-105 transition-all">
        <span class="material-symbols-outlined" x-text="isNavigating ? 'navigation' : 'near_me'"></span>
    </button>
    
    {{-- My Location --}}
    <button @click="locateUser(null, true)" class="flex items-center justify-center h-12 w-12 bg-primary text-[#171511] rounded-full shadow-lg hover:bg-primary/90 hover:scale-105 transition-all">
        <span class="material-symbols-outlined">my_location</span>
    </button>
</div>

{{-- Legend Overlay (Bottom Left) --}}
<div class="absolute bottom-6 left-6 z-[400] hidden sm:block">
    <div class="bg-white/95 dark:bg-[#2c2923]/95 backdrop-blur-sm p-3 rounded-lg shadow-lg border border-stone-200 dark:border-stone-800">
        <h5 class="text-xs font-bold uppercase tracking-wider text-text-light/40 dark:text-text-dark/40 mb-2 font-display">Legenda</h5>
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-emerald-500/30"></span>
                <span class="text-xs font-medium text-text-light dark:text-text-dark font-sans">Batas Wilayah</span>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div x-show="loading" class="absolute inset-0 bg-white/50 backdrop-blur-[2px] z-[1000] flex items-center justify-center" x-transition.opacity>
    <div class="bg-white p-4 rounded-xl shadow-xl flex items-center gap-3">
        <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
        <span class="text-sm font-bold text-text-light font-display">Memuat Peta...</span>
    </div>
</div>
