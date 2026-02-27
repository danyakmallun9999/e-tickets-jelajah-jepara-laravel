<x-public-layout>
    @push('seo')
        <x-seo
            :title="$culture->name . ' - Budaya Jepara'"
            :description="Str::limit(strip_tags($culture->description), 150)"
            :image="$culture->image_url ? $culture->image_url : asset('images/logo-kura.png')"
            type="article"
        />
    @endpush

    @php
        $hideInfoGrid = in_array($culture->category, [
            'Kemahiran & Kerajinan Tradisional (Kriya)',
            'Seni Pertunjukan',
            'Kuliner Khas',
        ]);
        $youtubeId = null;
        if ($culture->youtube_url) {
            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $culture->youtube_url, $m);
            $youtubeId = $m[1] ?? null;
        }
        $embedUrl = $youtubeId ? 'https://www.youtube.com/embed/' . $youtubeId . '?rel=0&modestbranding=1' : null;
    @endphp

    <div class="bg-white dark:bg-slate-950 min-h-screen font-sans -mt-20 pt-24 lg:pt-20">

        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row">

                {{-- ═══════════════════════════════════════
                     LEFT: Sticky Visual Panel (50%)
                ═══════════════════════════════════════ --}}
                @php
                    $galleryImages = collect([]);
                    if ($culture->image) {
                        $galleryImages->push(
                            file_exists(public_path('storage/' . $culture->image))
                                ? asset('storage/' . $culture->image)
                                : asset($culture->image)
                        );
                    }
                    foreach($culture->images as $img) {
                        $galleryImages->push(asset('storage/' . $img->image_path));
                    }
                    $galleryImages = $galleryImages->unique()->values();
                @endphp
                <div class="lg:w-1/2 lg:h-screen lg:sticky lg:top-0 relative bg-white dark:bg-slate-950 z-10 flex flex-col lg:overflow-hidden p-4 lg:pl-16 lg:pr-8 lg:pt-12"
                     x-data="{
                        activeImage: '{{ $galleryImages->first() ?? '' }}',
                        isFlipping: false,
                        lightboxOpen: false,
                        lightboxIndex: 0,
                        images: [
                            @foreach($galleryImages as $img)
                                '{{ $img }}',
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

                    {{-- Breadcrumb --}}
                    <div class="mb-6">
                     <nav class="flex" aria-label="Breadcrumb">
                         <ol class="inline-flex items-center space-x-1 md:space-x-3">
                             <li class="inline-flex items-center">
                                 <a href="{{ route('welcome') }}" wire:navigate
                                    class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white transition-colors">
                                     <span class="material-symbols-outlined text-lg mr-1">home</span>
                                     {{ __('Nav.Home') }}
                                 </a>
                             </li>
                             <li>
                                 <div class="flex items-center">
                                     <span class="material-symbols-outlined text-slate-400 mx-1">chevron_right</span>
                                     <a href="{{ route('culture.index') }}" wire:navigate
                                        class="text-sm font-medium text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white transition-colors">
                                         {{ __('Nav.Culture') }}
                                     </a>
                                 </div>
                             </li>
                             <li aria-current="page">
                                 <div class="flex items-center">
                                     <span class="material-symbols-outlined text-slate-400 mx-1">chevron_right</span>
                                     <span class="text-sm font-medium text-slate-900 dark:text-white line-clamp-1 max-w-[150px] md:max-w-xs">
                                         {{ $culture->name }}
                                     </span>
                                 </div>
                             </li>
                         </ol>
                     </nav>
                    </div>

                    {{-- Main Image --}}
                    <div class="relative w-full aspect-[4/3] lg:aspect-auto lg:h-[60vh] overflow-hidden perspective-[1000px]">
                        <div class="relative w-full h-full rounded-3xl overflow-hidden cursor-pointer text-transparent"
                             @click="openLightbox(activeImage)">
                            <template x-if="activeImage">
                                <img :src="activeImage"
                                     alt="{{ $culture->name }}"
                                     class="w-full h-full object-cover transition-all duration-500 ease-in-out transform origin-center"
                                     :class="isFlipping ? '[transform:rotateY(90deg)] opacity-75 scale-95' : '[transform:rotateY(0deg)] opacity-100 scale-100'">
                            </template>
                            <template x-if="!activeImage">
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-400">
                                    <span class="material-symbols-outlined text-6xl">image</span>
                                </div>
                            </template>
                            {{-- Zoom hint --}}
                            <div class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-colors flex items-center justify-center">
                                <div class="bg-white/90 backdrop-blur px-4 py-2 rounded-full font-bold text-sm text-slate-700 shadow-lg opacity-0 hover:opacity-100 transition-opacity flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base">zoom_in</span>
                                    Perbesar
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnails --}}
                    @if($galleryImages->count() > 1)
                    <div class="w-full px-4 lg:px-6 pb-6 pt-3 flex items-center gap-3 overflow-x-auto scrollbar-hide scroll-smooth">
                        @foreach($galleryImages as $imgUrl)
                            <button @click="changeImage('{{ $imgUrl }}')"
                                    :class="activeImage === '{{ $imgUrl }}' ? 'ring-2 ring-primary scale-105' : 'opacity-70 hover:opacity-100'"
                                    class="relative w-20 h-14 lg:w-24 lg:h-16 flex-shrink-0 rounded-xl overflow-hidden transition-all duration-300">
                                <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                    @endif

                    {{-- Lightbox Modal --}}
                    <div x-show="lightboxOpen" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-[9999] flex items-center justify-center"
                         style="position: fixed;">
                        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm" @click="closeLightbox()"></div>

                        {{-- Close --}}
                        <button @click="closeLightbox()"
                                class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm flex items-center justify-center text-white transition-all hover:scale-110">
                            <span class="material-symbols-outlined text-2xl">close</span>
                        </button>

                        {{-- Counter --}}
                        <div class="absolute top-4 left-4 sm:top-6 sm:left-6 z-20 bg-white/10 backdrop-blur-sm text-white text-sm font-medium px-4 py-2 rounded-full">
                            <span x-text="(lightboxIndex + 1) + ' / ' + images.length"></span>
                        </div>

                        {{-- Prev --}}
                        <button x-show="images.length > 1" @click="lightboxPrev()"
                                class="absolute left-2 sm:left-6 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm flex items-center justify-center text-white transition-all hover:scale-110">
                            <span class="material-symbols-outlined text-2xl">chevron_left</span>
                        </button>

                        {{-- Next --}}
                        <button x-show="images.length > 1" @click="lightboxNext()"
                                class="absolute right-2 sm:right-6 z-20 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm flex items-center justify-center text-white transition-all hover:scale-110">
                            <span class="material-symbols-outlined text-2xl">chevron_right</span>
                        </button>

                        {{-- Lightbox Image --}}
                        <div class="relative z-10 w-full h-full max-w-[90vw] max-h-[80vh] flex items-center justify-center pointer-events-none">
                            <img :src="images[lightboxIndex]"
                                 :alt="'{{ $culture->name }} - Foto ' + (lightboxIndex + 1)"
                                 class="w-auto h-auto max-w-full max-h-full object-contain rounded-lg shadow-2xl select-auto pointer-events-auto">
                        </div>

                        {{-- Thumbnail Strip --}}
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

                </div>{{-- /left --}}


                {{-- ═══════════════════════════════════════
                     RIGHT: Scrollable Content Panel (50%)
                ═══════════════════════════════════════ --}}
                <div class="lg:w-1/2 relative bg-white dark:bg-slate-950">
                    <main class="max-w-3xl mx-auto px-5 sm:px-8 py-10 md:py-16 lg:px-16 lg:pt-12 lg:pb-24">

                        {{-- Category Badge --}}
                        <div class="flex flex-wrap items-center gap-3 mb-6">
                            <span class="px-3 py-1 rounded-full bg-primary/5 dark:bg-primary/10 text-primary font-bold uppercase tracking-wider text-xs border border-primary/20">
                                {{ $culture->category }}
                            </span>
                        </div>

                        {{-- Title & Meta --}}
                        <div class="mb-10">
                            <h1 class="font-playfair text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white leading-[1.2] md:leading-tight mb-6">
                                {{ $culture->name }}
                            </h1>
                            @if(!$hideInfoGrid && ($culture->location || $culture->time))
                            <div class="flex flex-wrap gap-4">
                                @if($culture->location)
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-base">
                                    <span class="material-symbols-outlined text-xl flex-shrink-0 text-primary">location_on</span>
                                    <span class="font-light">{{ $culture->location }}</span>
                                </div>
                                @endif
                                @if($culture->time)
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-base">
                                    <span class="material-symbols-outlined text-xl flex-shrink-0 text-primary">event</span>
                                    <span class="font-light">{{ $culture->time }}</span>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <hr class="border-slate-100 dark:border-slate-800 mb-10">

                        {{-- Content Body --}}
                        <div class="space-y-12">

                             {{-- Konten Lengkap --}}
                             @if($culture->content || $culture->description)
                             <section>
                                 <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-4 flex items-center gap-3">
                                     <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                                     Tentang Budaya Ini
                                 </h3>
                                 <div x-data="{ expanded: false }">
                                     <div class="prose prose-lg prose-slate dark:prose-invert font-light text-slate-600 dark:text-slate-300 leading-relaxed text-justify transition-all duration-300 overflow-hidden"
                                          :class="expanded ? '' : 'line-clamp-3 mask-image-b'">
                                         <div class="whitespace-pre-line">{{ trim($culture->content ?? $culture->description) }}</div>
                                     </div>
                                     @if(strlen($culture->content ?? $culture->description) > 150)
                                         <button @click="expanded = !expanded" 
                                                 class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-primary dark:text-blue-400 hover:text-primary-dark dark:hover:text-blue-300 transition-colors">
                                             <span x-text="expanded ? '{{ __('Culinary.Detail.Hide') }}' : '{{ __('News.Button.ReadMore') }}'"></span>
                                             <span class="material-symbols-outlined text-lg transition-transform duration-300" 
                                                   :class="expanded ? 'rotate-180' : ''">expand_more</span>
                                         </button>
                                     @endif
                                 </div>
                             </section>
                             @endif

                            {{-- Info Grid: Kategori, Waktu, Lokasi --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Kategori --}}
                                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 hover:border-primary/30 dark:hover:border-primary/30 transition-colors">
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-3">Kategori</div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 dark:bg-primary/20 flex-shrink-0 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-xl">category</span>
                                        </div>
                                        <span class="text-slate-900 dark:text-white font-semibold text-sm leading-snug">{{ $culture->category }}</span>
                                    </div>
                                </div>

                                @if(!$hideInfoGrid)
                                    @if($culture->time)
                                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 hover:border-primary/30 dark:hover:border-primary/30 transition-colors">
                                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-3">Waktu Pelaksanaan</div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-primary/10 dark:bg-primary/20 flex-shrink-0 flex items-center justify-center text-primary">
                                                <span class="material-symbols-outlined text-xl">event</span>
                                            </div>
                                            <span class="text-slate-900 dark:text-white font-semibold text-sm">{{ $culture->time }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    @if($culture->location)
                                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 hover:border-primary/30 dark:hover:border-primary/30 transition-colors">
                                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-3">Lokasi</div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-primary/10 dark:bg-primary/20 flex-shrink-0 flex items-center justify-center text-primary">
                                                <span class="material-symbols-outlined text-xl">location_on</span>
                                            </div>
                                            <span class="text-slate-900 dark:text-white font-semibold text-sm">{{ $culture->location }}</span>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                            </div>

                            {{-- YouTube Video --}}
                            @if($embedUrl)
                            <section>
                                <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                                    <span class="w-1.5 h-6 bg-red-500 rounded-full"></span>
                                    Video
                                </h3>
                                <div class="relative w-full h-0 rounded-2xl overflow-hidden bg-black shadow-md" style="padding-bottom: 56.25%;">
                                    <iframe src="{{ $embedUrl }}"
                                            class="absolute inset-0 w-full h-full"
                                            frameborder="0" allowfullscreen
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                    </iframe>
                                </div>
                            </section>
                            @endif

                            {{-- Share --}}
                            <section class="p-6 rounded-2xl bg-gradient-to-br from-slate-50 to-white dark:from-slate-900 dark:to-slate-950 border border-slate-200 dark:border-slate-800">
                                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-3">Bagikan</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                                    Ceritakan warisan budaya <strong class="text-slate-700 dark:text-slate-200">{{ $culture->name }}</strong> kepada orang-orang di sekitar Anda.
                                </p>
                                <x-share-modal :url="request()->url()" :title="$culture->name" :text="Str::limit(strip_tags($culture->description), 100)">
                                    <button class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 active:scale-95 text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-primary/20 transition-all text-sm">
                                        <i class="fa-solid fa-share-nodes"></i>
                                        Bagikan Sekarang
                                    </button>
                                </x-share-modal>
                            </section>

                        </div>

                        {{-- Footer --}}
                        <div class="mt-20 pt-10 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <a href="{{ route('culture.index') }}" wire:navigate
                               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                Kembali ke Daftar Budaya
                            </a>
                            <p class="text-slate-400 text-xs">© {{ date('Y') }} Jelajah Jepara</p>
                        </div>

                    </main>
                </div>{{-- /right --}}

            </div>
        </div>
    </div>

<!-- Dedicated Map & Locations Section -->
    @if($culture->locations->isNotEmpty())
        @php
            $hasCoordinates = $culture->locations->whereNotNull('latitude')->whereNotNull('longitude')->isNotEmpty();
            $mapLocations = $culture->locations->whereNotNull('latitude')->whereNotNull('longitude')->values();
        @endphp

        <section class="bg-slate-50 dark:bg-slate-900/50 py-16 lg:py-24 border-t border-slate-200 dark:border-slate-800 relative z-0">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12" x-data="culinaryMap('{{ htmlspecialchars($mapLocations->toJson(), ENT_QUOTES, 'UTF-8') }}')">
                    <!-- Section Header -->
                    <div class="max-w-3xl mx-auto text-center mb-12 animate-fade-in-up">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 dark:bg-blue-900/30 text-primary dark:text-blue-400 mb-6 relative group overflow-hidden">
                            <span class="material-symbols-outlined text-3xl relative z-10">location_city</span>
                        </div>
                        <h2 class="font-playfair text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-6">
                            {{  'Lokasi Terkait' }}
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400 text-lg mb-8 max-w-2xl mx-auto">
                            Temukan lokasi terkait dari {{ $culture->name }} di bawah ini:
                        </p>

                        <!-- Distance Filter Action -->
                        <div class="flex justify-center">
                            <button @click="calculateDistances()" 
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 hover:border-primary dark:hover:border-primary text-slate-700 dark:text-slate-200 font-semibold hover:text-primary transition-colors group relative overflow-hidden"
                                    :class="isCalculatingLocation ? 'opacity-70 cursor-wait' : ''">
                                <!-- Loading Spinner inline -->
                                <span x-show="isCalculatingLocation" class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
                                <span x-show="!isCalculatingLocation" class="material-symbols-outlined text-xl">my_location</span>
                                <span x-text="isCalculatingLocation ? 'Mendeteksi Lokasi...' : (userLocation ? 'Perbarui Lokasi Saya' : 'Urutkan dari Terdekat')">Urutkan dari Terdekat</span>
                            </button>
                        </div>
                    </div>

                <div class="flex flex-col lg:flex-row gap-8">
                    
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
                                        <h3 class="font-bold text-white text-base sm:text-lg leading-tight truncate max-w-[200px] sm:max-w-md">{{ $culture->name }}</h3>
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
                             <div id="modal-map-host" class="w-full flex-1 rounded-2xl sm:rounded-3xl overflow-hidden relative border border-white/10 bg-slate-800">
                                 <!-- Floating Close Button overlaid directly on Map -->
                                 <button @click.prevent.stop="toggleFullscreen()" 
                                         class="absolute top-4 right-4 z-[400] flex items-center gap-2 px-4 py-2.5 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-600 dark:hover:border-red-500 dark:hover:text-white transition-colors group focus:outline-none">
                                     <span class="material-symbols-outlined text-xl">fullscreen_exit</span>
                                     <span class="font-bold text-sm">Kembali</span>
                                 </button>

                                 <!-- The actual map DOM moves here when fullscreen -->
                             </div>
                        </div>
                    </template>

                    <!-- Map (Left on Large Screens - Normal View) -->
                    @if($hasCoordinates)
                        <div class="w-full lg:w-3/5 xl:w-2/3 h-[400px] lg:h-[600px] rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 relative group bg-white dark:bg-slate-800 animate-fade-in-up delay-100">
                            
                            <!-- Inline Map Host -->
                            <div id="inline-map-host" class="w-full h-full relative z-0">
                                <div id="culinary-map" class="w-full h-full z-0 absolute inset-0"></div>
                            </div>
                            
                            <!-- Fullscreen Toggle Button (Apple HIG Float) -->
                            <button @click.prevent.stop="toggleFullscreen()" 
                                    class="absolute top-4 right-4 z-[400] w-12 h-12 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-200 hover:bg-primary hover:text-white dark:hover:bg-primary transition-colors focus:outline-none"
                                    title="Peta Layar Penuh">
                                <span class="material-symbols-outlined text-2xl">fullscreen</span>
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
                        <div class="relative flex-1 overflow-hidden rounded-2xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex flex-col">
                            <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-950 z-10 flex items-center justify-between">
                                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-1.5 h-5 bg-primary rounded-full"></span>
                                    Daftar Lokasi (<span x-text="locations.length"></span>)
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
                                         class="flex flex-col p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-800 transition-colors cursor-pointer group"
                                         :class="activeLocation === index ? 'border-primary dark:border-primary bg-primary/5 dark:bg-primary/10' : 'hover:border-primary/50'">
                                        <div class="flex items-start gap-4 mb-3">
                                            <div class="mt-1 w-12 h-12 rounded-full flex flex-shrink-0 items-center justify-center transition-colors"
                                                 :class="activeLocation === index ? 'bg-primary text-white' : 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary'">
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
                    animation: markerFade 0.3s ease-out forwards;
                }
                .apple-marker-icon {
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    background-color: #0ea5e9; /* primary */
                    border: 3px solid white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    z-index: 2;
                    transition: background-color 0.2s;
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
                    background-color: #0284c7; /* darker primary on hover instead of scale */
                }
                @keyframes markerFade {
                    0% { opacity: 0; transform: translateY(-10px); }
                    100% { opacity: 1; transform: translateY(0); }
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
                }
                
                /* Sleek Popup */
                .leaflet-popup-content-wrapper {
                    border-radius: 12px;
                    border: 1px solid rgba(0,0,0,0.1);
                    padding: 4px;
                    background-color: #ffffff;
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
                                    const checkMap = setInterval(() => {
                                        if (typeof L !== 'undefined' && typeof L.markerClusterGroup !== 'undefined') {
                                            clearInterval(checkMap);
                                            this.initMap();
                                        }
                                    }, 100);
                                    
                                    setTimeout(() => {
                                        clearInterval(checkMap);
                                        if (!this.map) this.loading = false;
                                    }, 5000);
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
                                        if(typeof L !== 'undefined' && this.map) {
                                            this.refreshMarkers();
                                        }
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
                            if(this.map && this.markerCluster) {
                                this.map.removeLayer(this.markerCluster);
                            }
                            this.loading = true;
                            if (typeof L !== 'undefined' && this.map) {
                                this.buildMarkers();
                            } else {
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
                                                <span class="material-symbols-outlined" style="font-size:18px;">location_on</span>
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
