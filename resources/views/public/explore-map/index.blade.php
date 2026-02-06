<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jelajahi Destinasi - Kabupaten Jepara</title>
    <link rel="icon" href="{{ asset('images/logo-kabupaten-jepara.png') }}" type="image/png">
    
    {{-- Local assets handled by Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Inline Styles --}}
    @include('public.explore-map._styles')
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-display h-screen flex flex-col overflow-hidden" x-data="mapComponent()">

    {{-- Main Layout Container --}}
    <div class="flex flex-1 h-full w-full overflow-hidden flex-col lg:flex-row relative">
        
        {{-- Mobile Header / Toggle --}}
        <div class="lg:hidden p-4 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between z-30">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <span class="text-lg font-bold font-display text-slate-800 dark:text-white">Jelajah Jepara</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        {{-- Sidebar Component --}}
        @include('public.explore-map._sidebar')

        {{-- Map Canvas --}}
        <main class="flex-1 relative bg-slate-200 dark:bg-slate-800 overflow-hidden group/map z-10">
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
