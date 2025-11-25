@php
    $initialCoordinates = [
        'lat' => (float) old('latitude', $place->latitude ?? -6.7289),
        'lng' => (float) old('longitude', $place->longitude ?? 110.7485),
    ];
@endphp

@pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endPushOnce

@pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('placeForm', (initial) => ({
                coords: { lat: initial.lat, lng: initial.lng },
                map: null,
                marker: null,

                init() {
                    this.initMap();
                },

                initMap() {
                    if (!this.$refs.mapContainer) {
                        return;
                    }

                    this.map = L.map(this.$refs.mapContainer).setView([this.coords.lat, this.coords.lng], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    this.marker = L.marker([this.coords.lat, this.coords.lng]).addTo(this.map);

                    this.map.on('click', (event) => {
                        const { lat, lng } = event.latlng;
                        this.setCoordinates(lat, lng);
                    });
                },

                setCoordinates(lat, lng) {
                    this.coords = {
                        lat: Number(lat).toFixed(6),
                        lng: Number(lng).toFixed(6)
                    };

                    if (this.marker) {
                        this.marker.setLatLng([this.coords.lat, this.coords.lng]);
                    }

                    if (this.map) {
                        this.map.setView([this.coords.lat, this.coords.lng], this.map.getZoom());
                    }
                }
            }));
        });
    </script>
@endPushOnce

<section
    x-data="placeForm(@js($initialCoordinates))"
    x-init="init()"
    x-effect="
        if (map && marker) {
            const lat = Number(coords.lat);
            const lng = Number(coords.lng);
            if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                marker.setLatLng([lat, lng]);
            }
        }
    "
>
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lokasi</label>
                    <input type="text" name="name" value="{{ old('name', $place->name) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $place->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $place->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                        <input type="text" name="latitude" x-model="coords.lat" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                        <input type="text" name="longitude" x-model="coords.lng" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Lokasi</label>
                    @if($place->image_path)
                        <div class="mb-3">
                            <img src="{{ asset($place->image_path) }}" alt="{{ $place->name }}" class="w-48 h-32 object-cover rounded-lg border">
                        </div>
                    @endif
                    <input type="file" name="image" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    <p class="text-xs text-gray-500 mt-2">Format JPG/PNG, maksimal 2MB.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">Pilih Titik Koordinat</h4>
                            <p class="text-xs text-gray-500">Klik pada peta untuk mengisi latitude & longitude secara otomatis.</p>
                        </div>
                        <button type="button" class="text-xs text-blue-600 hover:text-blue-800 font-semibold" @click="setCoordinates({{ $initialCoordinates['lat'] }}, {{ $initialCoordinates['lng'] }})">
                            Reset Titik
                        </button>
                    </div>
                    <div x-ref="mapContainer" class="w-full h-80 rounded-2xl overflow-hidden border border-gray-200"></div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.places.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</section>

