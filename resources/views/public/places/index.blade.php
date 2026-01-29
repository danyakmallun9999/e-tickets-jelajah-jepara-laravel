<x-public-layout>
    <div class="bg-background-light dark:bg-background-dark min-h-screen -mt-20 relative overflow-hidden">
        <!-- Decoration Pattern (Batik/Wood Motif style abstract) -->
        <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-primary/10 to-transparent pointer-events-none z-0"></div>
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none z-0"></div>
        <div class="absolute top-40 -left-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="pt-32 pb-20 relative z-10">
            <!-- Main Content Container -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{
                activeCategory: 'Semua',
                places: {{ Js::from($places) }},
                get filteredPlaces() {
                    if (this.activeCategory === 'Semua') return this.places;
                    return this.places.filter(place => place.category && place.category.name === this.activeCategory);
                }
            }">
                
                <!-- Hero Header -->
                <div class="text-center mb-16 relative">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/50 dark:bg-white/5 border border-primary/20 backdrop-blur-sm mb-6 animate-fade-in-up">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-primary font-bold tracking-widest uppercase text-xs">Wonderful Jepara</span>
                    </div>
                    
                    <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-white mb-6 leading-tight tracking-tight">
                        Jelajahi Pesona <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-dark">Bumi Kartini</span>
                    </h1>
                    
                    <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto text-lg leading-relaxed font-light">
                        Temukan keindahan alam yang memukau, jejak sejarah yang agung, dan kekayaan budaya yang tak ternilai di setiap sudut Jepara.
                    </p>
                </div>

                <!-- Elegant Filter Tabs -->
                <div class="flex flex-wrap justify-center gap-3 mb-16 px-4">
                    <button @click="activeCategory = 'Semua'" 
                            class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border"
                            :class="activeCategory === 'Semua' 
                                ? 'bg-slate-800 text-white border-slate-800 shadow-lg shadow-slate-800/20 transform scale-105' 
                                : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-primary/50 hover:text-primary'">
                        Semua
                    </button>
                    @foreach($categories as $category)
                    <button @click="activeCategory = '{{ $category->name }}'" 
                            class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border"
                            :class="activeCategory === '{{ $category->name }}' 
                                ? 'bg-primary text-white border-primary shadow-lg shadow-primary/30 transform scale-105' 
                                : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-primary/50 hover:text-primary'">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>

                <!-- Modern Grid Layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[50vh]">
                    <template x-for="place in filteredPlaces" :key="place.id">
                        <a :href="`/destinasi/${place.slug}`" class="group relative bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-slate-100 dark:border-slate-700 hover:-translate-y-2 flex flex-col h-full">
                            
                            <!-- Image Section -->
                            <div class="relative h-64 overflow-hidden">
                                <template x-if="place.image_path">
                                    <img :src="place.image_path" :alt="place.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </template>
                                <template x-if="!place.image_path">
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-slate-900 text-slate-300">
                                        <span class="material-symbols-outlined text-5xl">image</span>
                                    </div>
                                </template>
                                
                                <!-- Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                                
                                <!-- Category Badge -->
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full bg-white/90 dark:bg-slate-900/90 backdrop-blur text-xs font-bold text-slate-800 dark:text-white shadow-sm border border-white/20" 
                                          x-text="(place.category && place.category.name) ? place.category.name : 'Wisata'">
                                    </span>
                                </div>

                                <!-- Rating Badge -->
                                <div class="absolute bottom-4 right-4 flex items-center gap-1 bg-slate-900/80 backdrop-blur px-2.5 py-1 rounded-lg border border-white/10 text-white font-bold text-xs shadow-lg">
                                    <span class="material-symbols-outlined text-sm text-yellow-400">star</span>
                                    <span x-text="place.rating"></span>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex-1">
                                    <h3 class="text-xl font-display font-bold text-slate-800 dark:text-white mb-2 line-clamp-2 leading-snug group-hover:text-primary transition-colors" x-text="place.name"></h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2 mb-4 leading-relaxed font-light" x-text="place.description">
                                    </p>
                                </div>
                                
                                <!-- Footer Info -->
                                <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-sm">
                                    <!-- Address (Short) -->
                                    <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400 max-w-[60%]">
                                        <span class="material-symbols-outlined text-lg opacity-70">location_on</span>
                                        <span class="truncate" x-text="place.address ? place.address.split(',')[0] : 'Jepara'"></span>
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="font-bold text-primary flex items-center gap-1">
                                        <span class="material-symbols-outlined text-lg">payments</span>
                                        <span x-text="place.ticket_price === 'Gratis' ? 'Gratis' : (place.ticket_price && place.ticket_price.length < 15 ? place.ticket_price : 'Tiket Masuk')"></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </template>
                    
                    <!-- Empty State -->
                    <div x-show="filteredPlaces.length === 0" class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-24">
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <span class="material-symbols-outlined text-4xl">travel_explore</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Tidak ditemukan</h3>
                        <p class="text-slate-500">Coba pilih kategori yang lain.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
