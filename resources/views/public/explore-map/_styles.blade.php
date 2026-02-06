{{-- Inline Styles for Explore Map --}}
<style>
    /* Custom scrollbar for sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 20px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #4b5563; }
    
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filled-icon { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    
    #leaflet-map { height: 100%; width: 100%; z-index: 0; }
    [x-cloak] { display: none !important; }
    
    /* Marker Animations */
    .custom-marker { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .custom-marker:hover { transform: scale(1.25); z-index: 1000 !important; }
</style>

{{-- Routing Container Styles --}}
<style>
    /* Custom Styling for Leaflet Routing Machine */
    .leaflet-routing-container {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 1rem;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        max-height: 40vh;
        overflow-y: auto;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1px solid rgba(0,0,0,0.05);
        width: 320px !important;
        
        /* Positioning override */
        position: absolute !important;
        top: 20px !important;
        right: 20px !important;
        z-index: 9999 !important;
        display: none;
    }
    .dark .leaflet-routing-container {
        background-color: rgba(44, 41, 35, 0.95);
        color: #eceae4;
        border-color: rgba(255,255,255,0.1);
    }
    .leaflet-routing-alt {
        max-height: 100%;
    }
    .leaflet-routing-alt tr:hover {
        background-color: rgba(0,0,0,0.03);
    }
    .dark .leaflet-routing-alt tr:hover {
        background-color: rgba(255,255,255,0.05);
    }
</style>
