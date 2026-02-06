<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jelajahi Destinasi</title>
    <link rel="icon" href="{{ asset('images/logo-kabupaten-jepara.png') }}" type="image/png">
    
    {{-- Local assets handled by Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Inline Styles --}}
    @include('public.explore-map._styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main-light dark:text-text-main-dark font-display h-screen flex flex-col overflow-hidden" x-data="mapComponent()">

    {{-- Main Layout Container --}}
    <div class="flex flex-1 h-full w-full overflow-hidden flex-col lg:flex-row relative">
        
        {{-- Mobile Header / Toggle --}}
        <div class="lg:hidden p-4 bg-white dark:bg-surface-dark border-b border-surface-light flex items-center justify-between z-30">
            <a href="{{ route('welcome') }}" class="text-xl font-bold font-display hover:text-primary transition-colors">Pesona Jepara</a>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg bg-surface-light text-text-light">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        {{-- Sidebar Component --}}
        @include('public.explore-map._sidebar')

        {{-- Map Canvas --}}
        <main class="flex-1 relative bg-[#e5e3df] dark:bg-[#1a1814] overflow-hidden group/map z-10">
            {{-- Leaflet Map --}}
            <div id="leaflet-map" class="w-full h-full z-0"></div>

            {{-- Detail Slide-Over Panel --}}
            @include('public.explore-map._detail-panel')

            {{-- Map Controls (Zoom, Layers, Navigation) --}}
            @include('public.explore-map._map-controls')
        </main>
    </div>

    {{-- Proximity Alert Modal --}}
    @include('public.explore-map._proximity-modal')

    {{-- Alpine.js Map Component Script --}}
    @include('public.explore-map._scripts')
</body>
</html>
