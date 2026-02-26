<!DOCTYPE html>
<html class="light scroll-smooth overflow-x-hidden" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <x-seo 
        title="Jelajah Jepara - Portal Wisata Resmi" 
        description="Temukan keindahan Jepara, dari wisata alam memukau di Karimunjawa hingga kekayaan budaya ukir yang mendunia. Panduan lengkap liburanmu ada di sini!" 
        image="{{ asset('images/logo-kura.png') }}"
    />
    <link rel="icon" href="{{ asset('images/logo-kura.png') }}" type="image/png">

    {{-- Leaflet & Icon --}}
    {{-- Local assets handled by Vite --}}

    {{-- Fonts & Icons --}}

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/css/pages/welcome.css', 'resources/js/pages/welcome.js', 'resources/js/app.js'])
</head>

<body
    class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-display antialiased transition-colors duration-200 overflow-x-hidden pt-20"
    x-data="mapComponent({
        routes: {
            places: '{{ route('places.geojson') }}',
            boundaries: '{{ route('boundaries.geojson') }}',
            infrastructures: '{{ route('infrastructures.geojson') }}',
            landUses: '{{ route('land_uses.geojson') }}',
            search: '/search/places'
        },
        categories: {{ Js::from($categories) }}
    })">

    {{-- Top Navigation --}}
    @include('layouts.partials.navbar')

    {{-- Announcement Popup Carousel --}}
    @if($announcements->isNotEmpty())
    <div
        x-data="{
            open: false,
            current: 0,
            total: {{ $announcements->count() }},
            ids: {{ Js::from($announcements->pluck('id')) }},
            init() {
                const allDismissed = this.ids.every(id =>
                    sessionStorage.getItem('ann_dismissed_' + id)
                );
                if (!allDismissed) {
                    setTimeout(() => { this.open = true; this.blurNavbar(true); }, 800);
                }
            },
            blurNavbar(blur) {
                const nav = document.getElementById('site-navbar');
                if (!nav) return;
                nav.style.transition = 'filter 0.4s ease';
                nav.style.filter = blur ? 'blur(6px)' : '';
            },
            close() {
                this.ids.forEach(id => sessionStorage.setItem('ann_dismissed_' + id, '1'));
                this.blurNavbar(false);
                this.open = false;
            },
            prev() { this.current = (this.current - 1 + this.total) % this.total; },
            next() { this.current = (this.current + 1) % this.total; }
        }"
        x-show="open"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display:none;"
        class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-8 bg-black/70 backdrop-blur-md"
        @click.self="close()"
        role="dialog"
        aria-modal="true"
    >
        {{-- Slides --}}
        @foreach($announcements as $i => $ann)
        @php
            $fmt = $ann->image_format ?? 'landscape';
            // We only apply fixed aspect ratio and sizing on desktop (md:)
            $modalStyleDesktop = match($fmt) {
                'portrait' => 'aspect-ratio:9/16; max-height:95vh; max-width:min(700px,95vw);',
                default    => 'aspect-ratio:16/9; max-height:95vh; max-width:min(1200px,98vw);',
            };
        @endphp
        <div
            x-show="current === {{ $i }}"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative rounded-3xl shadow-2xl overflow-hidden w-full flex flex-col md:block bg-white dark:bg-slate-900 mx-4 md:mx-0 w-[calc(100%-2rem)] md:w-full"
        >
            {{-- Desktop style injection for fixed aspect ratio --}}
            <style>
                @media (min-width: 768px) {
                    #announcement-{{ $ann->id }} { <?php echo $modalStyleDesktop; ?> }
                }
            </style>
            
            <div id="announcement-{{ $ann->id }}" class="contents md:block w-full h-full">

            {{-- Close Button --}}
            <button @click="close()"
                class="absolute top-3 right-3 z-20 w-10 h-10 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/70 shadow-lg transition-all duration-200 hover:scale-110"
                aria-label="Tutup">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>

            {{-- Image Container --}}
            <div class="relative w-full aspect-video md:absolute md:inset-0 md:h-full shrink-0">
                @if($ann->image)
                <img src="{{ Storage::url($ann->image) }}" alt="Pengumuman"
                     class="absolute inset-0 w-full h-full object-contain md:object-cover bg-slate-100 dark:bg-slate-800">
                @else
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-500 to-cyan-400">
                    <div class="absolute -top-8 -right-8 w-36 h-36 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-28 h-28 bg-white/10 rounded-full"></div>
                </div>
                @endif
            </div>

            {{-- Content Overlay / Below Image --}}
            <div class="relative md:absolute inset-x-0 bottom-0 p-5 sm:p-6 z-10 bg-white dark:bg-slate-900 md:bg-transparent md:dark:bg-transparent flex justify-center md:justify-start rounded-b-3xl md:rounded-none">

                @if($ann->button_text && $ann->button_link)
                <a href="{{ $ann->button_link }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white md:bg-white md:text-gray-900 font-bold rounded-xl hover:bg-primary-600 md:hover:bg-gray-100 transition-all duration-200 text-sm shadow-lg w-full md:w-auto text-center"
                   @click="close()">
                    {{ $ann->button_text }}
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                @endif
            </div>

            {{-- Navigasi Panah (jika > 1 pengumuman) --}}
            @if($announcements->count() > 1)
            <button @click="prev()"
                class="absolute left-1 md:left-3 top-1/3 md:top-1/2 -translate-y-1/2 z-20 w-8 h-8 md:w-9 md:h-9 bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/60 transition-all">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </button>
            <button @click="next()"
                class="absolute right-1 md:right-3 top-1/3 md:top-1/2 -translate-y-1/2 z-20 w-8 h-8 md:w-9 md:h-9 bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/60 transition-all">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </button>
            @endif
            
            </div> <!-- End announcement wrapper -->
        </div>
        @endforeach

        {{-- Dot Indicators --}}
        @if($announcements->count() > 1)
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-30">
            @foreach($announcements as $i => $ann)
            <button @click="current = {{ $i }}"
                :class="current === {{ $i }} ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                class="h-2 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>
        @endif
    </div>
    @endif


    {{-- Hero Section --}}
    @include('public.home.sections.hero')

    {{-- Stats Section --}}
    @include('public.home.sections.stats')

    {{-- Profile Section --}}
    @include('public.home.sections.profile')

    {{-- History & Legends Section --}}
    @include('public.home.sections.history-legends')

    {{-- Culinary Carousel Section --}}
    @include('public.home.sections.culinary-carousel')

    {{-- Culture Section --}}
    @include('public.home.sections.culture')

    {{-- Upcoming Event Section --}}
    @include('public.home.sections.upcoming-event')

    {{-- Tourism Carousel Section --}}
    @include('public.home.sections.tourism-carousel')



    {{-- News Section --}}
    @include('public.home.sections.news')

    {{-- Footer --}}
    @include('layouts.partials.footer')

</body>

</html>
