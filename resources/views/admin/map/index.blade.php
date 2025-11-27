<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Admin Panel · Peta Interaktif</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Pemetaan Digital Desa Mayonglor
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="interactiveMap()" x-init="init()">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-t-lg border-b border-gray-200">
                <nav class="flex space-x-1 px-4 overflow-x-auto" aria-label="Tabs">
                    <button 
                        @click="activeTab = 'boundary'"
                        :class="activeTab === 'boundary' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition"
                    >
                        <i class="fa-solid fa-map mr-2"></i> Batas Wilayah
                    </button>
                    <button 
                        @click="activeTab = 'infrastructure'"
                        :class="activeTab === 'infrastructure' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition"
                    >
                        <i class="fa-solid fa-route mr-2"></i> Infrastruktur
                    </button>
                    <button 
                        @click="activeTab = 'landuse'"
                        :class="activeTab === 'landuse' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition"
                    >
                        <i class="fa-solid fa-seedling mr-2"></i> Penggunaan Lahan
                    </button>
                    <button 
                        @click="activeTab = 'road'"
                        :class="activeTab === 'road' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition"
                    >
                        <i class="fa-solid fa-road mr-2"></i> Jalan
                    </button>
                    <button 
                        @click="activeTab = 'place'"
                        :class="activeTab === 'place' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition"
                    >
                        <i class="fa-solid fa-map-marker-alt mr-2"></i> Titik Lokasi
                    </button>
                </nav>
            </div>

            <!-- Map Container with Sidebar -->
            <div class="bg-white rounded-b-lg border border-gray-200 border-t-0">
                <div class="flex h-[calc(100vh-200px)]">
                    <!-- Sidebar -->
                    <div class="w-80 border-r border-gray-200 bg-gray-50 flex flex-col">
                        <!-- Tab Content -->
                        <div class="flex-1 overflow-y-auto p-4">
                            <!-- Batas Wilayah Tab -->
                            <div x-show="activeTab === 'boundary'" x-cloak class="space-y-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Batas Wilayah</h3>
                                    <a href="{{ route('admin.boundaries.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                        <i class="fa-solid fa-plus mr-1"></i> Tambah
                                    </a>
                                </div>
                                <div class="space-y-2">
                                    <template x-if="boundaries.length === 0">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 text-center text-gray-500 text-sm">
                                            <i class="fa-solid fa-inbox mb-2 text-2xl"></i>
                                            <p>Belum ada data batas wilayah</p>
                                            <a href="{{ route('admin.boundaries.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                                Tambah sekarang
                                            </a>
                                        </div>
                                    </template>
                                    <template x-for="item in boundaries" :key="item.id">
                                        <div class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition"
                                             @click="focusOnFeature(item, 'boundary')">
                                            <p class="font-semibold text-sm text-gray-900" x-text="item.name"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.type"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Infrastruktur Tab -->
                            <div x-show="activeTab === 'infrastructure'" x-cloak class="space-y-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Infrastruktur</h3>
                                    <a href="{{ route('admin.infrastructures.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                        <i class="fa-solid fa-plus mr-1"></i> Tambah
                                    </a>
                                </div>
                                <div class="space-y-2">
                                    <template x-if="infrastructures.length === 0">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 text-center text-gray-500 text-sm">
                                            <i class="fa-solid fa-inbox mb-2 text-2xl"></i>
                                            <p>Belum ada data infrastruktur</p>
                                            <a href="{{ route('admin.infrastructures.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                                Tambah sekarang
                                            </a>
                                        </div>
                                    </template>
                                    <template x-for="item in infrastructures" :key="item.id">
                                        <div class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition"
                                             @click="focusOnFeature(item, 'infrastructure')">
                                            <p class="font-semibold text-sm text-gray-900" x-text="item.name"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.type"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Penggunaan Lahan Tab -->
                            <div x-show="activeTab === 'landuse'" x-cloak class="space-y-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Penggunaan Lahan</h3>
                                    <a href="{{ route('admin.land-uses.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                        <i class="fa-solid fa-plus mr-1"></i> Tambah
                                    </a>
                                </div>
                                <div class="space-y-2">
                                    <template x-if="landUses.length === 0">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 text-center text-gray-500 text-sm">
                                            <i class="fa-solid fa-inbox mb-2 text-2xl"></i>
                                            <p>Belum ada data penggunaan lahan</p>
                                            <a href="{{ route('admin.land-uses.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                                Tambah sekarang
                                            </a>
                                        </div>
                                    </template>
                                    <template x-for="item in landUses" :key="item.id">
                                        <div class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition"
                                             @click="focusOnFeature(item, 'landuse')">
                                            <p class="font-semibold text-sm text-gray-900" x-text="item.name"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.type"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Jalan Tab -->
                            <div x-show="activeTab === 'road'" x-cloak class="space-y-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Jalan</h3>
                                    <a href="{{ route('admin.infrastructures.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                        <i class="fa-solid fa-plus mr-1"></i> Tambah
                                    </a>
                                </div>
                                <div class="space-y-2">
                                    <template x-if="roads.length === 0">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 text-center text-gray-500 text-sm">
                                            <i class="fa-solid fa-inbox mb-2 text-2xl"></i>
                                            <p>Belum ada data jalan</p>
                                            <a href="{{ route('admin.infrastructures.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                                Tambah sekarang
                                            </a>
                                        </div>
                                    </template>
                                    <template x-for="item in roads" :key="item.id">
                                        <div class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition"
                                             @click="focusOnFeature(item, 'road')">
                                            <p class="font-semibold text-sm text-gray-900" x-text="item.name"></p>
                                            <p class="text-xs text-gray-500 mt-1">Panjang: <span x-text="item.length_meters ? item.length_meters + ' m' : '-'"></span></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Titik Lokasi Tab -->
                            <div x-show="activeTab === 'place'" x-cloak class="space-y-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Titik Lokasi</h3>
                                    <a href="{{ route('admin.places.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                        <i class="fa-solid fa-plus mr-1"></i> Tambah
                                    </a>
                                </div>
                                <div class="space-y-2">
                                    <template x-if="places.length === 0">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 text-center text-gray-500 text-sm">
                                            <i class="fa-solid fa-inbox mb-2 text-2xl"></i>
                                            <p>Belum ada data titik lokasi</p>
                                            <a href="{{ route('admin.places.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                                Tambah sekarang
                                            </a>
                                        </div>
                                    </template>
                                    <template x-for="item in places" :key="item.id">
                                        <div class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition"
                                             @click="focusOnFeature(item, 'place')">
                                            <p class="font-semibold text-sm text-gray-900" x-text="item.name"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.category?.name || ''"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Drawing Tools -->
                        <div class="border-t border-gray-200 p-4 bg-white">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Alat Gambar</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <button 
                                    @click="startDrawing('point')"
                                    :class="drawingMode === 'point' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-3 py-2 rounded-lg text-xs font-semibold hover:bg-blue-500 hover:text-white transition"
                                >
                                    <i class="fa-solid fa-map-marker-alt mr-1"></i> Titik
                                </button>
                                <button 
                                    @click="startDrawing('line')"
                                    :class="drawingMode === 'line' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-3 py-2 rounded-lg text-xs font-semibold hover:bg-blue-500 hover:text-white transition"
                                >
                                    <i class="fa-solid fa-route mr-1"></i> Garis
                                </button>
                                <button 
                                    @click="startDrawing('polygon')"
                                    :class="drawingMode === 'polygon' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-3 py-2 rounded-lg text-xs font-semibold hover:bg-blue-500 hover:text-white transition"
                                >
                                    <i class="fa-solid fa-draw-polygon mr-1"></i> Polygon
                                </button>
                                <button 
                                    @click="clearDrawing()"
                                    class="px-3 py-2 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition"
                                >
                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                </button>
                            </div>
                            
                            <!-- GeoJSON Upload -->
                            <div class="mt-3 border-t border-gray-100 pt-3">
                                <input type="file" x-ref="geojsonInput" accept=".geojson,.json" class="hidden" @change="handleFileUpload($event)">
                                <button 
                                    @click="$refs.geojsonInput.click()"
                                    class="w-full px-3 py-2 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition flex items-center justify-center"
                                >
                                    <i class="fa-solid fa-file-import mr-2"></i> Upload GeoJSON (QGIS)
                                </button>
                                
                                <button 
                                    x-show="uploadedGeoJSON"
                                    @click="saveUploadedFeature()"
                                    class="w-full mt-2 px-3 py-2 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700 transition flex items-center justify-center"
                                    x-transition
                                >
                                    <i class="fa-solid fa-save mr-2"></i> Simpan Fitur
                                </button>
                            </div>

                            <div class="mt-3">
                                <button 
                                    @click="toggleMeasure()"
                                    :class="measureMode ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="w-full px-3 py-2 rounded-lg text-xs font-semibold hover:bg-green-500 hover:text-white transition"
                                >
                                    <i class="fa-solid fa-ruler mr-1"></i> <span x-text="measureMode ? 'Nonaktifkan' : 'Aktifkan'"></span> Pengukuran
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Map Area -->
                    <div class="flex-1 relative">
                        <div id="interactiveMap" class="w-full h-full"></div>
                        
                        <!-- Floating Undo/Redo Controls -->
                        <div class="absolute top-4 left-1/2 transform -translate-x-1/2 z-[1000] flex space-x-2" 
                             x-show="drawingMode === 'line' || drawingMode === 'polygon'" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-4">
                            <button 
                                @click="undoPoint()"
                                class="bg-white text-gray-700 hover:text-gray-900 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-md border border-gray-200 text-sm font-semibold flex items-center transition"
                                title="Undo (Ctrl+Z)"
                            >
                                <i class="fa-solid fa-undo mr-2"></i> Undo
                            </button>
                            <button 
                                @click="redoPoint()"
                                class="bg-white text-gray-700 hover:text-gray-900 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-md border border-gray-200 text-sm font-semibold flex items-center transition"
                                title="Redo (Ctrl+Y)"
                            >
                                <i class="fa-solid fa-redo mr-2"></i> Redo
                            </button>
                        </div>
                        
                        <!-- Map Controls Overlay -->
                        <div class="absolute top-4 right-4 z-[1000] space-y-2">
                            <!-- Layer Control -->
                            <div class="bg-white/90 backdrop-blur-sm border border-gray-200 rounded-lg shadow-sm p-3 min-w-[200px]">
                                <h5 class="text-xs font-semibold text-gray-700 mb-2">Layer</h5>
                                <div class="space-y-2">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" x-model="showBoundaries" @change="toggleLayer('boundaries')" class="rounded">
                                        <span class="text-xs text-gray-700">Batas Wilayah</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" x-model="showInfrastructures" @change="toggleLayer('infrastructures')" class="rounded">
                                        <span class="text-xs text-gray-700">Infrastruktur</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" x-model="showLandUses" @change="toggleLayer('landuses')" class="rounded">
                                        <span class="text-xs text-gray-700">Penggunaan Lahan</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" x-model="showPlaces" @change="toggleLayer('places')" class="rounded">
                                        <span class="text-xs text-gray-700">Titik Lokasi</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div x-show="selectedFeature" x-cloak 
                             class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm border border-gray-200 rounded-lg shadow-sm p-4 min-w-[300px] z-[1000]">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-900" x-text="selectedFeature?.name"></h4>
                                <button @click="selectedFeature = null" class="text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p x-show="selectedFeature?.type"><strong>Tipe:</strong> <span x-text="selectedFeature?.type"></span></p>
                                <p x-show="selectedFeature?.area_hectares"><strong>Luas:</strong> <span x-text="selectedFeature?.area_hectares + ' ha'"></span></p>
                                <p x-show="selectedFeature?.length_meters"><strong>Panjang:</strong> <span x-text="selectedFeature?.length_meters + ' m'"></span></p>
                                <p x-show="selectedFeature?.description"><strong>Deskripsi:</strong> <span x-text="selectedFeature?.description"></span></p>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <a :href="selectedFeature?.editUrl" class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Edit
                                </a>
                                <button @click="deleteFeature(selectedFeature)" class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-cloak 
             class="fixed inset-0 z-[2000] overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="showDeleteModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Hapus Fitur
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus fitur <span class="font-bold" x-text="featureToDelete?.name"></span>? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" 
                                @click="confirmDelete()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                        <button type="button" 
                                @click="showDeleteModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('styles')
        <style>
            [x-cloak] { display: none !important; }
        </style>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endPushOnce

    @pushOnce('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            function interactiveMap() {
                return {
                    activeTab: 'boundary',
                    map: null,
                    drawnItems: null,
                    drawControl: null,
                    drawingMode: null,
                    currentDrawHandler: null,
                    measureMode: false,
                    selectedFeature: null,
                    showDeleteModal: false,
                    selectedFeature: null,
                    showDeleteModal: false,
                    featureToDelete: null,
                    uploadedGeoJSON: null,
                    
                    // Data
                    boundaries: @json($boundaries ?? []),
                    infrastructures: @json($infrastructures ?? []),
                    landUses: @json($landUses ?? []),
                    roads: @json($roads ?? []),
                    places: @json($places ?? []),
                    
                    // Layers
                    boundariesLayer: null,
                    infrastructuresLayer: null,
                    landUsesLayer: null,
                    placesLayer: null,
                    
                    // Layer visibility
                    showBoundaries: true,
                    showInfrastructures: true,
                    showLandUses: true,
                    showPlaces: true,

                    init() {
                        this.$nextTick(() => {
                            this.initMap();
                            this.loadAllLayers();
                            this.initKeyboardShortcuts();
                        });
                    },

                    initMap() {
                        this.map = L.map('interactiveMap').setView([-6.7289, 110.7485], 14);

                        // Google Maps Layers
                        const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}&s=Galileo&apistyle=s.t%3Apoi%7Cp.v%3Aoff%2Cs.t%3Atransit%7Cp.v%3Aoff', {
                            maxZoom: 20,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                            attribution: '&copy; Google Maps'
                        }).addTo(this.map);

                        const googleHybrid = L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                            maxZoom: 20,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                            attribution: '&copy; Google Maps'
                        });

                        const googleSatellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                            maxZoom: 20,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                            attribution: '&copy; Google Maps'
                        });

                        const googleTerrain = L.tileLayer('https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}&s=Galileo&apistyle=s.t%3Apoi%7Cp.v%3Aoff%2Cs.t%3Atransit%7Cp.v%3Aoff', {
                            maxZoom: 20,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                            attribution: '&copy; Google Maps'
                        });

                        // Base Layers Control
                        const baseLayers = {
                            "Google Streets": googleStreets,
                            "Google Hybrid": googleHybrid,
                            "Google Satellite": googleSatellite,
                            "Google Terrain": googleTerrain
                        };

                        L.control.layers(baseLayers).addTo(this.map);

                        this.drawnItems = new L.FeatureGroup();
                        this.map.addLayer(this.drawnItems);

                        this.initDrawControl();
                        this.initMapEvents();
                    },

                    initDrawControl() {
                        this.drawControl = new L.Control.Draw({
                            position: 'topright',
                            draw: {
                                polygon: { allowIntersection: false, showArea: true },
                                polyline: { metric: true },
                                circle: false,
                                rectangle: false,
                                marker: true,
                                circlemarker: false
                            },
                            edit: {
                                featureGroup: this.drawnItems,
                                remove: true
                            }
                        });
                        this.map.addControl(this.drawControl);

                        this.map.on(L.Draw.Event.CREATED, (e) => {
                            const layer = e.layer;
                            this.drawnItems.addLayer(layer);
                            
                            // Disable drawing after creation
                            if (this.currentDrawHandler) {
                                this.currentDrawHandler.disable();
                                this.currentDrawHandler = null;
                            }
                            this.drawingMode = null;
                            this.map.getContainer().style.cursor = '';
                            
                            // Show confirmation dialog
                            if (confirm('Geometry berhasil dibuat. Simpan sebagai fitur baru?')) {
                                this.handleNewFeature(layer);
                            }
                        });
                        
                        this.map.on(L.Draw.Event.DRAWSTART, () => {
                            this.map.getContainer().style.cursor = 'crosshair';
                        });
                        
                        this.map.on(L.Draw.Event.DRAWSTOP, () => {
                            this.map.getContainer().style.cursor = '';
                        });
                    },

                    initMapEvents() {
                        this.map.on('click', (e) => {
                            if (this.drawingMode === 'point') {
                                this.addPoint(e.latlng);
                            }
                        });
                    },

                    loadAllLayers() {
                        this.loadBoundaries();
                        this.loadInfrastructures();
                        this.loadLandUses();
                        this.loadPlaces();
                        this.loadRoads();
                    },

                    loadBoundaries() {
                        if (this.boundariesLayer) {
                            this.map.removeLayer(this.boundariesLayer);
                        }
                        this.boundariesLayer = L.geoJSON(null);
                        
                        fetch('{{ route("boundaries.geojson") }}')
                            .then(res => res.json())
                            .then(data => {
                                this.boundariesLayer = L.geoJSON(data.features || [], {
                                    style: { color: '#10b981', weight: 2, fillColor: '#10b981', fillOpacity: 0.2 },
                                    onEachFeature: (feature, layer) => {
                                        // Store feature reference in layer
                                        layer.feature = feature;
                                        layer.on('click', () => {
                                            this.selectedFeature = {
                                                ...feature.properties,
                                                geometry: feature.geometry,
                                                editUrl: `/admin/boundaries/${feature.properties.id}/edit`
                                            };
                                        });
                                    }
                                });
                                if (this.showBoundaries) {
                                    this.boundariesLayer.addTo(this.map);
                                }
                            });
                    },

                    loadInfrastructures() {
                        if (this.infrastructuresLayer) {
                            this.map.removeLayer(this.infrastructuresLayer);
                        }
                        
                        fetch('{{ route("infrastructures.geojson") }}')
                            .then(res => res.json())
                            .then(data => {
                                this.infrastructuresLayer = L.geoJSON(data.features || [], {
                                    style: (feature) => {
                                        const type = feature.properties.type;
                                        const color = type === 'river' ? '#3b82f6' : type === 'road' ? '#6b7280' : '#8b5cf6';
                                        return { color, weight: type === 'road' ? 4 : 3, opacity: 0.8 };
                                    },
                                    onEachFeature: (feature, layer) => {
                                        layer.feature = feature;
                                        layer.on('click', () => {
                                            this.selectedFeature = {
                                                ...feature.properties,
                                                geometry: feature.geometry,
                                                editUrl: `/admin/infrastructures/${feature.properties.id}/edit`
                                            };
                                        });
                                    }
                                });
                                if (this.showInfrastructures) {
                                    this.infrastructuresLayer.addTo(this.map);
                                }
                            });
                    },

                    loadLandUses() {
                        if (this.landUsesLayer) {
                            this.map.removeLayer(this.landUsesLayer);
                        }
                        
                        fetch('{{ route("land_uses.geojson") }}')
                            .then(res => res.json())
                            .then(data => {
                                this.landUsesLayer = L.geoJSON(data.features || [], {
                                    style: (feature) => {
                                        const type = feature.properties.type;
                                        const color = type === 'rice_field' ? '#fbbf24' : type === 'plantation' ? '#84cc16' : '#f59e0b';
                                        return { color, weight: 2, fillColor: color, fillOpacity: 0.3 };
                                    },
                                    onEachFeature: (feature, layer) => {
                                        layer.feature = feature;
                                        layer.on('click', () => {
                                            this.selectedFeature = {
                                                ...feature.properties,
                                                geometry: feature.geometry,
                                                editUrl: `/admin/land-uses/${feature.properties.id}/edit`
                                            };
                                        });
                                    }
                                });
                                if (this.showLandUses) {
                                    this.landUsesLayer.addTo(this.map);
                                }
                            });
                    },

                    loadPlaces() {
                        if (this.placesLayer) {
                            this.map.removeLayer(this.placesLayer);
                        }
                        
                        fetch('{{ route("places.geojson") }}')
                            .then(res => res.json())
                            .then(data => {
                                this.placesLayer = L.geoJSON(data.features || [], {
                                    pointToLayer: (feature, latlng) => {
                                        const color = feature.properties.category?.color || '#2563eb';
                                        const marker = L.marker(latlng, {
                                            icon: L.divIcon({
                                                className: 'custom-marker',
                                                html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                                                iconSize: [20, 20]
                                            })
                                        });
                                        marker.feature = feature;
                                        return marker;
                                    },
                                    onEachFeature: (feature, layer) => {
                                        layer.feature = feature;
                                        layer.on('click', () => {
                                            this.selectedFeature = {
                                                ...feature.properties,
                                                geometry: feature.geometry,
                                                editUrl: `/admin/places/${feature.properties.id}/edit`
                                            };
                                        });
                                    }
                                });
                                if (this.showPlaces) {
                                    this.placesLayer.addTo(this.map);
                                }
                            });
                    },

                    loadRoads() {
                        // Filter roads from infrastructures (already loaded)
                        this.roads = this.infrastructures.filter(i => i.type === 'road');
                    },

                    toggleLayer(type) {
                        switch(type) {
                            case 'boundaries':
                                if (this.showBoundaries) {
                                    this.boundariesLayer?.addTo(this.map);
                                } else {
                                    this.map.removeLayer(this.boundariesLayer);
                                }
                                break;
                            case 'infrastructures':
                                if (this.showInfrastructures) {
                                    this.infrastructuresLayer?.addTo(this.map);
                                } else {
                                    this.map.removeLayer(this.infrastructuresLayer);
                                }
                                break;
                            case 'landuses':
                                if (this.showLandUses) {
                                    this.landUsesLayer?.addTo(this.map);
                                } else {
                                    this.map.removeLayer(this.landUsesLayer);
                                }
                                break;
                            case 'places':
                                if (this.showPlaces) {
                                    this.placesLayer?.addTo(this.map);
                                } else {
                                    this.map.removeLayer(this.placesLayer);
                                }
                                break;
                        }
                    },

                    redoStack: [],
                    isRedoing: false,

                    startDrawing(type) {
                        // Disable previous drawing if any
                        if (this.currentDrawHandler) {
                            this.currentDrawHandler.disable();
                        }
                        
                        this.drawingMode = type;
                        this.redoStack = [];
                        
                        if (type === 'point') {
                            // Point drawing handled by map click - already set up in initMapEvents
                            this.map.getContainer().style.cursor = 'crosshair';
                        } else if (type === 'line' || type === 'polygon') {
                            const options = type === 'line' 
                                ? { shapeOptions: { color: '#3388ff', weight: 4 } }
                                : { shapeOptions: { color: '#3388ff', fillColor: '#3388ff', fillOpacity: 0.2 } };
                                
                            const HandlerClass = type === 'line' ? L.Draw.Polyline : L.Draw.Polygon;
                            this.currentDrawHandler = new HandlerClass(this.map, options);
                            
                            // Monkey-patch deleteLastVertex to save to redo stack
                            const originalDelete = this.currentDrawHandler.deleteLastVertex;
                            this.currentDrawHandler.deleteLastVertex = () => {
                                const markers = this.currentDrawHandler._markers;
                                if (markers && markers.length > 0) {
                                    const lastMarker = markers[markers.length - 1];
                                    this.redoStack.push(lastMarker.getLatLng());
                                }
                                originalDelete.call(this.currentDrawHandler);
                            };

                            // Monkey-patch addVertex to clear redo stack (unless redoing)
                            const originalAddVertex = this.currentDrawHandler.addVertex;
                            this.currentDrawHandler.addVertex = (latlng) => {
                                if (!this.isRedoing) {
                                    this.redoStack = [];
                                }
                                originalAddVertex.call(this.currentDrawHandler, latlng);
                            };

                            this.currentDrawHandler.enable();
                        }
                    },

                    redoPoint() {
                        console.log('Redo triggered');
                        if (this.currentDrawHandler && this.redoStack.length > 0) {
                            const latlng = this.redoStack.pop();
                            this.isRedoing = true;
                            this.currentDrawHandler.addVertex(latlng);
                            this.isRedoing = false;
                            console.log('Redo successful', latlng);
                        } else {
                            console.log('Redo failed: stack empty or no handler');
                        }
                    },

                    undoPoint() {
                        console.log('Undo triggered');
                        if (this.currentDrawHandler && this.currentDrawHandler.deleteLastVertex) {
                            this.currentDrawHandler.deleteLastVertex();
                            console.log('Undo successful');
                        } else {
                            console.log('Undo failed: no handler or deleteLastVertex method');
                        }
                    },

                    initKeyboardShortcuts() {
                        document.addEventListener('keydown', (e) => {
                            if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                                e.preventDefault();
                                this.undoPoint();
                            }
                            if ((e.ctrlKey || e.metaKey) && e.key === 'y') {
                                e.preventDefault();
                                this.redoPoint();
                            }
                        });
                    },

                    addPoint(latlng) {
                        const marker = L.marker(latlng);
                        marker.addTo(this.drawnItems);
                        
                        const geometry = {
                            type: 'Point',
                            coordinates: [latlng.lng, latlng.lat]
                        };
                        const geometryJson = JSON.stringify(geometry);
                        
                        // Determine create URL based on active tab
                        let createUrl = '/admin/';
                        if (this.activeTab === 'place') {
                            createUrl += 'places/create';
                        } else {
                            // Default to places for point
                            createUrl += 'places/create';
                        }
                        
                        sessionStorage.setItem('newFeatureGeometry', geometryJson);
                        window.location.href = createUrl;
                    },

                    clearDrawing() {
                        this.drawnItems.clearLayers();
                        this.drawingMode = null;
                        if (this.currentDrawHandler) {
                            this.currentDrawHandler.disable();
                            this.currentDrawHandler = null;
                        }
                        this.map.getContainer().style.cursor = '';
                    },

                    toggleMeasure() {
                        this.measureMode = !this.measureMode;
                        // TODO: Implement measurement tool
                    },

                    handleFileUpload(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            try {
                                const geojson = JSON.parse(e.target.result);
                                const layer = L.geoJSON(geojson);
                                
                                // Store the first feature's geometry for saving
                                if (geojson.features && geojson.features.length > 0) {
                                    this.uploadedGeoJSON = geojson.features[0].geometry;
                                } else if (geojson.type === 'Feature') {
                                    this.uploadedGeoJSON = geojson.geometry;
                                } else if (geojson.type === 'FeatureCollection' && geojson.features.length > 0) {
                                     this.uploadedGeoJSON = geojson.features[0].geometry;
                                } else {
                                    // Direct geometry object
                                    this.uploadedGeoJSON = geojson; 
                                }

                                layer.eachLayer((l) => {
                                    this.drawnItems.addLayer(l);
                                });
                                
                                // Zoom to uploaded features
                                if (layer.getBounds().isValid()) {
                                    this.map.fitBounds(layer.getBounds());
                                }
                                
                                // Reset input
                                event.target.value = '';
                                
                                alert('File GeoJSON berhasil diupload! Klik "Simpan Fitur" untuk menyimpan.');
                            } catch (error) {
                                console.error('Error parsing GeoJSON:', error);
                                alert('Gagal membaca file GeoJSON. Pastikan format file benar.');
                                this.uploadedGeoJSON = null;
                            }
                        };
                        reader.readAsText(file);
                    },

                    saveUploadedFeature() {
                        if (!this.uploadedGeoJSON) return;

                        const geometry = this.uploadedGeoJSON;
                        const geometryJson = JSON.stringify(geometry);
                        
                        // Determine create URL based on geometry type
                        let createUrl = '/admin/';
                        
                        if (geometry.type === 'Polygon' || geometry.type === 'MultiPolygon') {
                            createUrl += 'boundaries/create';
                        } else if (geometry.type === 'LineString' || geometry.type === 'MultiLineString') {
                            createUrl += 'infrastructures/create';
                        } else if (geometry.type === 'Point') {
                            createUrl += 'places/create';
                        } else {
                            // Default fallback
                            createUrl += 'boundaries/create';
                        }
                        
                        // Store geometry in sessionStorage and redirect
                        sessionStorage.setItem('newFeatureGeometry', geometryJson);
                        window.location.href = createUrl;
                    },

                    focusOnFeature(item, type) {
                        // Try to find layer in map first
                        let foundLayer = null;
                        let geometry = item.geometry;
                        
                        // Search in loaded layers
                        const searchInLayer = (geoJsonLayer) => {
                            if (!geoJsonLayer) return;
                            geoJsonLayer.eachLayer((layer) => {
                                const feature = layer.feature;
                                if (feature && feature.properties && feature.properties.id == item.id) {
                                    foundLayer = layer;
                                    geometry = feature.geometry;
                                }
                            });
                        };
                        
                        searchInLayer(this.boundariesLayer);
                        searchInLayer(this.infrastructuresLayer);
                        searchInLayer(this.landUsesLayer);
                        searchInLayer(this.placesLayer);
                        
                        // If not found and it's a point, create geometry from lat/lng
                        if (!geometry && item.latitude && item.longitude) {
                            geometry = {
                                type: 'Point',
                                coordinates: [item.longitude, item.latitude]
                            };
                        }
                        
                        // Focus map on feature
                        if (foundLayer) {
                            if (foundLayer.getLatLng) {
                                // It's a marker/point
                                this.map.setView(foundLayer.getLatLng(), 16);
                            } else if (foundLayer.getBounds) {
                                // It's a polyline or polygon
                                this.map.fitBounds(foundLayer.getBounds(), { padding: [50, 50] });
                            }
                            // Highlight the layer temporarily
                            if (foundLayer.setStyle) {
                                const originalStyle = foundLayer.options;
                                foundLayer.setStyle({ weight: 5, color: '#ff0000' });
                                setTimeout(() => {
                                    if (foundLayer && foundLayer.setStyle) {
                                        foundLayer.setStyle(originalStyle);
                                    }
                                }, 2000);
                            }
                        } else if (geometry) {
                            // Fallback: create temporary layer to focus
                            if (geometry.type === 'Point') {
                                const [lng, lat] = geometry.coordinates;
                                this.map.setView([lat, lng], 16);
                            } else if (geometry.type === 'LineString') {
                                const latlngs = geometry.coordinates.map(coord => [coord[1], coord[0]]);
                                const polyline = L.polyline(latlngs);
                                this.map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
                                polyline.remove();
                            } else if (geometry.type === 'Polygon') {
                                const latlngs = geometry.coordinates[0].map(coord => [coord[1], coord[0]]);
                                const polygon = L.polygon(latlngs);
                                this.map.fitBounds(polygon.getBounds(), { padding: [50, 50] });
                                polygon.remove();
                            }
                        }
                        
                        this.selectedFeature = {
                            ...item,
                            geometry: geometry,
                            editUrl: this.getEditUrl(item, type)
                        };
                    },

                    getEditUrl(item, type) {
                        const baseUrl = '/admin/';
                        switch(type) {
                            case 'boundary':
                                return `${baseUrl}boundaries/${item.id}/edit`;
                            case 'infrastructure':
                            case 'road':
                                return `${baseUrl}infrastructures/${item.id}/edit`;
                            case 'landuse':
                                return `${baseUrl}land-uses/${item.id}/edit`;
                            case 'place':
                                return `${baseUrl}places/${item.id}/edit`;
                            default:
                                return '#';
                        }
                    },

                    deleteFeature(feature) {
                        console.log('deleteFeature called', feature);
                        this.featureToDelete = feature;
                        this.showDeleteModal = true;
                        console.log('showDeleteModal set to true');
                    },

                    confirmDelete() {
                        if (!this.featureToDelete) return;

                        const url = this.featureToDelete.editUrl.replace('/edit', '');
                        
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => {
                            if (res.ok) {
                                this.selectedFeature = null;
                                this.showDeleteModal = false;
                                this.featureToDelete = null;
                                this.loadAllLayers();
                                // Optional: Show success toast
                            } else {
                                alert('Gagal menghapus fitur. Silakan coba lagi.');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan saat menghapus fitur.');
                        });
                    },

                    handleNewFeature(layer) {
                        const geometry = layer.toGeoJSON().geometry;
                        const geometryJson = JSON.stringify(geometry);
                        
                        // Determine create URL based on active tab
                        let createUrl = '/admin/';
                        const tab = this.activeTab;
                        
                        if (tab === 'boundary') {
                            createUrl += 'boundaries/create';
                        } else if (tab === 'infrastructure' || tab === 'road') {
                            createUrl += 'infrastructures/create';
                        } else if (tab === 'landuse') {
                            createUrl += 'land-uses/create';
                        } else if (tab === 'place') {
                            createUrl += 'places/create';
                        } else {
                            // Default based on geometry type
                            if (geometry.type === 'Polygon') {
                                createUrl += 'boundaries/create';
                            } else if (geometry.type === 'LineString') {
                                createUrl += 'infrastructures/create';
                            } else {
                                createUrl += 'places/create';
                            }
                        }
                        
                        // Store geometry in sessionStorage and redirect
                        sessionStorage.setItem('newFeatureGeometry', geometryJson);
                        window.location.href = createUrl;
                    }
                }
            }
        </script>
    @endPushOnce
</x-app-layout>

