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
            <div class="lg:w-1/2 lg:h-screen lg:sticky lg:top-0 relative bg-white dark:bg-slate-950 z-10 p-4 lg:pl-16 lg:pr-8 lg:pt-12 flex flex-col justify-start">
                 
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

                 <!-- Image Card Wrapper -->
                 <div class="relative w-full max-w-2xl mx-auto aspect-[4/3] rounded-[2.5rem] overflow-hidden group">
                    <!-- Main Image with "Ken Burns" Effect -->
                    <img src="{{ $culinary->image_url }}" alt="{{ $culinary->name }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-110 transition-transform duration-[20s] ease-in-out will-change-transform">
                    
                    <!-- Cinematic Overlays -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/10 opacity-60 transition-opacity duration-700"></div>
                 </div>
            </div>

            <!-- Right Side: Scrollable Content (50%) -->
            <div class="lg:w-1/2 relative bg-white dark:bg-slate-950">
                <main class="max-w-2xl mx-auto px-5 sm:px-8 py-10 md:py-20 lg:px-16 lg:pt-12 lg:pb-24 stagger-entry">
                    
                    <!-- Header Section -->
                    <div class="mb-10">
                        <!-- Breadcrumbs / Badges -->
                        <div class="flex items-center gap-3 mb-4 text-sm">
                            <span class="px-3 py-1 rounded-full bg-primary/10 dark:bg-blue-900/30 text-primary dark:text-blue-400 font-bold uppercase tracking-wider text-xs border border-primary/20">
                                {{ __('Culinary.Detail.Badge') }}
                            </span>
                        </div>

                        <h1 class="font-playfair text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white leading-[1.2] md:leading-tight mb-6">
                            {{ $culinary->name }}
                        </h1>
                        
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-lg">
                            <span class="material-symbols-outlined text-xl text-primary">restaurant_menu</span>
                            <span>{{ __('Culinary.Detail.Subtitle') }}</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="border-slate-100 dark:border-slate-800 mb-10">

                    <!-- Content Body -->
                    <div class="space-y-12">
                        
                         <!-- Description -->
                         <section>
                             <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                                 <span class="w-1 h-6 bg-primary rounded-full"></span>
                                 {{ __('Culinary.Detail.About') }}
                             </h3>
                             <div x-data="{ expanded: false }">
                                 <div class="prose prose-lg prose-slate dark:prose-invert font-light text-slate-600 dark:text-slate-300 leading-relaxed text-justify transition-all duration-300 overflow-hidden"
                                      :class="expanded ? '' : 'line-clamp-3 mask-image-b'">
                                     <div class="whitespace-pre-line">{{ trim($culinary->content ?? $culinary->description) }}</div>
                                 </div>
                                 @if(strlen($culinary->content ?? $culinary->description) > 150)
                                     <button @click="expanded = !expanded" 
                                             class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-primary dark:text-blue-400 hover:text-primary-dark dark:hover:text-blue-300 transition-colors">
                                         <span x-text="expanded ? '{{ __('Culinary.Detail.Hide') }}' : '{{ __('News.Button.ReadMore') }}'"></span>
                                         <span class="material-symbols-outlined text-lg transition-transform duration-300" 
                                               :class="expanded ? 'rotate-180' : ''">expand_more</span>
                                     </button>
                                 @endif
                             </div>
                         </section>

                        <!-- Highlights / Quick Info Grid -->
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-6 rounded-2xl bg-primary/5 dark:bg-blue-900/10 border border-primary/20 dark:border-blue-800/30">
                                <div class="text-primary text-xs font-bold uppercase tracking-wider mb-2">{{ __('Culinary.Detail.Recommendation') }}</div>
                                <p class="text-slate-800 dark:text-blue-100 font-medium text-sm italic">
                                    "{{ $culinary->description }}"
                                </p>
                            </div>
                        </div>

                        <!-- Location / Map Section -->
                        <section>
                             <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                                 <div class="flex items-center gap-3 mb-4">
                                     <div class="w-10 h-10 rounded-full bg-primary/10 dark:bg-blue-900/30 flex items-center justify-center text-primary dark:text-blue-400">
                                         <span class="material-symbols-outlined text-xl">storefront</span>
                                     </div>
                                     <div>
                                         <h3 class="font-bold text-slate-900 dark:text-white">{{ __('Culinary.Detail.WantToTry') }}</h3>
                                         <p class="text-slate-500 text-xs mt-0.5">{{ __('Culinary.Detail.FindNearby', ['name' => $culinary->name]) }}</p>
                                     </div>
                                 </div>

                                 @if($culinary->locations->isNotEmpty())
                                     @php
                                         $hasCoordinates = $culinary->locations->whereNotNull('latitude')->whereNotNull('longitude')->isNotEmpty();
                                         $mapLocations = $culinary->locations->whereNotNull('latitude')->whereNotNull('longitude')->values();
                                     @endphp

                                     @if($hasCoordinates)
                                         <!-- Interactive Map Container (Apple HIG Style) -->
                                         <div class="mb-5 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm relative group bg-slate-100 dark:bg-slate-800" style="height: 300px;" x-data="culinaryMap('{{ htmlspecialchars($mapLocations->toJson(), ENT_QUOTES, 'UTF-8') }}')">
                                             <div id="culinary-map" class="w-full h-full z-0"></div>
                                             
                                             <!-- Loading overlay -->
                                             <div x-show="loading" x-transition.opacity.duration.300ms class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm z-10">
                                                 <div class="flex flex-col items-center gap-3">
                                                     <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                                                     <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Memuat Peta...</span>
                                                 </div>
                                             </div>
                                         </div>
                                     @endif

                                     <!-- Recommended Locations List -->
                                     <div class="space-y-3 mb-6">
                                         @foreach($culinary->locations as $location)
                                             <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm hover:border-primary/30 transition-colors group">
                                                 <div class="flex items-start gap-3">
                                                     <div class="mt-1 w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-red-500">
                                                         <span class="material-symbols-outlined text-sm">location_on</span>
                                                     </div>
                                                     <div>
                                                         <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $location->name }}</h4>
                                                         @if($location->address)
                                                             <p class="text-slate-500 text-[11px] leading-tight">{{ $location->address }}</p>
                                                         @endif
                                                     </div>
                                                 </div>
                                                 @if($location->google_maps_url || ($location->latitude && $location->longitude))
                                                     <a href="{{ $location->google_maps_url ?: 'https://www.google.com/maps/dir/?api=1&destination=' . $location->latitude . ',' . $location->longitude }}" target="_blank" 
                                                        class="flex items-center gap-1 px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg text-xs font-bold transition-all shrink-0">
                                                         {{ __('Events.Detail.MapsLink') ?? 'Rute' }}
                                                         <span class="material-symbols-outlined text-xs">open_in_new</span>
                                                     </a>
                                                 @endif
                                             </div>
                                         @endforeach
                                     </div>
                                 @endif
                             </div>
                        </section>
                    </div>

                    <!-- Footer Area -->
                    <div class="mt-16 pt-8 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-slate-400 text-sm font-serif italic">{{ __('Culinary.Detail.ShareLabel') }}</span>
                        <x-share-modal :url="request()->url()" :title="$culinary->name" :text="Str::limit(strip_tags($culinary->description), 100)">
                            <button class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all duration-300" title="{{ __('Culinary.Detail.ShareButton') }}">
                                <i class="fa-solid fa-share-nodes text-sm"></i>
                            </button>
                        </x-share-modal>
                    </div>

                </main>
            </div>

            </div>
        </div>
    </div>

    <!-- Leaflet & Custom Map Scripts -->
    @if(isset($hasCoordinates) && $hasCoordinates)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        
        <style>
            /* Apple HIG Style Map Customizations */
            .leaflet-control-attribution,
            .leaflet-control-zoom {
                display: none !important;
            }
            .apple-marker-container {
                position: relative;
                width: 32px;
                height: 40px;
                display: flex;
                flex-direction: column;
                align-items: center;
                animation: markerDrop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }
            .apple-marker-icon {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background-color: #ef4444; /* red-500 */
                border: 3px solid white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 14px;
                z-index: 2;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .apple-marker-pointer {
                width: 0;
                height: 0;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 8px solid #ef4444;
                margin-top: -2px;
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
                margin: 12px 14px;
                line-height: 1.4;
            }
            .leaflet-container a.leaflet-popup-close-button {
                color: #94a3b8;
                padding: 6px;
                border-radius: 50%;
                top: 8px;
                right: 8px;
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

        <script>
            function culinaryMap(locationsJson) {
                return {
                    map: null,
                    loading: true,
                    locations: [],
                    
                    init() {
                        try {
                            // Decode HTML entities since we used htmlspecialchars
                            const txt = document.createElement("textarea");
                            txt.innerHTML = locationsJson;
                            this.locations = JSON.parse(txt.value);
                            
                            if (this.locations.length > 0) {
                                // Initialize map on next tick to ensure container is ready
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
                        
                        // Prevent re-initialization
                        if (mapEl._leaflet_id) {
                            mapEl._leaflet_id = null;
                        }
                        
                        this.map = L.map('culinary-map', {
                            zoomControl: false,
                            scrollWheelZoom: false // Prevent accidental scrolling
                        });
                        
                        // Premium Base Layer (CartoDB Positron - great for HIG clean look)
                        // Auto-detect dark mode
                        const isDarkMode = document.documentElement.classList.contains('dark');
                        const tileUrl = isDarkMode 
                            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                            : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
                            
                        L.tileLayer(tileUrl, {
                            maxZoom: 20,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
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
                                            <span class="material-symbols-outlined" style="font-size:16px;">restaurant</span>
                                        </div>
                                        <div class="apple-marker-pointer"></div>
                                    </div>
                                `,
                                className: '',
                                iconSize: [32, 40],
                                iconAnchor: [16, 40],
                                popupAnchor: [0, -38]
                            });
                            
                            const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(this.map);
                            
                            // Popup Content
                            const mapsLink = loc.google_maps_url ? loc.google_maps_url : `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
                            const addressHtml = loc.address ? `<p style="font-size:11px; color:#64748b; margin:4px 0 8px 0; max-width:180px;" class="dark:text-slate-400">${loc.address}</p>` : '<div style="height:8px;"></div>';
                            
                            const popupHtml = `
                                <div style="min-width: 160px; padding: 2px;">
                                    <h4 style="font-weight: 700; font-size: 14px; margin: 0; font-family: inherit;" class="text-slate-900 dark:text-white">${loc.name}</h4>
                                    ${addressHtml}
                                    <a href="${mapsLink}" target="_blank" style="display:inline-flex; align-items:center; gap:4px; background:#0ea5e9; color:white; padding:6px 12px; border-radius:12px; font-weight:600; font-size:11px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#0284c7'" onmouseout="this.style.background='#0ea5e9'">
                                        <span class="material-symbols-outlined" style="font-size:14px;">directions_car</span> Rute Maps
                                    </a>
                                </div>
                            `;
                            
                            marker.bindPopup(popupHtml);
                            bounds.extend([lat, lng]);
                            markers.push(marker);
                        });
                        
                        // Map interactions
                        this.map.on('focus', () => { this.map.scrollWheelZoom.enable(); });
                        this.map.on('blur', () => { this.map.scrollWheelZoom.disable(); });
                        
                        // Hide loader and fit bounds
                        setTimeout(() => {
                            this.loading = false;
                            
                            if (markers.length > 0) {
                                // Add slight padding and max zoom
                                this.map.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
                                // Invalidate size after fitBounds to ensure correct rendering
                                setTimeout(() => this.map.invalidateSize(), 300);
                            }
                        }, 500); 
                    }
                };
            }
        </script>
    @endif
</x-public-layout>
