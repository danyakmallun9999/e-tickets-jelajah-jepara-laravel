<!DOCTYPE html>
<html class="light scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>GIS Village Landing Page - {{ config('app.name', 'Mayong Lor') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Leaflet & Icon -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        [x-cloak] { display: none !important; }
        
        /* Map Styles */
        #leaflet-map { height: 100%; width: 100%; z-index: 0; }
        
        /* Custom Marker Animations */
        .custom-marker { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .custom-marker:hover { transform: scale(1.25); z-index: 1000 !important; }
        
        .marker-pulse { animation: pulse-blue 2s infinite; }
        @keyframes pulse-blue {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-display antialiased transition-colors duration-200" x-data="mapComponent()">

<!-- Top Navigation -->
<div class="sticky top-0 z-[10000] w-full border-b border-surface-light dark:border-surface-dark bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <header class="flex h-20 items-center justify-between gap-8">
            <div class="flex items-center gap-8">
                <a class="flex items-center gap-3 text-text-light dark:text-text-dark group" href="#">
                    <div class="flex items-center justify-center size-10 rounded-full bg-primary/20 text-primary-dark dark:text-primary transition-colors group-hover:bg-primary/30">
                        <span class="material-symbols-outlined">terrain</span>
                    </div>
                    <h2 class="text-xl font-bold leading-tight tracking-tight">Green Valley</h2>
                </a>
                <nav class="hidden lg:flex items-center gap-8">
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="#">Home</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="#gis-map">GIS Map</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="#news">News</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="#catalog">Catalog</a>
                </nav>
            </div>
            
            <div class="flex flex-1 items-center justify-end gap-4">
                <!-- Search Bar -->
                <label class="hidden md:flex flex-col w-full max-w-xs h-10 relative">
                    <div class="flex w-full h-full items-center rounded-full bg-surface-light dark:bg-surface-dark px-4 transition-colors focus-within:ring-2 focus-within:ring-primary/50">
                        <span class="material-symbols-outlined text-gray-500 dark:text-gray-400">search</span>
                        <input class="w-full bg-transparent border-none text-sm px-3 text-text-light dark:text-text-dark placeholder-gray-500 focus:ring-0" 
                               placeholder="Search data, places..." type="text"
                               x-model="searchQuery" 
                               @input.debounce.300ms="performSearch()"
                               @keydown.enter="scrollToMap()"/>
                    </div>
                    
                    <!-- Search Results Dropdown -->
                    <div x-show="searchResults.length > 0" 
                         @click.outside="searchResults = []"
                         class="absolute top-12 left-0 right-0 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-surface-light dark:border-surface-dark overflow-hidden z-50 max-h-80 overflow-y-auto" 
                         x-cloak
                         x-transition>
                        <template x-for="result in searchResults" :key="result.id || result.name">
                            <button @click="selectFeature(result); scrollToMap()" class="w-full text-left px-4 py-3 hover:bg-surface-light dark:hover:bg-black/20 border-b border-surface-light dark:border-surface-dark last:border-0 transition flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary-dark dark:text-primary flex-shrink-0">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-text-light dark:text-text-dark text-sm truncate" x-text="result.name"></p>
                                    <p class="text-xs text-text-light/60 dark:text-text-dark/60 truncate" x-text="result.type || 'Location'"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </label>

                <!-- Auth Buttons -->
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="flex items-center justify-center rounded-full h-10 px-6 bg-primary hover:bg-primary-dark text-white dark:text-gray-900 text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center justify-center rounded-full h-10 px-6 bg-primary hover:bg-primary-dark text-white dark:text-gray-900 text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
                            <span class="truncate">Login</span>
                        </a>
                    @endauth
                @endif
                
                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 rounded-full hover:bg-surface-light dark:hover:bg-surface-dark">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </header>
    </div>
</div>

<!-- Hero Section -->
<div class="relative w-full">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="relative overflow-hidden rounded-xl bg-cover bg-center h-[500px] lg:h-[600px] group" 
             style="background-image: linear-gradient(rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.6) 100%), url('https://lh3.googleusercontent.com/aida-public/AB6AXuBUzMVBQRSSUZkMlQwav98rOWGcfb2D7JypX_GsUqDe-67oKmSJ9Gn7scMij-J5m6OKQGyqZgJXZaLCin2htC9Q-tHKWAyelfgjWeouV_THkWoqG2SMGwxzXDfbwk21ZmAA2NHHvMhegC2rNhgBuC5trbeEFzNOf_zQbQ8JmBya5mDbqEcvIE-8_IFKJEREUWnboBZ5fwNj6SKS1Q2oEeY2UBE8jjYrkYANhSmdr3MKOS22lkYaVfTaQOUzrFlHJIs87Ef9k7AqNyDl');">
            <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                <div class="max-w-3xl space-y-6">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs font-bold uppercase tracking-wider">
                        Welcome to Green Valley
                    </span>
                    <h1 class="text-white text-4xl sm:text-5xl lg:text-7xl font-black leading-tight tracking-tight drop-shadow-sm">
                        A Community Mapped<br/>for Everyone.
                    </h1>
                    <p class="text-gray-100 text-lg sm:text-xl font-medium max-w-2xl mx-auto leading-relaxed drop-shadow-sm">
                        Discover our rich heritage, explore land potential, and stay connected through our interactive village information system.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <a class="flex items-center justify-center h-12 px-8 rounded-full bg-primary hover:bg-primary-dark text-background-dark text-base font-bold shadow-lg shadow-black/20 transition-all hover:-translate-y-0.5" href="{{ route('explore.map') }}">
                            Explore GIS Map
                        </a>
                        <a href="#news" class="flex items-center justify-center h-12 px-8 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 text-white text-base font-bold transition-all">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="w-full bg-background-light dark:bg-background-dark py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-8">
            <!-- Population -->
            <div class="flex flex-col gap-3 rounded-xl p-6 bg-surface-light dark:bg-surface-dark hover:bg-white dark:hover:bg-white/5 transition-colors shadow-sm border border-transparent hover:border-primary/20">
                <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary-dark dark:text-primary">
                    <span class="material-symbols-outlined">groups</span>
                </div>
                <div>
                    <p class="text-text-light/60 dark:text-text-dark/60 text-sm font-medium uppercase tracking-wide">Population</p>
                    <p class="text-text-light dark:text-text-dark text-3xl font-bold tracking-tight">{{ number_format($population->total_population ?? 1250) }}</p>
                </div>
            </div>
            <!-- Area -->
            <div class="flex flex-col gap-3 rounded-xl p-6 bg-surface-light dark:bg-surface-dark hover:bg-white dark:hover:bg-white/5 transition-colors shadow-sm border border-transparent hover:border-primary/20">
                <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary-dark dark:text-primary">
                    <span class="material-symbols-outlined">square_foot</span>
                </div>
                <div>
                    <p class="text-text-light/60 dark:text-text-dark/60 text-sm font-medium uppercase tracking-wide">Area (ha)</p>
                    <p class="text-text-light dark:text-text-dark text-3xl font-bold tracking-tight">{{ number_format( ($totalBoundaries ?? 0) * 10 + 100 ) }}</p> 
                    <!-- Dummy calculation if data not avail -->
                </div>
            </div>
            <!-- Households -->
            <div class="flex flex-col gap-3 rounded-xl p-6 bg-surface-light dark:bg-surface-dark hover:bg-white dark:hover:bg-white/5 transition-colors shadow-sm border border-transparent hover:border-primary/20">
                <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary-dark dark:text-primary">
                    <span class="material-symbols-outlined">home</span>
                </div>
                <div>
                    <p class="text-text-light/60 dark:text-text-dark/60 text-sm font-medium uppercase tracking-wide">Households</p>
                    <p class="text-text-light dark:text-text-dark text-3xl font-bold tracking-tight">{{ number_format($population->total_family ?? 320) }}</p>
                </div>
            </div>
            <!-- Facilities (Replaces Founded) -->
            <div class="flex flex-col gap-3 rounded-xl p-6 bg-surface-light dark:bg-surface-dark hover:bg-white dark:hover:bg-white/5 transition-colors shadow-sm border border-transparent hover:border-primary/20">
                <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary-dark dark:text-primary">
                    <span class="material-symbols-outlined">flag</span>
                </div>
                <div>
                    <p class="text-text-light/60 dark:text-text-dark/60 text-sm font-medium uppercase tracking-wide">Facilities</p>
                    <p class="text-text-light dark:text-text-dark text-3xl font-bold tracking-tight">{{ $totalPlaces ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GIS Map Section -->
<div class="w-full py-12 lg:py-20 scroll-mt-20" id="gis-map">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-text-light dark:text-text-dark tracking-tight">Interactive GIS Map</h2>
                <p class="mt-2 text-text-light/70 dark:text-text-dark/70 text-lg">Navigate our village geography, check land use, and find public facilities.</p>
            </div>
            <div class="relative z-[2000] flex gap-3" x-data="{ showLayers: false, showFilters: false }">
                <!-- Layers Toggle -->
                <div class="relative">
                    <button @click="showLayers = !showLayers" 
                            class="flex items-center gap-2 px-4 py-2 rounded-full bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark font-medium hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-lg">layers</span>
                        Layers
                    </button>
                    <!-- Layers Dropdown -->
                    <div x-show="showLayers" @click.outside="showLayers = false" 
                         class="absolute top-full right-0 mt-2 w-72 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-surface-light dark:border-surface-dark p-4 z-[1000]" x-cloak x-transition>
                         
                         <!-- Base Maps Section -->
                         <h4 class="text-xs font-bold uppercase tracking-wider text-text-light mb-3">Tipe Peta</h4>
                         <div class="grid grid-cols-2 gap-2 mb-4">
                            <button @click="setBaseLayer('satellite')" 
                                    :class="currentBaseLayer === 'satellite' ? 'ring-2 ring-primary border-transparent' : 'border-surface-light hover:border-primary/50'"
                                    class="relative h-16 rounded-lg border overflow-hidden group transition-all">
                                <img src="https://mt1.google.com/vt/lyrs=s&x=1325&y=3145&z=13" class="absolute inset-0 w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                    <span class="text-white text-xs font-bold text-shadow">Satelit</span>
                                </div>
                            </button>
                            <button @click="setBaseLayer('streets')" 
                                    :class="currentBaseLayer === 'streets' ? 'ring-2 ring-primary border-transparent' : 'border-surface-light hover:border-primary/50'"
                                    class="relative h-16 rounded-lg border overflow-hidden group transition-all">
                                <img src="https://mt1.google.com/vt/lyrs=m&x=1325&y=3145&z=13" class="absolute inset-0 w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                    <span class="text-white text-xs font-bold text-shadow">Jalan</span>
                                </div>
                            </button>
                         </div>

                         <div class="h-px bg-surface-light dark:bg-gray-700 my-3"></div>

                         <h4 class="text-xs font-bold uppercase tracking-wider text-text-light mb-3">Layer Data</h4>
                         <div class="space-y-3">
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm">Batas Wilayah</span>
                                <input type="checkbox" x-model="showBoundaries" @change="updateLayers()" class="rounded text-primary focus:ring-primary">
                            </label>
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm">Infrastruktur</span>
                                <input type="checkbox" x-model="showInfrastructures" @change="updateLayers()" class="rounded text-primary focus:ring-primary">
                            </label>
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm">Penggunaan Lahan</span>
                                <input type="checkbox" x-model="showLandUses" @change="updateLayers()" class="rounded text-primary focus:ring-primary">
                            </label>
                         </div>
                    </div>
                </div>

                <!-- Filters Toggle -->
                <div class="relative">
                    <button @click="showFilters = !showFilters" 
                            class="flex items-center gap-2 px-4 py-2 rounded-full bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark font-medium hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-lg">filter_alt</span>
                        Filters
                    </button>
                    <!-- Filters Dropdown (Categories) -->
                    <div x-show="showFilters" @click.outside="showFilters = false" 
                         class="absolute top-full right-0 mt-2 w-64 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-surface-light dark:border-surface-dark p-4 z-[1000]" x-cloak x-transition>
                         <h4 class="text-xs font-bold uppercase tracking-wider text-text-light mb-3">Filter Places</h4>
                         <div class="space-y-2 max-h-60 overflow-y-auto custom-scroll">
                            @foreach($categories as $category)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="{{ $category->id }}" x-model="selectedCategories" @change="updateMapMarkers()" class="rounded text-primary focus:ring-primary">
                                <span class="text-sm" style="color: {{ $category->color }}">{{ $category->name }}</span>
                            </label>
                            @endforeach
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAP CONTAINER -->
        <div class="relative w-full aspect-[16/9] lg:aspect-[21/9] bg-surface-light dark:bg-surface-dark rounded-xl overflow-hidden shadow-lg border border-surface-light dark:border-surface-dark group">
            
            <!-- Real Leaflet Map -->
            <div id="leaflet-map" class="w-full h-full z-0"></div>

            <!-- Floating Map Controls -->
            <div class="absolute top-6 right-6 flex flex-col gap-2 z-[400]">
                <button @click="map.zoomIn()" class="size-10 flex items-center justify-center bg-white dark:bg-surface-dark rounded-full shadow-md hover:bg-gray-50 dark:hover:bg-black/40 text-text-light dark:text-text-dark transition-colors" title="Zoom In">
                    <span class="material-symbols-outlined">add</span>
                </button>
                <button @click="map.zoomOut()" class="size-10 flex items-center justify-center bg-white dark:bg-surface-dark rounded-full shadow-md hover:bg-gray-50 dark:hover:bg-black/40 text-text-light dark:text-text-dark transition-colors" title="Zoom Out">
                    <span class="material-symbols-outlined">remove</span>
                </button>
                <button @click="locateUser()" class="size-10 flex items-center justify-center bg-white dark:bg-surface-dark rounded-full shadow-md hover:bg-gray-50 dark:hover:bg-black/40 text-text-light dark:text-text-dark transition-colors mt-2" title="My Location">
                    <span class="material-symbols-outlined">my_location</span>
                </button>
            </div>

            <!-- Floating Legend (Static for demo visuals, effectively shows what IS possible) -->
            <div class="absolute bottom-6 left-6 p-4 bg-white/90 dark:bg-black/80 backdrop-blur-sm rounded-lg shadow-md max-w-[200px] z-[400]">
                <h4 class="text-xs font-bold uppercase tracking-wider text-text-light dark:text-text-dark mb-3">Map Legend (Samples)</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="size-3 rounded-full bg-green-500"></span>
                        <span class="text-text-light dark:text-text-dark">Agriculture</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="size-3 rounded-full bg-blue-500"></span>
                        <span class="text-text-light dark:text-text-dark">Water Source</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="size-3 rounded-full bg-orange-400"></span>
                        <span class="text-text-light dark:text-text-dark">Residential</span>
                    </div>
                </div>
            </div>

            <!-- Selected Feature Modal (Slide-over) -->
            <div x-show="selectedFeature" 
                 @click.outside="selectedFeature = null"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="absolute top-4 right-4 bottom-4 w-80 bg-white/95 dark:bg-surface-dark/95 backdrop-blur rounded-2xl shadow-2xl z-[500] border border-surface-light dark:border-black/20 flex flex-col overflow-hidden"
                 x-cloak>
                 
                 <!-- Header Image -->
                <div class="h-40 bg-slate-200 relative shrink-0">
                    <template x-if="selectedFeature?.image_url">
                        <img :src="selectedFeature.image_url" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!selectedFeature?.image_url">
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                             <span class="material-symbols-outlined text-4xl">image</span>
                        </div>
                    </template>
                    <button @click="selectedFeature = null" class="absolute top-2 right-2 size-8 rounded-full bg-black/20 hover:bg-black/40 text-white backdrop-blur flex items-center justify-center transition">
                         <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                        <h3 class="text-white font-bold text-lg leading-tight text-shadow" x-text="selectedFeature?.name"></h3>
                        <p class="text-white/80 text-xs" x-text="selectedFeature?.type"></p>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto custom-scroll p-4 space-y-4">
                    <p class="text-sm text-text-light/80 dark:text-text-dark/80 leading-relaxed" x-text="selectedFeature?.description || 'No description available.'"></p>
                    
                    <button @click="zoomToFeature(selectedFeature)" class="w-full py-2.5 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold text-sm shadow-lg transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">location_on</span> View on Map
                    </button>
                </div>
            </div>
            
            <!-- Loading Overlay -->
            <div x-show="loading" class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-[1000] flex items-center justify-center">
                 <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
            </div>

        </div>
    </div>
</div>

<!-- News & Announcements Grid (Dummy Data as requested) -->
<div class="w-full bg-surface-light/30 dark:bg-surface-dark/20 py-16 scroll-mt-20" id="news">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-text-light dark:text-text-dark">Village News &amp; Announcements</h2>
            <a class="text-primary font-bold hover:underline flex items-center gap-1" href="#">
                View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- News Card 1 -->
            <article class="bg-background-light dark:bg-surface-dark rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all group flex flex-col h-full">
                <div class="h-48 overflow-hidden relative">
                    <div class="absolute top-3 left-3 bg-primary text-background-dark text-xs font-bold px-3 py-1 rounded-full z-10">News</div>
                    <img alt="Community gathering in a park with tents" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBwBBQX_RtcqkH1dvzPt8sfLSmNN3INyBtY96t4JKXIl8wN0okQpf7aSfD1NHqyeJT6hLcR_J1yJcYfMqAnCoL3LNmVFjdQzfCAE4ZPLWL0BXxNmZc5jtf7WZG5RG4bqpYnhG7kh05BWLd7gRvGBQr5P46PmxqnYh518XqJ--i2YE7f1O43mYsbMu0yg88QfFGOKCtf95irXxDl46peV-IicnHkUwh1FE8nUS729tCfifqZq5NFNgeKXZvB6keGE8l_lEfG37LCVAiI"/>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-center gap-2 text-xs text-text-light/50 dark:text-text-dark/50 mb-2">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        <span>Oct 24, 2023</span>
                    </div>
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark mb-2 leading-tight">Annual Harvest Festival</h3>
                    <p class="text-text-light/70 dark:text-text-dark/70 text-sm mb-4 line-clamp-2">Join us this weekend for the preparation of our biggest event of the year.</p>
                    <div class="mt-auto">
                        <a class="text-primary font-bold text-sm hover:underline" href="#">Read Story</a>
                    </div>
                </div>
            </article>
            <!-- More cards... -->
             <article class="bg-background-light dark:bg-surface-dark rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all group flex flex-col h-full">
                <div class="h-48 overflow-hidden relative">
                    <div class="absolute top-3 left-3 bg-red-400 text-white text-xs font-bold px-3 py-1 rounded-full z-10">Alert</div>
                    <img alt="Road maintanance" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWLj65QAHHISSdPjIhHxHPJoGYQjZWPbZI-2aSzOjUi3z5Y4UCKUmU12j-hZDouGaPuWiJb9icYzESmeqRulsGb2T1Q7CO67F9Pf-tx9Kzrxp6SnhbrI9tiggzkKt1POy6smWhDuUZBNkVALHRH2Mns42WcpA-a16jckchGyGI5eBVJHSqccDAF_BavOoUpLtfQZcC5Q17PsUs9U4dmh6SMtdF4K8w7qClVnPBsK0ijzoEd-eaZqOEvP2I60J6FAxpuPuvlnOE9YZu"/>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-center gap-2 text-xs text-text-light/50 dark:text-text-dark/50 mb-2">
                        <span class="material-symbols-outlined text-sm">campaign</span>
                        <span>Oct 20, 2023</span>
                    </div>
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark mb-2 leading-tight">Road Maintenance</h3>
                    <p class="text-text-light/70 dark:text-text-dark/70 text-sm mb-4 line-clamp-2">Schedule maintenance for North Bridge next Tuesday.</p>
                    <div class="mt-auto">
                        <a class="text-primary font-bold text-sm hover:underline" href="#">Read details</a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<!-- Discovery Section (Catalog) -->
<div class="w-full py-16 scroll-mt-20" id="catalog">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-text-light dark:text-text-dark mb-4">Discover Our Village</h2>
            <p class="text-text-light/70 dark:text-text-dark/70 max-w-2xl mx-auto">Explore local products, investment opportunities, and beautiful destinations.</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Featured Tourism -->
            <div class="relative group rounded-xl overflow-hidden min-h-[400px] lg:h-full bg-surface-dark">
                <img alt="Scenic waterfall" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-60 transition-opacity duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBYBwSUC5trP61lf-HgAmOkycJEX_hBAvxWSzCTuGJdsyng8DzTUJ6-x3cKZGHucyplyJ3WV32HGW0H7iUPqRYcgaXEsMC0hHQnqQnxobRatGl7-OHGtUjgHi2gGDhbih62GMdUANJJbrYiZy_Ii-Wl5RTdaTXKOG6PsqaYf1jW1FEPQgWOFtCbXW9ViJF_oV_-0I6d5ItvENi9-30HkL_MQnqSyOIcOTJgOycldwLLcVIXs0xP2EHNrPmw1-xYFH7I9cOZUb5TiH6A"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 w-full">
                    <div class="flex items-center gap-2 text-primary mb-2">
                        <span class="material-symbols-outlined">camera_alt</span>
                        <span class="text-sm font-bold uppercase tracking-wider">Tourism</span>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-3">Hidden Falls</h3>
                    <p class="text-gray-300 mb-6 max-w-md">Experience the breathtaking beauty of our natural waterfall.</p>
                    <button class="px-6 py-3 rounded-full bg-white text-background-dark font-bold text-sm hover:bg-primary hover:text-white transition-colors">
                        View Guide
                    </button>
                </div>
            </div>
            <div class="flex flex-col gap-8">
                <!-- Product -->
                <div class="flex flex-col sm:flex-row gap-6 p-6 rounded-xl bg-surface-light dark:bg-surface-dark hover:bg-white dark:hover:bg-white/5 transition-colors border border-transparent hover:border-primary/20">
                    <div class="w-full sm:w-1/3 aspect-square sm:aspect-auto rounded-lg overflow-hidden bg-gray-200">
                        <img alt="Baskets" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBYBwSUC5trP61lf-HgAmOkycJEX_hBAvxWSzCTuGJdsyng8DzTUJ6-x3cKZGHucyplyJ3WV32HGW0H7iUPqRYcgaXEsMC0hHQnqQnxobRatGl7-OHGtUjgHi2gGDhbih62GMdUANJJbrYiZy_Ii-Wl5RTdaTXKOG6PsqaYf1jW1FEPQgWOFtCbXW9ViJF_oV_-0I6d5ItvENi9-30HkL_MQnqSyOIcOTJgOycldwLLcVIXs0xP2EHNrPmw1-xYFH7I9cOZUb5TiH6A"/>
                    </div>
                    <div class="flex-1 flex flex-col justify-center">
                        <span class="text-xs font-bold text-primary uppercase mb-1">Catalog</span>
                        <h4 class="text-xl font-bold text-text-light dark:text-text-dark mb-2">Woven Baskets</h4>
                        <p class="text-text-light/70 dark:text-text-dark/70 text-sm mb-4">Handcrafted by local artisans.</p>
                        <a class="text-sm font-bold underline decoration-primary/50 hover:decoration-primary" href="#">View Product</a>
                    </div>
                </div>
                <!-- Potential -->
                <div class="flex flex-col sm:flex-row gap-6 p-6 rounded-xl bg-surface-light dark:bg-surface-dark hover:bg-white dark:hover:bg-white/5 transition-colors border border-transparent hover:border-primary/20">
                    <div class="w-full sm:w-1/3 aspect-square sm:aspect-auto rounded-lg overflow-hidden bg-gray-200">
                         <img alt="Land" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUZkgWrMRK3fKXC-eP1nG5d2MjqgoraRNXin7VB3GbfgWeOBNM8w8QYkoEv4fsTd5NF2F5IgAa2dwZ8YbovxYp1V4P-d5XBDjvb3wne0pgID5Z8oa_-eEfmrCvhGaj5BLbMMhF_quCQtx-MjU0cnB5e4uEizBP3XwpUtz-SMJOjkEMRNpLaO2dAW10u-CdAZ1nUFtkE97jvXffZ1Uvp5NIvouW9DCMGcaZS0MKdJ_Q2y_1oe7QhLjeXRFwDx0hb7cYFXNqXJ4poTPr"/>
                    </div>
                    <div class="flex-1 flex flex-col justify-center">
                        <span class="text-xs font-bold text-primary uppercase mb-1">Potential</span>
                        <h4 class="text-xl font-bold text-text-light dark:text-text-dark mb-2">Eco-Tourism Land</h4>
                        <p class="text-text-light/70 dark:text-text-dark/70 text-sm mb-4">Prime location for investment.</p>
                        <a class="text-sm font-bold underline decoration-primary/50 hover:decoration-primary" href="#">View Opportunity</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-surface-light dark:bg-surface-dark pt-16 pb-8 border-t border-surface-light dark:border-surface-dark">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <div class="space-y-4">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="material-symbols-outlined text-primary">terrain</span> Green Valley</h2>
                <p class="text-text-light/60 text-sm">Empowering our village through transparent data and shared resources.</p>
            </div>
            <!-- Quick Links -->
            <div>
                 <h3 class="font-bold mb-4">Quick Links</h3>
                 <ul class="space-y-2 text-sm text-text-light/70">
                    <li><a href="#" class="hover:text-primary">About Us</a></li>
                    <li><a href="#" class="hover:text-primary">Public Services</a></li>
                 </ul>
            </div>
        </div>
        <div class="border-t border-text-light/10 pt-8 text-center text-xs text-text-light/50">
            &copy; 2025 Green Valley Village Government. All rights reserved.
        </div>
    </div>
</footer>

<!-- JS Logic from Old File -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function mapComponent() {
        return {
            map: null,
            loading: true,
            
            // Toggles
            showBoundaries: true,
            showInfrastructures: true,
            showLandUses: true,
            
            // Data
            categories: @json($categories),
            selectedCategories: [],
            
            // Computed Data
            allPlaces: [],
            geoFeatures: [],
            
            // Search
            searchQuery: '',
            searchResults: [],
            selectedFeature: null,
            userMarker: null,
            
            // Layers
            markers: [],
            boundariesLayer: null,
            infrastructuresLayer: null,
            landUsesLayer: null,
            baseLayers: {},
            currentBaseLayer: 'streets',

            init() {
                this.selectedCategories = this.categories.map(c => c.id);
                this.$nextTick(() => {
                   this.initMap();
                   this.fetchAllData();
                });
                
                this.$watch('selectedCategories', () => this.updateMapMarkers());
            },
            
            initMap() {
                 this.map = L.map('leaflet-map', { zoomControl: false, attributionControl: false }).setView([-6.7289, 110.7485], 14);
                                    
                // Define Base Layers
                const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                    maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                });
                const googleSatellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                    maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                });

                this.baseLayers = {
                    'streets': googleStreets,
                    'satellite': googleSatellite
                };

                // Default to Satellite
                this.currentBaseLayer = 'satellite';
                this.baseLayers['satellite'].addTo(this.map);
            },

            setBaseLayer(type) {
                if (this.currentBaseLayer === type) return;
                this.map.removeLayer(this.baseLayers[this.currentBaseLayer]);
                this.currentBaseLayer = type;
                this.baseLayers[type].addTo(this.map);
            },
            
            async fetchAllData() {
                try {
                    this.loading = true;
                    
                    const [places, boundaries, infrastructures, landUses] = await Promise.all([
                        fetch('{{ route('places.geojson') }}').then(r => r.json()),
                        fetch('{{ route('boundaries.geojson') }}').then(r => r.json()),
                        fetch('{{ route('infrastructures.geojson') }}').then(r => r.json()),
                        fetch('{{ route('land_uses.geojson') }}').then(r => r.json())
                    ]);

                    // Store raw features
                    this.geoFeatures = places.features || []; // Places with geometry
                    this.allPlaces = places.features || [];
                    
                    // Load Layers
                    this.loadBoundaries(boundaries.features || []);
                    this.loadInfrastructures(infrastructures.features || []);
                    this.loadLandUses(landUses.features || []);
                    
                    // Initial Markers Render
                    this.updateMapMarkers();

                } catch (e) {
                    console.error('Error loading data:', e);
                } finally {
                    this.loading = false;
                }
            },
            
            updateLayers() {
                this.fetchAllData(); // Simple refresh for now
            },

            loadBoundaries(features) {
                if (this.boundariesLayer) this.map.removeLayer(this.boundariesLayer);
                if (!this.showBoundaries) return;
                
                this.boundariesLayer = L.geoJSON(features, {
                    style: { color: '#059669', weight: 2, fillColor: '#10b981', fillOpacity: 0.1, dashArray: '5, 5' },
                    onEachFeature: (f, l) => {
                        l.on('click', (e) => {
                            L.DomEvent.stop(e);
                            this.selectFeature({...f.properties, type: 'Batas Wilayah'});
                        });
                    }
                }).addTo(this.map);

                if (features.length > 0) this.map.fitBounds(this.boundariesLayer.getBounds(), { padding: [50, 50] });
            },

            loadInfrastructures(features) {
                if (this.infrastructuresLayer) this.map.removeLayer(this.infrastructuresLayer);
                if (!this.showInfrastructures) return;

                this.infrastructuresLayer = L.geoJSON(features, {
                    style: f => {
                        const type = f.properties.type;
                        const color = type === 'river' ? '#3b82f6' : '#64748b'; 
                        return { color: color, weight: type === 'river' ? 4 : 3, opacity: 0.8 };
                    },
                    onEachFeature: (f, l) => {
                        l.on('click', (e) => {
                            L.DomEvent.stop(e);
                            this.selectFeature({...f.properties, type: 'Infrastruktur'});
                        });
                    }
                }).addTo(this.map);
            },

            loadLandUses(features) {
                if (this.landUsesLayer) this.map.removeLayer(this.landUsesLayer);
                if (!this.showLandUses) return;

                this.landUsesLayer = L.geoJSON(features, {
                    style: f => {
                        const colors = { rice_field: '#fbbf24', forest: '#15803d', settlement: '#f97316', plantation: '#84cc16' };
                        return { color: colors[f.properties.type] || '#94a3b8', weight: 1, fillOpacity: 0.3, fillColor: colors[f.properties.type] };
                    },
                    onEachFeature: (f, l) => {
                        l.on('click', (e) => {
                            L.DomEvent.stop(e);
                            this.selectFeature({...f.properties, type: 'Penggunaan Lahan'});
                        });
                    }
                }).addTo(this.map);
            },

            updateMapMarkers() {
                this.markers.forEach(m => this.map.removeLayer(m));
                this.markers = [];
                
                const filtered = this.geoFeatures.filter(f => this.selectedCategories.includes(f.properties.category?.id));
                
                filtered.forEach(feature => {
                    const [lng, lat] = feature.geometry.coordinates;
                    const p = feature.properties;
                    const color = p.category ? p.category.color : '#3b82f6';
                    
                    const iconHtml = `
                        <div class="w-9 h-9 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white text-sm custom-marker bg-gradient-to-br from-[${color}] to-slate-600" style="background-color: ${color}">
                            <i class="${p.category?.icon_class ?? 'fa-solid fa-map-marker-alt'}"></i>
                        </div>
                    `;
                    
                    const marker = L.marker([lat, lng], {
                        icon: L.divIcon({ html: iconHtml, className: '', iconSize: [36, 36], iconAnchor: [18, 18] })
                    });
                    
                    marker.on('click', () => {
                        this.selectPlace({...p, latitude: lat, longitude: lng});
                    });
                    
                    marker.addTo(this.map);
                    this.markers.push(marker);
                });
            },
            
            performSearch() {
                if (this.searchQuery.length < 2) { this.searchResults = []; return; }
                const q = this.searchQuery.toLowerCase();
                const matches = this.allPlaces.filter(p => p.properties.name.toLowerCase().includes(q))
                    .map(p => ({ 
                        ...p.properties, 
                        coords: [...p.geometry.coordinates].reverse(),
                        type: 'Lokasi',
                        feature: p
                    }));
                this.searchResults = matches.slice(0, 5);
            },

            selectFeature(result) {
                this.selectedFeature = result;
                this.zoomToFeature(result);
                this.searchResults = [];
            },
            
            selectPlace(place) {
                    this.selectedFeature = {
                    ...place,
                    type: 'Lokasi',
                    image_url: place.image_url || null
                };
                this.zoomToFeature(place);
            },

            zoomToFeature(feature) {
                if (feature.coords) {
                    this.map.flyTo(feature.coords, 18);
                } else if (feature.latitude && feature.longitude) {
                    this.map.flyTo([feature.latitude, feature.longitude], 18);
                } else if (feature.geometry) {
                        const layer = L.geoJSON(feature);
                        this.map.fitBounds(layer.getBounds(), { padding: [50, 50] });
                }
            },
            
            scrollToMap() {
                document.getElementById('gis-map').scrollIntoView({ behavior: 'smooth' });
            },

            locateUser() {
                if (!navigator.geolocation) { alert('Browser tidak mendukung geolokasi'); return; }
                this.loading = true;
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const { latitude, longitude } = pos.coords;
                        this.map.flyTo([latitude, longitude], 17);
                        if (this.userMarker) this.map.removeLayer(this.userMarker);
                        this.userMarker = L.marker([latitude, longitude], {
                            icon: L.divIcon({ html: '<div class="w-4 h-4 bg-blue-600 rounded-full border-2 border-white shadow-lg marker-pulse"></div>', iconSize: [16, 16] })
                        }).addTo(this.map);
                        this.loading = false;
                    },
                    (err) => {
                        console.error(err);
                        this.loading = false;
                        alert('Gagal mengambil lokasi.');
                    }
                );
            }
        };
    }
</script>
</body>
</html>
