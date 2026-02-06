{{-- Map Controls --}}

{{-- Floating Controls (Top Right) - Layer Toggle --}}
<div class="absolute top-4 right-4 flex flex-col gap-2 z-[400]">
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center justify-center h-10 w-10 bg-white dark:bg-slate-800 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-sky-500 transition-colors border border-slate-200 dark:border-slate-700">
            <span class="material-symbols-outlined">layers</span>
        </button>
        {{-- Dropdown --}}
        <div x-show="open" @click.outside="open = false" class="absolute top-0 right-12 w-48 bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700" x-cloak x-transition>
             <p class="text-[10px] font-bold uppercase text-slate-400 mb-2 font-display">Peta Dasar</p>
             <div class="flex gap-1">
                 <button @click="setBaseLayer('streets')" :class="currentBaseLayer === 'streets' ? 'bg-sky-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600'" class="flex-1 py-1.5 text-[10px] rounded-lg font-bold font-display transition-colors">Jalan</button>
                 <button @click="setBaseLayer('satellite')" :class="currentBaseLayer === 'satellite' ? 'bg-sky-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600'" class="flex-1 py-1.5 text-[10px] rounded-lg font-bold font-display transition-colors">Satelit</button>
             </div>
        </div>
    </div>
</div>

{{-- Floating Controls (Bottom Right) - Zoom & Navigation --}}
<div class="absolute bottom-6 right-6 flex flex-col gap-3 z-[400]">
    {{-- Zoom Controls --}}
    <div class="flex flex-col rounded-xl overflow-hidden bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700 border border-slate-200 dark:border-slate-700">
        <button @click="map.zoomIn()" class="flex items-center justify-center h-10 w-10 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-sky-500 active:bg-slate-100 transition-colors">
            <span class="material-symbols-outlined">add</span>
        </button>
        <button @click="map.zoomOut()" class="flex items-center justify-center h-10 w-10 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-sky-500 active:bg-slate-100 transition-colors">
            <span class="material-symbols-outlined">remove</span>
        </button>
    </div>
    
    {{-- Navigation Toggle --}}
    <button @click="toggleLiveNavigation()" 
            :class="isNavigating ? 'bg-red-500 text-white animate-pulse' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-sky-500 border border-slate-200 dark:border-slate-700'"
            class="flex items-center justify-center h-12 w-12 rounded-full hover:scale-105 transition-all">
        <span class="material-symbols-outlined" x-text="isNavigating ? 'navigation' : 'near_me'"></span>
    </button>
    
    {{-- My Location --}}
    <button @click="locateUser(null, true)" class="flex items-center justify-center h-12 w-12 bg-gradient-to-r from-sky-500 to-cyan-500 text-white rounded-full hover:from-sky-600 hover:to-cyan-600 hover:scale-105 transition-all">
        <span class="material-symbols-outlined">my_location</span>
    </button>
</div>

{{-- Legend Overlay (Bottom Left) --}}
<div class="absolute bottom-6 left-6 z-[400] hidden sm:block">
    <div class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm p-3 rounded-xl border border-slate-200 dark:border-slate-700">
        <h5 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2 font-display">Legenda</h5>
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-emerald-500/30"></span>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300 font-sans">Batas Wilayah</span>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div x-show="loading" class="absolute inset-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-[2px] z-[1000] flex items-center justify-center" x-transition.opacity>
    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl flex items-center gap-3 border border-slate-200 dark:border-slate-700">
        <div class="w-6 h-6 border-2 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
        <span class="text-sm font-bold text-slate-700 dark:text-white font-display">Memuat Peta...</span>
    </div>
</div>
