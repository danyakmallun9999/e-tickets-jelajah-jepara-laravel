{{-- Proximity Alert Modal --}}
<div x-show="nearbyAlert" 
     class="fixed inset-0 z-[1000] flex items-end sm:items-center justify-center p-4 sm:p-0"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
     x-cloak>
    
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="nearbyAlert = null"></div>

    <div class="relative bg-white dark:bg-[#2c2923] rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-white/20">
        {{-- Image Header --}}
        <div class="h-32 bg-gray-200 relative">
            <template x-if="nearbyAlert?.image_url">
                <img :src="nearbyAlert.image_url" class="w-full h-full object-cover">
            </template>
             <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-4">
                 <div>
                     <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1 block">Di Sekitar Anda</span>
                     <h3 class="text-white font-bold text-xl leading-tight" x-text="nearbyAlert?.name"></h3>
                 </div>
             </div>
        </div>
        
        <div class="p-5">
            <p class="text-text-light/80 dark:text-text-dark/80 text-sm mb-6">
                Anda berada dalam jarak 500m dari lokasi ini. Ingin melihat detailnya?
            </p>
            
            <div class="flex gap-3">
                <button @click="nearbyAlert = null" class="flex-1 px-4 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-text-light dark:text-text-dark text-sm font-bold hover:bg-stone-50 dark:hover:bg-white/5 transition">
                    Tutup
                </button>
                <button @click="selectPlace({ properties: nearbyAlert, geometry: { coordinates: [0,0] } }); nearbyAlert = null;" class="flex-1 px-4 py-2 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-dark shadow-lg shadow-primary/20 transition">
                    Lihat Detail
                </button>
            </div>
        </div>
    </div>
</div>
