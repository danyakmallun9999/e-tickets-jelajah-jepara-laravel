<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Geografis Desa Mayong Lor</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #map { height: 600px; z-index: 1; }
        .leaflet-popup-content-wrapper { border-radius: 0.5rem; }
        .leaflet-popup-content { margin: 0; width: 300px !important; }
    </style>
</head>
<body class="antialiased font-sans text-gray-800 bg-gray-50">

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Desa Mayong Lor" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto text-white">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 tracking-tight">Desa Mayong Lor</h1>
            <p class="text-xl md:text-2xl mb-10 text-gray-200">Menjelajahi Potensi dan Keindahan Desa Melalui Sistem Informasi Geografis Modern</p>
            <button @click="document.getElementById('map-section').scrollIntoView({ behavior: 'smooth' })" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-8 rounded-full transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                Jelajahi Peta
            </button>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Statistik Desa</h2>
                <div class="w-24 h-1 bg-blue-600 mx-auto rounded"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($categories as $category)
                <div class="bg-gray-50 rounded-xl p-6 shadow-sm hover:shadow-md transition duration-300 border border-gray-100 text-center group">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 text-white text-2xl" style="background-color: {{ $category->color }}">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900 mb-2">{{ $category->places_count }}</h3>
                    <p class="text-gray-600 font-medium">{{ $category->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section id="map-section" class="py-20 bg-gray-50 relative" x-data="mapComponent()">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Peta Digital</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan lokasi penting, fasilitas umum, dan potensi desa melalui peta interaktif di bawah ini.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar Filters -->
                <div class="w-full lg:w-1/4">
                    <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
                        <h3 class="text-xl font-bold mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter Kategori
                        </h3>
                        
                        <div class="space-y-3">
                            <template x-for="category in categories" :key="category.id">
                                <label class="flex items-center space-x-3 cursor-pointer group p-2 rounded-lg hover:bg-gray-50 transition">
                                    <input type="checkbox" 
                                           :value="category.id" 
                                           x-model="selectedCategories" 
                                           @change="updateMapMarkers()"
                                           class="form-checkbox h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    <span class="flex items-center flex-1">
                                        <span class="w-3 h-3 rounded-full mr-3" :style="`background-color: ${category.color}`"></span>
                                        <span class="text-gray-700 font-medium group-hover:text-gray-900" x-text="category.name"></span>
                                    </span>
                                    <span class="text-xs font-semibold bg-gray-100 text-gray-600 py-1 px-2 rounded-full" x-text="category.places_count"></span>
                                </label>
                            </template>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button @click="resetFilters()" class="w-full py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="w-full lg:w-3/4">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                        <div id="map" class="w-full h-[600px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-4">Desa Mayong Lor</h3>
            <p class="text-gray-400 mb-8">Kecamatan Mayong, Kabupaten Jepara, Jawa Tengah</p>
            <div class="border-t border-gray-800 pt-8 text-sm text-gray-500">
                &copy; {{ date('Y') }} Pemerintah Desa Mayong Lor. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- FontAwesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        function mapComponent() {
            return {
                map: null,
                markers: [],
                categories: @json($categories),
                places: @json($places),
                selectedCategories: [],

                init() {
                    // Initialize selected categories (all selected by default)
                    this.selectedCategories = this.categories.map(c => c.id);

                    // Initialize Map
                    // Coordinates for Mayong Lor (approximate, adjust as needed)
                    const center = [-6.7289, 110.7485]; 
                    
                    this.map = L.map('map').setView(center, 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(this.map);

                    // Add markers
                    this.updateMapMarkers();
                },

                updateMapMarkers() {
                    // Clear existing markers
                    this.markers.forEach(marker => this.map.removeLayer(marker));
                    this.markers = [];

                    // Filter places based on selected categories
                    const filteredPlaces = this.places.filter(place => 
                        this.selectedCategories.includes(place.category_id)
                    );

                    // Add new markers
                    filteredPlaces.forEach(place => {
                        const marker = L.marker([place.latitude, place.longitude]);
                        
                        // Custom Popup Content
                        const popupContent = `
                            <div class="overflow-hidden">
                                ${place.image ? `<img src="${place.image}" class="w-full h-32 object-cover mb-3">` : ''}
                                <div class="p-4 ${place.image ? 'pt-0' : ''}">
                                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1 block">${place.category.name}</span>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">${place.name}</h3>
                                    <p class="text-sm text-gray-600 mb-0 line-clamp-3">${place.description || 'Tidak ada deskripsi.'}</p>
                                </div>
                            </div>
                        `;

                        marker.bindPopup(popupContent, {
                            maxWidth: 300,
                            className: 'custom-popup'
                        });

                        marker.addTo(this.map);
                        this.markers.push(marker);
                    });
                },

                resetFilters() {
                    this.selectedCategories = this.categories.map(c => c.id);
                    this.updateMapMarkers();
                }
            }
        }
    </script>
</body>
</html>
