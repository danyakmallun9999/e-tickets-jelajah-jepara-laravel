{{-- Inline Styles for Explore Map --}}
<style>
    /* Custom scrollbar for sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
    
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
        background-color: rgba(255, 255, 255, 0.98);
        padding: 1rem;
        border-radius: 1rem;
        max-height: 40vh;
        overflow-y: auto;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1px solid #e2e8f0;
        width: 320px !important;
        
        /* Positioning override */
        position: absolute !important;
        top: 20px !important;
        right: 20px !important;
        z-index: 9999 !important;
        display: none;
    }
    .dark .leaflet-routing-container {
        background-color: rgba(30, 41, 59, 0.98);
        color: #f1f5f9;
        border-color: #334155;
    }
    .leaflet-routing-alt {
        max-height: 100%;
    }
    .leaflet-routing-alt tr:hover {
        background-color: rgba(14, 165, 233, 0.05);
    }
    .dark .leaflet-routing-alt tr:hover {
        background-color: rgba(14, 165, 233, 0.1);
    }
</style>
