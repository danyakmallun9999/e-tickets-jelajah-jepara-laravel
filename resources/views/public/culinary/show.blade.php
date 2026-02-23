<x-public-layout>
    @push('seo')
        <x-seo 
            :title="$culinary->name . ' - Kuliner Jepara'"
            :description="Str::limit(strip_tags($culinary->full_description ?? $culinary->description), 150)"
            :image="$culinary->image_url ? $culinary->image_url : asset('images/logo-kura.png')"
            type="article"
        />
    @endpush

    <div class="bg-white dark:bg-slate-950 min-h-screen font-sans -mt-20 pt-24 lg:pt-20">
        
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row">
            
            <!-- Left Side: Sticky Visuals (50%) -->
            <div class="lg:w-1/2 lg:h-screen lg:sticky lg:top-0 relative bg-white dark:bg-slate-950 z-10 group flex flex-col lg:overflow-hidden p-4 lg:pl-16 lg:pr-8 lg:pt-12"
                 @php
                     $uniqueGalleryImages = collect([]);
                     if ($culinary->image_url) {
                         $uniqueGalleryImages->push($culinary->image_url);
                     }
                     if (isset($culinary->images)) {
                         foreach($culinary->images as $img) {
                             $uniqueGalleryImages->push($img->image_url ?? asset('storage/' . $img->image));
                         }
                     }
                     $uniqueGalleryImages = $uniqueGalleryImages->unique()->values();
                 @endphp
                 x-data="{ 
                    activeImage: '{{ $uniqueGalleryImages->first() }}',
                    isFlipping: false,
                    lightboxOpen: false,
                    lightboxIndex: 0,
                    images: [
                        @foreach($uniqueGalleryImages as $imgPath)
                            '{{ $imgPath }}',
                        @endforeach
                    ],
                    changeImage(url) {
                        if (this.activeImage === url) return;
                        this.isFlipping = true;
                        setTimeout(() => {
                            this.activeImage = url;
                            this.isFlipping = false;
                        }, 300);
                    },
                    openLightbox(url) {
                        this.lightboxIndex = this.images.indexOf(url);
                        if (this.lightboxIndex === -1) this.lightboxIndex = 0;
                        this.lightboxOpen = true;
                        document.body.style.overflow = 'hidden';
                    },
                    closeLightbox() {
                        this.lightboxOpen = false;
                        document.body.style.overflow = '';
                    },
                    lightboxPrev() {
                        this.lightboxIndex = (this.lightboxIndex - 1 + this.images.length) % this.images.length;
                    },
                    lightboxNext() {
                        this.lightboxIndex = (this.lightboxIndex + 1) % this.images.length;
                    }
                 }"
                 @keydown.escape.window="if (lightboxOpen) closeLightbox()"
                 @keydown.left.window="if (lightboxOpen) lightboxPrev()"
                 @keydown.right.window="if (lightboxOpen) lightboxNext()">
                 
                 <!-- Breadcrumbs -->
                 <div class="mb-6">
                     <nav class="flex" aria-label="Breadcrumb">
                         <ol class="inline-flex items-center space-x-1 md:space-x-3">
                             <li class="inline-flex items-center">
                                 <a href="{{ route('welcome') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white transition-colors">
                                     <span class="material-symbols-outlined text-lg mr-1">home</span>
                                     {{ __('Nav.Home') }}
                                 </a>
                             </li>
                             <li>
                                 <div class="flex items-center">
                                     <span class="material-symbols-outlined text-slate-400 mx-1">chevron_right</span>
                                     <a href="{{ route('culture.index') }}" wire:navigate class="text-sm font-medium text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white transition-colors">
                                         {{ __('Nav.Culture') }}
                                     </a>
                                 </div>
                             </li>
                             <li aria-current="page">
                                 <div class="flex items-center">
                                     <span class="material-symbols-outlined text-slate-400 mx-1">chevron_right</span>
                                     <span class="text-sm font-medium text-slate-900 dark:text-white line-clamp-1 max-w-[150px] md:max-w-xs">
                                         {{ $culinary->name }}
                                     </span>
                                 </div>
                             </li>
                         </ol>
                     </nav>
                 </div>

                 <!-- Main Image Area -->
                 <div class="relative w-full aspect-[4/3] lg:aspect-auto lg:h-[60vh] overflow-hidden perspective-[1000px]">
                     <div class="relative w-full h-full rounded-3xl overflow-hidden text-transparent cursor-pointer bg-slate-100 dark:bg-slate-900" @click="openLightbox(activeImage)">
                         <template x-if="activeImage">
                             <img :src="activeImage" 
                                  alt="{{ $culinary->name }}" 
                                  class="w-full h-full object-cover transition-all duration-500 ease-in-out transform origin-center"
                                  :class="isFlipping ? '[transform:rotateY(90deg)] opacity-75 scale-95' : '[transform:rotateY(0deg)] opacity-100 scale-100'">
                         </template>
                         <template x-if="!activeImage">
                             <div class="w-full h-full flex items-center justify-center text-slate-400">
                                 <span class="material-symbols-outlined text-6xl">restaurant_menu</span>
                             </div>
                         </template>
                         <!-- Zoom hint overlay -->
                         <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                             <div class="bg-white/90 backdrop-blur px-4 py-2 rounded-full font-bold text-sm text-slate-700 shadow-lg flex items-center gap-2 transform translate-y-4 group-hover:translate-y-0 transition-all">
                                 <span class="material-symbols-outlined text-base">zoom_in</span>
                                 Lihat Foto
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Thumbnails / Gallery List -->
                 <div class="w-full px-4 lg:px-6 pb-6 pt-3 flex items-center gap-3 overflow-x-auto scrollbar-hide scroll-smooth">
                     @foreach($uniqueGalleryImages as $imgPath)
                         <button @click="changeImage('{{ $imgPath }}')" 
                                 :class="activeImage === '{{ $imgPath }}' ? 'ring-2 ring-primary scale-105' : 'opacity-70 hover:opacity-100'"
                                 class="relative w-20 h-14 lg:w-24 lg:h-16 flex-shrink-0 rounded-xl overflow-hidden transition-all duration-300">
                             <img src="{{ $imgPath }}" class="w-full h-full object-cover">
                         </button>
                     @endforeach
                 </div>

                 <!-- Lightbox Modal -->
                 <template x-teleport="body">
                     <div x-show="lightboxOpen" x-cloak
                          x-transition:enter="transition ease-out duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100"
                          x-transition:leave-end="opacity-0"
                          class="fixed inset-0 z-[9999] flex items-center justify-center">
                         
                         <!-- Backdrop (click to close) -->
                         <div class="absolute inset-0 bg-black/95 backdrop-blur-sm" @click="closeLightbox()"></div>
                         
                         <!-- Close Button -->
                         <button @click="closeLightbox()" 
                                 class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm flex items-center justify-center text-white transition-all duration-200 hover:scale-110">
                             <span class="material-symbols-outlined text-2xl">close</span>
                         </button>

                         <!-- Image Counter -->
                         <div class="absolute top-4 left-4 sm:top-6 sm:left-6 z-20 bg-white/10 backdrop-blur-sm text-white text-sm font-medium px-4 py-2 rounded-full">
                             <span x-text="(lightboxIndex + 1) + ' / ' + images.length"></span>
                         </div>

                         <!-- Prev Button -->
                         <button x-show="images.length > 1" @click="lightboxPrev()" 
                                 class="absolute left-2 sm:left-6 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm flex items-center justify-center text-white transition-all duration-200 hover:scale-110">
                             <span class="material-symbols-outlined text-2xl">chevron_left</span>
                         </button>

                         <!-- Next Button -->
                         <button x-show="images.length > 1" @click="lightboxNext()" 
                                 class="absolute right-2 sm:right-6 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm flex items-center justify-center text-white transition-all duration-200 hover:scale-110">
                             <span class="material-symbols-outlined text-2xl">chevron_right</span>
                         </button>

                         <!-- Lightbox Image -->
                         <div class="relative z-10 w-full h-full max-w-[90vw] max-h-[80vh] flex items-center justify-center pointer-events-none">
                             <img :src="images[lightboxIndex]" 
                                  :alt="'{{ $culinary->name }} - Foto ' + (lightboxIndex + 1)"
                                  class="w-auto h-auto max-w-full max-h-full object-contain rounded-lg shadow-2xl select-auto pointer-events-auto">
                         </div>

                         <!-- Thumbnail Strip -->
                         <div x-show="images.length > 1" class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 bg-black/40 backdrop-blur-sm rounded-2xl p-2 max-w-[90vw] overflow-x-auto scrollbar-hide">
                             <template x-for="(img, idx) in images" :key="idx">
                                 <button @click="lightboxIndex = idx" 
                                         :class="lightboxIndex === idx ? 'ring-2 ring-white scale-110 opacity-100' : 'opacity-50 hover:opacity-80'"
                                         class="w-14 h-10 sm:w-16 sm:h-11 flex-shrink-0 rounded-lg overflow-hidden transition-all duration-200">
                                     <img :src="img" class="w-full h-full object-cover">
                                 </button>
                             </template>
                         </div>
                     </div>
                 </template>
            </div>

            <!-- Right Side: Scrollable Content (50%) -->
            <div class="lg:w-1/2 relative bg-white dark:bg-slate-950">
                <main class="max-w-3xl mx-auto px-5 sm:px-8 py-10 md:py-16 lg:px-16 lg:pt-12 lg:pb-24">
                    
                    <!-- Top Meta: Category & Rating -->
                    <div class="flex flex-wrap items-center gap-3 mb-6 animate-fade-in-up">
                        <span class="px-3 py-1 rounded-full bg-primary/5 dark:bg-primary/10 text-primary dark:text-primary font-bold uppercase tracking-wider text-xs border border-primary/20 dark:border-primary/20">
                            {{ __('Culinary.Detail.Badge') }}
                        </span>
                    </div>

                    <!-- Header Section -->
                    <div class="mb-10 animate-fade-in-up delay-100">
                        <h1 class="font-playfair text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white leading-[1.2] md:leading-tight mb-6">
                            {{ $culinary->name }}
                        </h1>
                        
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-lg">
                            <span class="material-symbols-outlined text-xl flex-shrink-0">restaurant_menu</span>
                            <span class="font-light">{{ __('Culinary.Detail.Subtitle') }}</span>
                        </div>
                    </div>

                    <!-- Horizontal Divider -->
                    <hr class="border-slate-100 dark:border-slate-800 mb-10">

                    <!-- Content Body -->
                    <div class="space-y-12 animate-fade-in-up delay-200">
                        
                         <!-- Description -->
                         <section>
                             <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-4 flex items-center gap-3">
                                 <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                                 {{ __('Culinary.Detail.About') }}
                             </h3>
                             <div x-data="{ expanded: false }">
                                 <div class="prose prose-lg prose-slate dark:prose-invert font-light text-slate-600 dark:text-slate-300 leading-relaxed text-justify transition-all duration-300"
                                      :class="expanded ? '' : 'line-clamp-4 mask-image-b'">
                                     <p class="whitespace-pre-line">{{ trim($culinary->content ?? $culinary->description) }}</p>
                                 </div>
                                 @if(strlen($culinary->content ?? $culinary->description) > 300)
                                     <button @click="expanded = !expanded" 
                                             class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-primary dark:text-primary hover:text-primary/80 dark:hover:text-primary/80 transition-colors">
                                         <span x-text="expanded ? '{{ __('Culinary.Detail.Hide') ?? 'Lebih Sedikit' }}' : '{{ __('News.Button.ReadMore') ?? 'Baca Selengkapnya' }}'"></span>
                                         <span class="material-symbols-outlined text-lg transition-transform duration-300" 
                                               :class="expanded ? 'rotate-180' : ''">expand_more</span>
                                     </button>
                                 @endif
                             </div>
                         </section>

                        <!-- Highlights / Quick Info Grid -->
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 hover:border-primary/30 dark:hover:border-primary/30 transition-colors">
                                <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">{{ __('Culinary.Detail.Recommendation') }}</div>
                                <div class="text-slate-900 dark:text-white font-semibold flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 dark:bg-primary/20 flex-shrink-0 flex items-center justify-center text-primary dark:text-primary">
                                        <span class="material-symbols-outlined text-xl">star</span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1 italic text-slate-800 dark:text-slate-200">
                                        "{{ $culinary->description }}"
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Area -->
                    <div class="mt-20 pt-10 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-slate-400 text-sm font-serif italic">{{ __('Culinary.Detail.ShareLabel') }}</span>
                        <x-share-modal :url="request()->url()" :title="$culinary->name" :text="Str::limit(strip_tags($culinary->description), 100)">
                            <button class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all duration-300 shadow-sm hover:shadow-md" title="{{ __('Culinary.Detail.ShareButton') }}">
                                <i class="fa-solid fa-share-nodes"></i>
                            </button>
                        </x-share-modal>
                    </div>

                </main>
            </div>

            </div>
        </div>
    </div>

    <!-- Dedicated Map & Locations Section -->
    @if($culinary->locations->isNotEmpty())
        @php
            $hasCoordinates = $culinary->locations->whereNotNull('latitude')->whereNotNull('longitude')->isNotEmpty();
            $mapLocations = $culinary->locations->whereNotNull('latitude')->whereNotNull('longitude')->values();
        @endphp

        <section class="bg-slate-50 dark:bg-slate-900/50 py-16 lg:py-24 border-t border-slate-200 dark:border-slate-800 relative z-0">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <!-- Section Header -->
                <div class="max-w-3xl mx-auto text-center mb-12 animate-fade-in-up">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 dark:bg-blue-900/30 text-primary dark:text-blue-400 mb-6">
                        <span class="material-symbols-outlined text-3xl">storefront</span>
                    </div>
                    <h2 class="font-playfair text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                        {{ __('Culinary.Detail.WantToTry') ?? 'Lokasi Terkait' }}
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400">
                        {{ __('Culinary.Detail.FindNearby', ['name' => $culinary->name]) ?? "Temukan cabang atau lokasi terkait dari $culinary->name di bawah ini:" }}
                    </p>
                </div>

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Map (Left on Large Screens) -->
                    @if($hasCoordinates)
                        <div class="w-full lg:w-3/5 xl:w-2/3 h-[400px] lg:h-[600px] rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-xl relative group bg-white dark:bg-slate-800 animate-fade-in-up delay-100" x-data="culinaryMap('{{ htmlspecialchars($mapLocations->toJson(), ENT_QUOTES, 'UTF-8') }}')">
                            <div id="culinary-map" class="w-full h-full z-0"></div>
                            
                            <!-- Loading overlay -->
                            <div x-show="loading" x-transition.opacity.duration.300ms class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm z-10">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Memuat Peta...</span>
                                </div>
                            </div>
                        </div>
                    @endempty

                    <!-- Locations Grid/List (Right on Large Screens) -->
                    <div class="w-full {{ $hasCoordinates ? 'lg:w-2/5 xl:w-1/3' : 'lg:w-full' }} flex flex-col h-[600px] animate-fade-in-up delay-200">
                        <!-- Fade Out Top/Bottom Effect Container -->
                        <div class="relative flex-1 overflow-hidden rounded-2xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col">
                            <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 backdrop-blur z-10">
                                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-1.5 h-5 bg-primary rounded-full"></span>
                                    Daftar Cabang ({{ $culinary->locations->count() }})
                                </h3>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-700 p-6 space-y-4 relative z-0">
                                @if(!$hasCoordinates)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @endif

                                @foreach($culinary->locations as $location)
                                    <div class="flex flex-col p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-md group">
                                        <div class="flex items-start gap-4 mb-3">
                                            <div class="mt-1 w-12 h-12 rounded-full bg-primary/10 dark:bg-primary/20 flex flex-shrink-0 items-center justify-center text-primary dark:text-primary transition-transform group-hover:scale-110">
                                                <span class="material-symbols-outlined text-2xl">location_on</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-slate-900 dark:text-white text-base truncate">{{ $location->name }}</h4>
                                                @if($location->address)
                                                    <p class="text-slate-500 text-sm leading-relaxed mt-1 line-clamp-2">{{ $location->address }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($location->google_maps_url || ($location->latitude && $location->longitude))
                                            <div class="mt-2 text-right">
                                                <a href="{{ $location->google_maps_url ?: 'https://www.google.com/maps/dir/?api=1&destination=' . $location->latitude . ',' . $location->longitude }}" target="_blank" 
                                                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-primary hover:text-white hover:border-primary dark:hover:bg-primary dark:hover:text-white transition-all text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm w-full">
                                                    <span class="material-symbols-outlined text-lg">directions</span>
                                                    Petunjuk Arah
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                @if(!$hasCoordinates)
                                    </div>
                                @endif
                                
                                <!-- Bottom fade spacing -->
                                <div class="h-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Leaflet & Custom Map Scripts -->
    @if(isset($hasCoordinates) && $hasCoordinates)
        @push('styles')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
            <style>
                /* Apple HIG Style Map Customizations */
                .leaflet-control-attribution,
                .leaflet-control-zoom {
                    display: none !important;
                }
                .apple-marker-container {
                    position: relative;
                    width: 36px;
                    height: 44px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    animation: markerDrop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                }
                .apple-marker-icon {
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    background-color: #0ea5e9; /* primary */
                    border: 3px solid white;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    z-index: 2;
                    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                }
                .apple-marker-pointer {
                    width: 0;
                    height: 0;
                    border-left: 6px solid transparent;
                    border-right: 6px solid transparent;
                    border-top: 10px solid #0ea5e9;
                    margin-top: -3px;
                    z-index: 1;
                }
                .apple-marker-container:hover .apple-marker-icon {
                    transform: scale(1.15);
                }
                @keyframes markerDrop {
                    0% { opacity: 0; transform: translateY(-20px) scale(0.8); }
                    100% { opacity: 1; transform: translateY(0) scale(1); }
                }
                
                /* Sleek Popup */
                .leaflet-popup-content-wrapper {
                    border-radius: 16px;
                    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
                    padding: 4px;
                    background-color: rgba(255,255,255,0.98);
                    backdrop-filter: blur(10px);
                }
                .dark .leaflet-popup-content-wrapper {
                    background-color: rgba(30,41,59,0.95);
                    color: #f8fafc;
                }
                .dark .leaflet-popup-tip {
                    background: rgba(30,41,59,0.95);
                }
                .leaflet-popup-content {
                    margin: 14px;
                    line-height: 1.4;
                }
                .leaflet-container a.leaflet-popup-close-button {
                    color: #94a3b8;
                    padding: 6px;
                    border-radius: 50%;
                    top: 10px;
                    right: 10px;
                    width: 24px;
                    height: 24px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #f1f5f9;
                    transition: all 0.2s;
                }
                .dark .leaflet-container a.leaflet-popup-close-button {
                    background: #334155;
                }
                .leaflet-container a.leaflet-popup-close-button:hover {
                    background: #e2e8f0;
                    color: #475569;
                }
                .dark .leaflet-container a.leaflet-popup-close-button:hover {
                    background: #475569;
                    color: #f1f5f9;
                }
            </style>
        @endpush
        @push('scripts')
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
            <script>
                function culinaryMap(locationsJson) {
                    return {
                        map: null,
                        loading: true,
                        locations: [],
                        
                        init() {
                            try {
                                const txt = document.createElement("textarea");
                                txt.innerHTML = locationsJson;
                                this.locations = JSON.parse(txt.value);
                                
                                if (this.locations.length > 0) {
                                    setTimeout(() => this.initMap(), 100);
                                } else {
                                    this.loading = false;
                                }
                            } catch (e) {
                                console.error('Error parsing locations', e);
                                this.loading = false;
                            }
                        },
                        
                        initMap() {
                            const mapEl = document.getElementById('culinary-map');
                            if (!mapEl) return;
                            
                            if (mapEl._leaflet_id) {
                                mapEl._leaflet_id = null;
                            }
                            
                            this.map = L.map('culinary-map', {
                                zoomControl: false,
                                scrollWheelZoom: false
                            });
                            
                            const isDarkMode = document.documentElement.classList.contains('dark');
                            const tileUrl = isDarkMode 
                                ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                                : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
                                
                            L.tileLayer(tileUrl, {
                                maxZoom: 20,
                                attribution: '&copy; CARTO'
                            }).addTo(this.map);
                            
                            const markers = [];
                            const bounds = L.latLngBounds();
                            
                            this.locations.forEach(loc => {
                                const lat = parseFloat(loc.latitude);
                                const lng = parseFloat(loc.longitude);
                                
                                if (isNaN(lat) || isNaN(lng)) return;
                                
                                const markerIcon = L.divIcon({
                                    html: `
                                        <div class="apple-marker-container">
                                            <div class="apple-marker-icon">
                                                <span class="material-symbols-outlined" style="font-size:18px;">restaurant</span>
                                            </div>
                                            <div class="apple-marker-pointer"></div>
                                        </div>
                                    `,
                                    className: '',
                                    iconSize: [36, 44],
                                    iconAnchor: [18, 44],
                                    popupAnchor: [0, -42]
                                });
                                
                                const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(this.map);
                                
                                const mapsLink = loc.google_maps_url ? loc.google_maps_url : `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
                                const addressHtml = loc.address ? `<p style="font-size:12px; color:#64748b; margin:6px 0 10px 0; max-width:200px; line-height: 1.3;" class="dark:text-slate-400">${loc.address}</p>` : '<div style="height:12px;"></div>';
                                
                                const popupHtml = `
                                    <div style="min-width: 180px; padding: 4px;">
                                        <h4 style="font-weight: 800; font-size: 15px; margin: 0; font-family: inherit;" class="text-slate-900 dark:text-white">${loc.name}</h4>
                                        ${addressHtml}
                                        <a href="${mapsLink}" target="_blank" style="display:inline-flex; align-items:center; justify-content:center; gap:6px; background:#0ea5e9; color:white; padding:8px 14px; border-radius:12px; font-weight:700; font-size:12px; width: 100%; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#0284c7'" onmouseout="this.style.background='#0ea5e9'">
                                            <span class="material-symbols-outlined" style="font-size:16px;">directions</span> Petunjuk Arah
                                        </a>
                                    </div>
                                `;
                                
                                marker.bindPopup(popupHtml);
                                bounds.extend([lat, lng]);
                                markers.push(marker);
                            });
                            
                            this.map.on('focus', () => { this.map.scrollWheelZoom.enable(); });
                            this.map.on('blur', () => { this.map.scrollWheelZoom.disable(); });
                            
                            setTimeout(() => {
                                this.loading = false;
                                
                                if (markers.length > 0) {
                                    this.map.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
                                    setTimeout(() => this.map.invalidateSize(), 300);
                                }
                            }, 500); 
                        }
                    };
                }
            </script>
        @endpush
    @endif
</x-public-layout>
