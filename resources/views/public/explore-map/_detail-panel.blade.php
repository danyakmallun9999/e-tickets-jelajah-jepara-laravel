{{-- Detail Slide-Over Panel --}}
<div x-show="selectedFeature" 
     @click.outside="selectedFeature = null"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     class="absolute top-4 right-4 bottom-24 w-80 max-h-[calc(100vh-8rem)] bg-white/95 dark:bg-[#2c2923]/95 backdrop-blur-md rounded-2xl shadow-2xl z-[500] border border-white/50 dark:border-white/10 flex flex-col overflow-hidden"
     x-cloak>
    
    {{-- Header Image --}}
    <div class="h-40 bg-black/10 relative shrink-0">
        <template x-if="selectedFeature?.image_url">
            <img :src="selectedFeature.image_url" class="w-full h-full object-cover">
        </template>
        <template x-if="!selectedFeature?.image_url">
            <div class="w-full h-full flex items-center justify-center text-text-light/20 bg-black/5 dark:bg-white/5">
                <span class="material-symbols-outlined text-4xl">image</span>
            </div>
        </template>
        <button @click="selectedFeature = null" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/20 hover:bg-black/40 text-white backdrop-blur flex items-center justify-center transition">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
        <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/20 text-white backdrop-blur uppercase tracking-wider" x-text="selectedFeature?.type || 'LOKASI'"></span>
            </div>
            <h3 class="text-white font-bold text-xl leading-tight text-shadow font-display" x-text="selectedFeature?.name"></h3>
        </div>
    </div>

    {{-- Content --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar p-5 space-y-4">
        {{-- Description --}}
        <div>
            <h4 class="text-[10px] font-bold text-text-light/40 dark:text-text-dark/40 uppercase tracking-widest mb-1 font-display">Deskripsi</h4>
            <p class="text-xs text-text-light/80 dark:text-text-dark/80 leading-relaxed font-sans" x-text="selectedFeature?.description || 'Tidak ada deskripsi tersedia.'"></p>
        </div>
        
        {{-- Metadata Grid --}}
        <div class="grid grid-cols-2 gap-3">
            <template x-if="selectedFeature?.area">
                <div class="bg-surface-light dark:bg-white/5 p-3 rounded-xl">
                    <p class="text-[10px] uppercase text-text-light/40 dark:text-text-dark/40 font-bold mb-1 font-display">Luas Area</p>
                    <p class="font-bold text-text-light dark:text-text-dark text-base"><span x-text="selectedFeature.area"></span> <span class="text-xs font-normal">ha</span></p>
                </div>
            </template>
            <template x-if="selectedFeature?.owner">
                <div class="bg-surface-light dark:bg-white/5 p-3 rounded-xl">
                    <p class="text-[10px] uppercase text-text-light/40 dark:text-text-dark/40 font-bold mb-1 font-display">Pemilik</p>
                    <p class="font-bold text-text-light dark:text-text-dark text-base" x-text="selectedFeature.owner"></p>
                </div>
            </template>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-2 mt-auto">
             <div class="grid grid-cols-2 gap-2">
                 <button @click="startRouting(selectedFeature)" class="py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                    <span class="material-symbols-outlined text-sm">directions</span> Rute
                </button>
                 <button @click="openGoogleMaps(selectedFeature)" class="py-2.5 bg-white dark:bg-white/10 hover:bg-gray-50 dark:hover:bg-white/20 text-text-light dark:text-text-dark border border-stone-200 dark:border-stone-700 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                    <i class="fa-brands fa-google text-red-500"></i> Maps
                </button>
             </div>
             
             {{-- Toggle Instructions Button (Only visible if route exists) --}}
             <template x-if="routingControl">
                 <button @click="toggleNavigationInstructions()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                    <span class="material-symbols-outlined text-sm">list_alt</span> Lihat Petunjuk Arah
                </button>
             </template>
             
            <button @click="zoomToFeature(selectedFeature)" class="w-full py-2.5 bg-surface-light hover:bg-stone-200 text-text-light rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 transform active:scale-95 font-display">
                <span class="material-symbols-outlined text-sm">my_location</span> Zoom ke Lokasi
            </button>
        </div>
    </div>
</div>
