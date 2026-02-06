{{-- Detail Slide-Over Panel --}}
<div x-show="selectedFeature" 
     @click.outside="selectedFeature = null"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     class="absolute top-4 right-4 bottom-24 w-80 max-h-[calc(100vh-8rem)] bg-white/98 dark:bg-slate-800/98 backdrop-blur-md rounded-2xl z-[500] border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden"
     x-cloak>
    
    {{-- Header Image --}}
    <div class="h-40 bg-slate-100 dark:bg-slate-700 relative shrink-0">
        <template x-if="selectedFeature?.image_url">
            <img :src="selectedFeature.image_url" class="w-full h-full object-cover">
        </template>
        <template x-if="!selectedFeature?.image_url">
            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-500 bg-slate-100 dark:bg-slate-700">
                <span class="material-symbols-outlined text-4xl">image</span>
            </div>
        </template>
        <button @click="selectedFeature = null" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/20 hover:bg-black/40 text-white backdrop-blur flex items-center justify-center transition">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
        <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-slate-900/90 via-slate-900/50 to-transparent">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-sky-500/80 text-white backdrop-blur uppercase tracking-wider" x-text="selectedFeature?.type || 'LOKASI'"></span>
            </div>
            <h3 class="text-white font-bold text-xl leading-tight text-shadow font-display" x-text="selectedFeature?.name"></h3>
        </div>
    </div>

    {{-- Content --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar p-5 space-y-4">
        {{-- Description --}}
        <div>
            <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1 font-display">Deskripsi</h4>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-sans" x-text="selectedFeature?.description || 'Tidak ada deskripsi tersedia.'"></p>
        </div>
        
        {{-- Metadata Grid --}}
        <div class="grid grid-cols-2 gap-3">
            <template x-if="selectedFeature?.area">
                <div class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-xl">
                    <p class="text-[10px] uppercase text-slate-400 dark:text-slate-500 font-bold mb-1 font-display">Luas Area</p>
                    <p class="font-bold text-slate-800 dark:text-white text-base"><span x-text="selectedFeature.area"></span> <span class="text-xs font-normal">ha</span></p>
                </div>
            </template>
            <template x-if="selectedFeature?.owner">
                <div class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-xl">
                    <p class="text-[10px] uppercase text-slate-400 dark:text-slate-500 font-bold mb-1 font-display">Pemilik</p>
                    <p class="font-bold text-slate-800 dark:text-white text-base" x-text="selectedFeature.owner"></p>
                </div>
            </template>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-2 mt-auto">
             <div class="grid grid-cols-2 gap-2">
                 <button @click="startRouting(selectedFeature)" class="py-2.5 bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                    <span class="material-symbols-outlined text-sm">directions</span> Rute
                </button>
                 <button @click="openGoogleMaps(selectedFeature)" class="py-2.5 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                    <i class="fa-brands fa-google text-red-500"></i> Maps
                </button>
             </div>
             
             {{-- Toggle Instructions Button (Only visible if route exists) --}}
             <template x-if="routingControl">
                 <button @click="toggleNavigationInstructions()" class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                    <span class="material-symbols-outlined text-sm">list_alt</span> Lihat Petunjuk Arah
                </button>
             </template>
             
            <button @click="zoomToFeature(selectedFeature)" class="w-full py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-white rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                <span class="material-symbols-outlined text-sm">my_location</span> Zoom ke Lokasi
            </button>
        </div>
    </div>
</div>
