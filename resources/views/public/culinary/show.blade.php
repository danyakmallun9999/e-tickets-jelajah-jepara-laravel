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
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 dark:bg-blue-900/30 text-primary dark:text-blue-400 mb-6 relative group overflow-hidden">
                            <!-- Subtle ping animation behind icon -->
                            <div class="absolute inset-0 bg-primary/20 rounded-full animate-ping opacity-75"></div>
                            <span class="material-symbols-outlined text-3xl relative z-10">storefront</span>
                        </div>
                        <h2 class="font-playfair text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-6">
                            {{ __('Culinary.Detail.WantToTry') ?? 'Lokasi Terkait' }}
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400 text-lg mb-8 max-w-2xl mx-auto">
                            {{ __('Culinary.Detail.FindNearby', ['name' => $culinary->name]) ?? "Temukan cabang atau lokasi terkait dari $culinary->name di bawah ini:" }}
                        </p>

                        <!-- Distance Filter Action -->
                        <div class="flex justify-center">
                            <button @click="calculateDistances()" 
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 hover:border-primary dark:hover:border-primary text-slate-700 dark:text-slate-200 font-semibold shadow-sm hover:shadow-lg hover:text-primary transition-all duration-300 transform hover:-translate-y-1 active:translate-y-0 group relative overflow-hidden"
                                    :class="isCalculatingLocation ? 'opacity-70 cursor-wait' : ''">
                                <!-- Loading Spinner inline -->
                                <span x-show="isCalculatingLocation" class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
                                <span x-show="!isCalculatingLocation" class="material-symbols-outlined text-xl transition-transform group-hover:scale-110">my_location</span>
                                <span x-text="isCalculatingLocation ? 'Mendeteksi Lokasi...' : (userLocation ? 'Perbarui Lokasi Saya' : 'Urutkan dari Terdekat')">Urutkan dari Terdekat</span>
                                
                                <!-- Shine effect -->
                                <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/40 dark:via-white/10 to-transparent group-hover:animate-shimmer"></div>
                            </button>
                        </div>
                    </div>

                <div class="flex flex-col lg:flex-row gap-8" x-data="culinaryMap('{{ htmlspecialchars($mapLocations->toJson(), ENT_QUOTES, 'UTF-8') }}')">
                    
                    <!-- Fullscreen Teleport Modal Container -->
                    <template x-teleport="body">
                        <div x-show="isFullscreen" 
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-[99999] bg-slate-900/90 backdrop-blur-sm flex flex-col p-4 md:p-8">
                             
                             <!-- Modal Header Toolbar (Solid - No Glass) -->
                             <div class="flex justify-between items-center mb-3 sm:mb-4 px-3 sm:px-4 bg-slate-800 rounded-2xl border border-slate-700 p-2 sm:p-3 shadow-md">
                                 <div class="flex items-center gap-3">
                                     <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary hidden sm:flex">
                                        <span class="material-symbols-outlined">map</span>
                                     </div>
                                     <div>
                                        <h3 class="font-bold text-white text-base sm:text-lg leading-tight truncate max-w-[200px] sm:max-w-md">{{ $culinary->name }}</h3>
                                        <p class="text-slate-400 text-xs">Peta Interaktif Layar Penuh</p>
                                     </div>
                                 </div>

                                 <!-- Clear Text Button to Close -->
                                 <button @click="toggleFullscreen()" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white transition-all font-bold shadow-lg">
                                     <span class="material-symbols-outlined text-xl">close</span>
                                     <span class="text-sm hidden sm:inline">Tutup Peta (Esc)</span>
                                     <span class="text-sm sm:hidden">Tutup</span>
                                 </button>
                             </div>

                             <!-- Modal Map Host -->
                             <div id="modal-map-host" class="w-full flex-1 rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl relative border border-white/10 bg-slate-800">
                                 <!-- Floating Close Button overlaid directly on Map -->
                                 <button @click.prevent.stop="toggleFullscreen()" 
                                         class="absolute top-4 right-4 z-[400] flex items-center gap-2 px-4 py-2.5 rounded-full bg-white/95 dark:bg-slate-900/90 backdrop-blur shadow-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-600 dark:hover:border-red-500 dark:hover:text-white transition-all group focus:outline-none">
                                     <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">fullscreen_exit</span>
                                     <span class="font-bold text-sm">Kembali</span>
                                 </button>

                                 <!-- The actual map DOM moves here when fullscreen -->
                             </div>
                        </div>
                    </template>

                    <!-- Map (Left on Large Screens - Normal View) -->
                    @if($hasCoordinates)
                        <div class="w-full lg:w-3/5 xl:w-2/3 h-[400px] lg:h-[600px] rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-xl relative group bg-white dark:bg-slate-800 animate-fade-in-up delay-100">
                            
                            <!-- Inline Map Host -->
                            <div id="inline-map-host" class="w-full h-full relative z-0">
                                <div id="culinary-map" class="w-full h-full z-0 absolute inset-0"></div>
                            </div>
                            
                            <!-- Fullscreen Toggle Button (Apple HIG Float) -->
                            <button @click.prevent.stop="toggleFullscreen()" 
                                    class="absolute top-4 right-4 z-[400] w-12 h-12 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-lg border border-slate-200/50 dark:border-slate-700/50 flex items-center justify-center text-slate-700 dark:text-slate-200 hover:bg-primary hover:text-white dark:hover:bg-primary transition-all duration-300 hover:scale-105 active:scale-95 group/btn focus:outline-none"
                                    title="Peta Layar Penuh">
                                <span class="material-symbols-outlined text-2xl transition-transform group-hover/btn:scale-110">fullscreen</span>
                            </button>

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
                            <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 backdrop-blur z-10 flex items-center justify-between">
                                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-1.5 h-5 bg-primary rounded-full"></span>
                                    Daftar Cabang (<span x-text="locations.length"></span>)
                                </h3>
                                <span x-show="userLocation" style="display:none;" class="px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-bold border border-green-200 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Terurut
                                </span>
                            </div>
                            
                            <!-- Provide an ID for programmatic scrolling -->
                            <div id="locations-scroll-container" class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-700 p-6 space-y-4 relative z-0 scroll-smooth">
                                @if(!$hasCoordinates)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @endif

                                <!-- Rendered dynamically by Alpine so we can sort them by distance -->
                                <template x-for="(loc, index) in locations" :key="loc.id || index">
                                    <div :id="'card-' + index"
                                         @click="focusLocation(index)"
                                         class="flex flex-col p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-md cursor-pointer group"
                                         :class="activeLocation === index ? 'ring-2 ring-primary border-transparent dark:border-transparent bg-primary/5 dark:bg-primary/10 scale-[1.02] shadow-md z-10' : 'hover:border-primary/50'">
                                        <div class="flex items-start gap-4 mb-3">
                                            <div class="mt-1 w-12 h-12 rounded-full flex flex-shrink-0 items-center justify-center transition-transform group-hover:scale-110"
                                                 :class="activeLocation === index ? 'bg-primary text-white shadow-md' : 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary'">
                                                <span class="material-symbols-outlined text-2xl">location_on</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2">
                                                    <h4 class="font-bold text-base truncate transition-colors"
                                                        :class="activeLocation === index ? 'text-primary dark:text-primary' : 'text-slate-900 dark:text-white group-hover:text-primary'"
                                                        x-text="loc.name"></h4>
                                                </div>
                                                <p x-show="loc.address" class="text-slate-500 text-sm leading-relaxed mt-1 line-clamp-2" x-text="loc.address"></p>
                                                
                                                <!-- Dynamic Distance or Fake Status Label -->
                                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                                    <template x-if="loc.distance">
                                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md bg-slate-200/60 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                                            <span class="material-symbols-outlined text-[12px]">route</span>
                                                            <span x-text="loc.distance + ' km'"></span>
                                                        </span>
                                                    </template>
                                                    <template x-if="loc.is_open !== null">
                                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md"
                                                              :class="loc.is_open ? 'bg-green-100 text-green-700 dark:bg-green-900/30' : 'bg-red-100 text-red-700 dark:bg-red-900/30'">
                                                            <span x-text="loc.is_open ? 'Buka' : 'Tutup'"></span>
                                                            <!-- Optional: specific hours -->
                                                            <span x-show="loc.open_time && loc.close_time" class="pl-1 ml-1 border-l border-current opacity-70" x-text="loc.open_time.substring(0,5) + '-' + loc.close_time.substring(0,5)"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <template x-if="loc.google_maps_url || (loc.latitude && loc.longitude)">
                                            <div class="mt-2 text-right">
                                                <a @click.stop :href="loc.google_maps_url ? loc.google_maps_url : ('https://www.google.com/maps/dir/?api=1&destination=' + loc.latitude + ',' + loc.longitude)" target="_blank" 
                                                   class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-primary hover:text-white hover:border-primary dark:hover:bg-primary dark:hover:text-white transition-all text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm w-full">
                                                    <span class="material-symbols-outlined text-lg">directions</span>
                                                    Petunjuk Arah
                                                </a>
                                            </div>
                                        </template>
                                    </div>
                                </template>

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
            <!-- Leaflet CSS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
            <!-- MarkerCluster CSS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css"/>
            <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css"/>
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
                
                /* Custom MarkerCluster Styling (Apple HIG Theme) */
                .marker-cluster-small, .marker-cluster-medium, .marker-cluster-large {
                    background-color: rgba(14, 165, 233, 0.4);
                }
                .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div {
                    background-color: #0ea5e9;
                    color: white;
                    font-family: inherit;
                    font-weight: bold;
                    border: 3px solid white;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
                
                /* Pulse Animation applied to external CSS block */
                @keyframes shimmer {
                    100% {
                        transform: translateX(100%);
                    }
                }
                .animate-shimmer {
                    animation: shimmer 2s infinite;
                }
            </style>
        @endpush
        @push('scripts')
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
            <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
            <script>
                function culinaryMap(locationsJson) {
                    return {
                        map: null,
                        markerCluster: null,
                        loading: true,
                        locations: [],
                        markersArray: [],
                        isFullscreen: false,
                        activeLocation: null,
                        // Geolocation Sort States
                        isCalculatingLocation: false,
                        userLocation: null,
                        
                        init() {
                            try {
                                const txt = document.createElement("textarea");
                                txt.innerHTML = locationsJson;
                                this.locations = JSON.parse(txt.value);
                                
                                // Calculate initial real-time open status
                                this.locations.forEach(loc => {
                                    loc.is_open = this.checkIfOpen(loc.open_time, loc.close_time);
                                });
                                
                                if (this.locations.length > 0) {
                                    setTimeout(() => this.initMap(), 100);
                                } else {
                                    this.loading = false;
                                }
                            } catch (e) {
                                console.error('Error parsing locations', e);
                                this.loading = false;
                            }
                            
                            // Watch for fullscreen escape
                            window.addEventListener('keydown', (e) => {
                                if (e.key === 'Escape' && this.isFullscreen) {
                                    this.toggleFullscreen();
                                }
                            });
                        },
                        
                        // DOM Map Teleportation for True Fullscreen
                        toggleFullscreen() {
                            const mapDOM = document.getElementById('culinary-map');
                            const inlineHost = document.getElementById('inline-map-host');
                            const modalHost = document.getElementById('modal-map-host');
                            
                            this.isFullscreen = !this.isFullscreen;
                            
                            if (this.isFullscreen) {
                                // Prevent Body Scroll
                                document.body.style.overflow = 'hidden';
                                // Teleport map to modal
                                modalHost.appendChild(mapDOM);
                            } else {
                                // Restore Body Scroll
                                document.body.style.overflow = '';
                                // Teleport map back
                                inlineHost.appendChild(mapDOM);
                            }
                            
                            // Let DOM settle before map bounds update
                            setTimeout(() => {
                                if (this.map) {
                                    this.map.invalidateSize();
                                    
                                    // If active location, keep it centered
                                    if (this.activeLocation !== null && this.markersArray[this.activeLocation]) {
                                        const marker = this.markersArray[this.activeLocation];
                                        this.map.setView(marker.getLatLng(), 15);
                                    } else {
                                        // Fit bounds for all active markers
                                        const bounds = L.latLngBounds();
                                        this.markersArray.forEach(m => bounds.extend(m.getLatLng()));
                                        if(bounds.isValid()) {
                                            this.map.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
                                        }
                                    }
                                }
                            }, 50);
                        },
                        
                        focusLocation(index) {
                            this.activeLocation = index;

                            if (this.map && this.markersArray[index]) {
                                const marker = this.markersArray[index];
                                const latLng = marker.getLatLng();
                                
                                // Expand cluster if the marker is inside one
                                if (this.markerCluster && this.markerCluster.getVisibleParent(marker)) {
                                     this.markerCluster.zoomToShowLayer(marker, () => {
                                         this.executeFocus(latLng, marker);
                                     });
                                } else {
                                    this.executeFocus(latLng, marker);
                                }
                            }
                        },
                        
                        executeFocus(latLng, marker) {
                            // Fly to the marker and open its popup
                            this.map.flyTo(latLng, 16, {
                                duration: 1.5,
                                easeLinearity: 0.25
                            });
                            
                            setTimeout(() => {
                                marker.openPopup();
                            }, 1500); // 1.5 seconds later, open popup
                        },
                        
                        // Calculate Distance Logic
                        calculateDistances() {
                            if (!navigator.geolocation) {
                                alert("Browser Anda tidak mendukung Geolocation.");
                                return;
                            }

                            this.isCalculatingLocation = true;
                            
                            navigator.geolocation.getCurrentPosition(
                                (position) => {
                                    try {
                                        const userLat = position.coords.latitude;
                                        const userLng = position.coords.longitude;
                                        this.userLocation = { lat: userLat, lng: userLng };
                                        
                                        // Calculate for all
                                        this.locations.forEach(loc => {
                                            const lat2 = parseFloat(loc.latitude);
                                            const lon2 = parseFloat(loc.longitude);
                                            loc.distance = this.haversine(userLat, userLng, lat2, lon2);
                                        });
                                        
                                        // Sort by distance (asc)
                                        this.locations.sort((a,b) => {
                                            const distA = isNaN(a.distance) ? 9999 : a.distance;
                                            const distB = isNaN(b.distance) ? 9999 : b.distance;
                                            return distA - distB;
                                        });
                                        
                                        // Reset map visualizer to sort order changes
                                        this.activeLocation = null;
                                        this.refreshMarkers();
                                    } catch (e) {
                                        console.error("Error calculating distances:", e);
                                    } finally {
                                        this.isCalculatingLocation = false;
                                    }
                                },
                                (err) => {
                                    console.error("Geolocation error:", err);
                                    let msg = "Gagal mendeteksi lokasi.";
                                    if(err.code === 1) msg = "Izin lokasi ditolak oleh browser.";
                                    else if(err.code === 2) msg = "Lokasi tidak tersedia (sinyal GPS/Network tidak ditemukan).";
                                    else if(err.code === 3) msg = "Waktu pencarian lokasi habis (Timeout).";
                                    
                                    alert(msg + " Pastikan GPS menyala dan izin lokasi diberikan ke situs ini.");
                                    this.isCalculatingLocation = false;
                                },
                                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                            );
                        },

                        haversine(lat1, lon1, lat2, lon2) {
                            if(isNaN(lat2) || isNaN(lon2)) return Number.NaN;
                            const R = 6371; // Radius earth in km
                            const dLat = (lat2 - lat1) * Math.PI / 180;
                            const dLon = (lon2 - lon1) * Math.PI / 180;
                            const a = 
                                Math.sin(dLat/2) * Math.sin(dLat/2) +
                                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                                Math.sin(dLon/2) * Math.sin(dLon/2);
                                
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                            const d = R * c; 
                            return (Math.round(d * 10) / 10); // 1 decimal return
                        },

                        checkIfOpen(openTime, closeTime) {
                            if (!openTime || !closeTime) return null; // Unknown/Not Set
                            
                            const now = new Date();
                            const currentHour = now.getHours();
                            const currentMinute = now.getMinutes();
                            const currentTime = currentHour + (currentMinute / 60);

                            const [openH, openM] = openTime.split(':').map(Number);
                            const [closeH, closeM] = closeTime.split(':').map(Number);
                            
                            const openVal = openH + (openM / 60);
                            const closeVal = closeH + (closeM / 60);
                            
                            // Handle past midnight (e.g., open 17:00, close 02:00)
                            if (closeVal < openVal) {
                                if (currentTime >= openVal || currentTime <= closeVal) return true;
                                return false;
                            }
                            
                            return currentTime >= openVal && currentTime <= closeVal;
                        },
                        
                        // Re-initialize markers when sorting changes
                        refreshMarkers() {
                            if(this.markerCluster) {
                                this.map.removeLayer(this.markerCluster);
                            }
                            // Call init map process directly bypassing map instantiation
                            this.loading = true;
                            this.buildMarkers();
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
                            
                            this.map.on('focus', () => { this.map.scrollWheelZoom.enable(); });
                            this.map.on('blur', () => { this.map.scrollWheelZoom.disable(); });
                            
                            this.buildMarkers();
                        },
                        
                        buildMarkers() {
                            this.markersArray = [];
                            const bounds = L.latLngBounds();
                            
                            // Initialize MarkerCluster logic
                            this.markerCluster = L.markerClusterGroup({
                                maxClusterRadius: 40,
                                showCoverageOnHover: false,
                                animate: true
                            });

                            this.locations.forEach((loc, index) => {
                                const lat = parseFloat(loc.latitude);
                                const lng = parseFloat(loc.longitude);
                                
                                if (isNaN(lat) || isNaN(lng)) return;
                                
                                // Operational Status string
                                const statusStr = loc.is_open !== undefined 
                                                    ? (loc.is_open 
                                                        ? '<span style="color:#16a34a;font-weight:700;">• Buka Sekarang</span>' 
                                                        : '<span style="color:#dc2626;font-weight:700;">• Tutup</span>') 
                                                    : '';

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
                                
                                const marker = L.marker([lat, lng], { icon: markerIcon });
                                
                                const mapsLink = loc.google_maps_url ? loc.google_maps_url : `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
                                const addressHtml = loc.address ? `<p style="font-size:12px; color:#64748b; margin:6px 0 10px 0; max-width:200px; line-height: 1.3;" class="dark:text-slate-400">${loc.address}</p>` : '<div style="height:12px;"></div>';
                                
                                const popupHtml = `
                                    <div style="min-width: 180px; padding: 4px;">
                                        <h4 style="font-weight: 800; font-size: 15px; margin: 0; font-family: inherit;" class="text-slate-900 dark:text-white">${loc.name}</h4>
                                        <div style="margin-top:2px;">${statusStr}</div>
                                        ${addressHtml}
                                        <a href="${mapsLink}" target="_blank" style="display:inline-flex; align-items:center; justify-content:center; gap:6px; background:#0ea5e9; color:white; padding:8px 14px; border-radius:12px; font-weight:700; font-size:12px; width: 100%; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#0284c7'" onmouseout="this.style.background='#0ea5e9'">
                                            <span class="material-symbols-outlined" style="font-size:16px;">directions</span> Petunjuk Arah
                                        </a>
                                    </div>
                                `;
                                
                                marker.bindPopup(popupHtml);
                                
                                // 2-Way Sync: Clicking marker scrolls to the list item
                                marker.on('click', () => {
                                    this.activeLocation = index;
                                    
                                    if(this.isFullscreen) return; // don't scroll if modal is covering screen
                                    
                                    // Scroll behavior inside the responsive container
                                    setTimeout(() => {
                                        const card = document.getElementById('card-' + index);
                                        const container = document.getElementById('locations-scroll-container');
                                        if(card && container) {
                                            const scrollOffset = card.offsetTop - container.offsetTop - 20; // 20px breathing room
                                            container.scrollTo({ top: scrollOffset, behavior: 'smooth' });
                                        }
                                    }, 100);
                                });
                                
                                bounds.extend([lat, lng]);
                                this.markersArray.push(marker);
                                this.markerCluster.addLayer(marker);
                            });
                            
                            this.map.addLayer(this.markerCluster);
                            
                            setTimeout(() => {
                                this.loading = false;
                                
                                if (this.markersArray.length > 0) {
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
