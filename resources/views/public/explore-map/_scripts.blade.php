{{-- Alpine.js Map Component Script --}}
<script>
    function mapComponent() {
        return {
            map: null,
            loading: true,
            sidebarOpen: true,
            activeTab: 'places',
            currentBaseLayer: 'satellite',
            baseLayers: {},
            categories: @json($categories),
            selectedCategories: [],
            showBoundaries: true,
            sortByDistance: false,

            allPlaces: [],
            geoFeatures: [],
            searchQuery: '',
            searchResults: [],
            selectedFeature: null,
            markers: [],
            boundariesLayer: null,
            routingControl: null,
            
            // Proximity Alert State
            nearbyAlert: null,
            notifiedPlaces: new Set(),
            watchId: null,
            
            // Navigation Mode State
            isNavigating: false,
            heading: 0,
            wakeLock: null,

            defaultCenter: [-6.59, 110.68],
            defaultZoom: 10,
            userMarker: null,
            userLocation: null,

            get visiblePlaces() {
                const ids = this.selectedCategories.length > 0 ? this.selectedCategories : this.categories.map(c => c.id);
                let places = this.allPlaces.filter(p => ids.includes(p.properties.category?.id))
                     .map(p => ({
                         ...p.properties,
                         image_path: p.properties.image_path,
                         category: p.properties.category,
                         latitude: p.geometry.coordinates[1],
                         longitude: p.geometry.coordinates[0],
                         distance: this.calculateDistance(p.geometry.coordinates[1], p.geometry.coordinates[0])
                     }));
                
                if (this.sortByDistance) {
                    places.sort((a, b) => (a.distance || Infinity) - (b.distance || Infinity));
                }
                
                return places;
            },

            init() {
                this.selectedCategories = this.categories.map(c => c.id);
                this.initMap();
                this.fetchAllData();
                
                this.$watch('selectedCategories', () => this.updateMapMarkers());
                this.$watch('showBoundaries', () => this.loadBoundaries());
                
                // Device Orientation for Compass
                if (window.DeviceOrientationEvent) {
                    window.addEventListener('deviceorientation', (e) => this.handleOrientation(e));
                }

                if (window.innerWidth < 1024) { this.sidebarOpen = false; }
            },
            
            handleOrientation(event) {
                if (!this.isNavigating) return;
                
                let heading = event.alpha; 
                if (event.webkitCompassHeading) {
                    heading = event.webkitCompassHeading;
                }
                
                this.heading = heading;
                
                if (this.userMarker) {
                     const icon = this.userMarker.getElement();
                     if (icon) {
                         const arrow = icon.querySelector('.user-arrow');
                         if (arrow) {
                             arrow.style.transform = `rotate(${heading}deg)`;
                         }
                     }
                }
            },
            
            async toggleLiveNavigation() {
                this.isNavigating = !this.isNavigating;
                
                if (this.isNavigating) {
                    this.sidebarOpen = false;
                    this.selectedFeature = null;
                    this.map.closePopup();
                    
                    try {
                        if ('wakeLock' in navigator) {
                            this.wakeLock = await navigator.wakeLock.request('screen');
                        }
                    } catch (err) { console.log('Wake Lock error:', err); }
                    
                    this.locateUser(() => {
                        this.map.setZoom(19);
                    }, true);
                    
                } else {
                    if (this.wakeLock) {
                        this.wakeLock.release();
                        this.wakeLock = null;
                    }
                    this.map.setZoom(15);
                }
            },

            toggleCategory(id) {
                if (this.selectedCategories.includes(id)) {
                    this.selectedCategories = this.selectedCategories.filter(c => c !== id);
                } else {
                    this.selectedCategories.push(id);
                }
            },
            
            toggleSortNearby() {
                if (!this.userLocation) {
                    this.locateUser(() => {
                        this.sortByDistance = !this.sortByDistance;
                    });
                } else {
                    this.sortByDistance = !this.sortByDistance;
                }
            },

            calculateDistance(lat2, lon2) {
                if (!this.userLocation) return null;
                const lat1 = this.userLocation.lat;
                const lon1 = this.userLocation.lng;
                const R = 6371; // km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                        Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return (R * c).toFixed(1);
            },

            initMap() {
                this.map = L.map('leaflet-map', { zoomControl: false, attributionControl: false }).setView(this.defaultCenter, this.defaultZoom);
                
                const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] });
                const googleSatellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] });

                this.baseLayers = { 'streets': googleStreets, 'satellite': googleSatellite };
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
                    const [places, boundaries] = await Promise.all([
                        fetch('{{ route('places.geojson') }}').then(r => r.json()),
                        fetch('{{ route('boundaries.geojson') }}').then(r => r.json())
                    ]);

                    this.geoFeatures = places.features || [];
                    this.allPlaces = places.features || [];
                    
                    this.boundariesFeatures = boundaries.features || [];
                    this.loadBoundaries();
                    
                    this.updateMapMarkers();

                } catch (e) {
                    console.error('Error loading data:', e);
                } finally {
                    this.loading = false;
                }
            },

            updateLayers() {
                 // Handled by watchers now
            },

            loadBoundaries() {
                if (this.boundariesLayer) this.map.removeLayer(this.boundariesLayer);
                if (!this.showBoundaries) return;
                this.boundariesLayer = L.geoJSON(this.boundariesFeatures, {
                    style: { color: '#10b981', weight: 2, fillColor: '#10b981', fillOpacity: 0.1, dashArray: '5, 5' },
                    onEachFeature: (f, l) => {
                        l.on('click', (e) => { L.DomEvent.stop(e); this.selectFeature({...f.properties, type: 'Batas Wilayah'}); });
                    }
                }).addTo(this.map);
            },

            updateMapMarkers() {
                this.markers.forEach(m => this.map.removeLayer(m));
                this.markers = [];
                
                const visible = this.visiblePlaces;
                
                visible.forEach(p => {
                     const color = p.category?.color || '#3b82f6';
                     const iconHtml = `
                        <div class="w-9 h-9 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white text-sm custom-marker" style="background-color: ${color}">
                            <i class="${p.category?.icon_class ?? 'fa-solid fa-map-marker-alt'}"></i>
                        </div>
                    `;
                    const marker = L.marker([p.latitude, p.longitude], {
                         icon: L.divIcon({ html: iconHtml, className: '', iconSize: [36, 36], iconAnchor: [18, 18] })
                    });
                    marker.on('click', () => { this.selectPlace(p); });
                    marker.addTo(this.map);
                    this.markers.push(marker);
                });
            },

            performSearch() {
                if (this.searchQuery.length < 2) { this.searchResults = []; return; }
                const q = this.searchQuery.toLowerCase();
                this.searchResults = this.allPlaces.filter(p => p.properties.name.toLowerCase().includes(q))
                    .map(p => ({ ...p.properties, type: 'Lokasi', latitude: p.geometry.coordinates[1], longitude: p.geometry.coordinates[0] }))
                    .slice(0, 5);
            },

            selectFeature(feat) {
                this.selectedFeature = feat;
                this.zoomToFeature(feat);
            },

            selectPlace(place) {
                 this.selectedFeature = {
                    ...place,
                    type: 'Lokasi',
                    image_url: place.image_url || (place.image_path ? '{{ url('/') }}/' + place.image_path : null)
                };
                this.zoomToFeature(place);
            },

            zoomToFeature(feature) {
                if (feature.latitude && feature.longitude) {
                    this.map.flyTo([feature.latitude, feature.longitude], 18);
                } else if (feature.geometry) {
                     const layer = L.geoJSON(feature);
                     this.map.fitBounds(layer.getBounds(), { padding: [100, 100] });
                }
            },
            
            startRouting(destination) {
                if (!this.userLocation) {
                    this.locateUser(() => this.calculateRoute(destination));
                } else {
                    this.calculateRoute(destination);
                }
            },
            
            calculateRoute(destination) {
                if (this.routingControl) {
                    this.map.removeControl(this.routingControl);
                }
                
                this.routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(this.userLocation.lat, this.userLocation.lng),
                        L.latLng(destination.latitude, destination.longitude)
                    ],
                    routeWhileDragging: true,
                    lineOptions: {
                        styles: [{color: '#6FA1EC', opacity: 0.8, weight: 6}]
                    },
                    show: true,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true,
                    createMarker: function() { return null; },
                    containerClassName: 'routing-container custom-scrollbar'
                }).addTo(this.map);
                
                setTimeout(() => {
                    const container = this.routingControl.getContainer();
                    if (container) container.style.display = 'none';
                }, 100);
                
                if (window.innerWidth < 1024) { this.sidebarOpen = false; }
            },

            openGoogleMaps(destination) {
                if (!this.userLocation) {
                    alert('Lokasi anda belum terdeteksi.');
                    return;
                }
                const url = `https://www.google.com/maps/dir/?api=1&destination=${destination.latitude},${destination.longitude}&travelmode=driving`;
                window.open(url, '_blank');
            },
            
            toggleNavigationInstructions() {
                 if (!this.routingControl) return;
                 const container = this.routingControl.getContainer();
                 if (container) {
                     if (container.style.display === 'none') {
                         container.style.display = 'block';
                     } else {
                         container.style.display = 'none';
                     }
                 }
            },

            locateUser(callback = null, forceFollow = false) {
                if (!navigator.geolocation) { alert('Browser tidak mendukung geolokasi'); return; }
                this.loading = true;
                
                if (this.watchId) navigator.geolocation.clearWatch(this.watchId);
                
                this.watchId = navigator.geolocation.watchPosition(
                    (pos) => {
                        const { latitude, longitude } = pos.coords;
                        this.userLocation = { lat: latitude, lng: longitude };
                        
                        const compassHtml = `
                            <div class="relative w-12 h-12 flex items-center justify-center">
                                <div class="user-arrow w-0 h-0 border-l-[6px] border-l-transparent border-r-[6px] border-r-transparent border-b-[16px] border-b-blue-600 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 transition-transform duration-300 origin-center" style="transform: rotate(${this.heading}deg)"></div>
                                <div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-md relative z-10 pulse"></div>
                                <div class="absolute inset-0 bg-blue-500/10 rounded-full animate-ping"></div>
                            </div>
                        `;

                        if (this.userMarker) {
                            this.userMarker.setLatLng([latitude, longitude]);
                        } else {
                            this.userMarker = L.marker([latitude, longitude], {
                                icon: L.divIcon({ html: compassHtml, className: '', iconSize: [48, 48], iconAnchor: [24, 24] })
                            }).addTo(this.map);
                        }
                        
                        if (this.isNavigating || forceFollow || this.loading) {
                            this.map.flyTo([latitude, longitude], this.isNavigating ? 18 : 17, { animate: true, duration: 1 });
                        }
                        
                        this.loading = false;
                        if (callback) callback();
                        
                        this.checkProximity(latitude, longitude);
                    },
                    (err) => { 
                        this.loading = false; 
                        console.error(err);
                    },
                    { enableHighAccuracy: true, maximumAge: 1000, timeout: 5000 }
                );
            },
            
            checkProximity(lat, lng) {
                const threshold = 0.5; // 500 meters
                
                this.allPlaces.forEach(place => {
                    if (this.notifiedPlaces.has(place.properties.name)) return;
                    
                    const dist = this.calculateDistance(place.geometry.coordinates[1], place.geometry.coordinates[0]);
                    if (dist && parseFloat(dist) <= threshold) {
                        this.nearbyAlert = {
                            ...place.properties,
                            image_url: place.properties.image_path ? '{{ url('/') }}/' + place.properties.image_path : null
                        };
                        this.notifiedPlaces.add(place.properties.name);
                        
                        if (navigator.vibrate) navigator.vibrate(200);
                    }
                });
            }
        };
    }
</script>
