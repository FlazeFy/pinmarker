<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<div class="map-area">
    <div class="map-img-wrap">
        <div id="map-board"></div>
    </div>
    <div class="map-type-wrap">
        <div class="map-type-group">
            <button class="map-type active" data-type="default">Default</button>
            <button class="map-type" data-type="satellite">Satellite</button>
            <button class="map-type" data-type="terrain">Terrain</button>
        </div>
    </div>
    <div class="region-bar">
        <div class="region-focus">
            <i class="fa-solid fa-compass" style="color:var(--primaryColor);"></i>
            <div>
                <div class="region-label">Current Region Focus</div>
                <div class="region-desc">Loading map data...</div>
            </div>
        </div>
    </div>
</div>

<style>
    .map-area {
        position: relative;
        height: 70vh; 
        border-radius: var(--roundedJumbo);
        overflow: hidden;
        border: 1.5px solid #e7e8ec;
        background: #1a1a2e;
        box-shadow: 0 8px 32px rgba(0,0,0,.08);
    }
    .map-img-wrap {
        position: absolute;
        inset: 0;
    }
    #map-board {
        width: 100%;
        height: 100%;
    }
    .map-type-wrap {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
    .map-type-group {
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        border-radius: var(--roundedMD);
        padding: 4px;
        display: flex;
        gap: 2px;
        border: 1px solid rgba(199,196,216,.3);
        box-shadow: 0 4px 16px rgba(0,0,0,.1);
    }
    .map-type {
        padding: 7px 16px;
        border: none;
        border-radius: var(--roundedSM);
        font-size: var(--textXSM);
        font-weight: 700;
        font-family: inherit;
        color: #464555;
        background: transparent;
        cursor: pointer;
        transition: all .2s;
    }
    .map-type.active {
        background: var(--primaryColor);
        color: #fff;
        box-shadow: 0 3px 10px rgba(99,91,255,.3);
    }
    .map-type:hover:not(.active) {
        background: #f2f3f7;
    }
    .region-bar {
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
        z-index: 1000;
    }
    .region-focus {
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        border-radius: var(--roundedLG);
        padding: var(--spaceMD) var(--spaceXMD);
        display: inline-flex;
        gap: var(--spaceMD);
        align-items: flex-start;
        border: 1px solid rgba(199,196,216,.25);
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        max-width: 420px;
    }
    .region-label {
        font-size: var(--textXSM);
        font-weight: 700;
        color: var(--primaryColor);
        margin-bottom: 2px;
    }
    .region-desc {
        font-size: var(--textXSM);
        color: #464555;
        line-height: 1.5;
    }
    .custom-popup h6 {
        margin: 0;
        font-weight: 700;
    }
    .custom-popup p {
        margin: 4px 0 0;
        color: #666;
        font-size: 12px;
    }
    .leaflet-control-zoom {
        border-radius: var(--roundedMD) !important;
        overflow: hidden;
    }
    .leaflet-popup-content-wrapper {
        border-radius: var(--roundedMD);
    }
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    $(document).ready(function () {
        let map = L.map('map-board', {
            zoomControl: false
        }).setView([-6.21462, 106.84513], 11)

        L.control.zoom({
            position: 'bottomright'
        }).addTo(map)

        let tileLayer = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(map)

        const pins = [
            {
                name: 'Apollo Wu Artisan Roast',
                lat: -6.2284,
                lng: 106.8264
            },
        ]

        const bounds = []

        pins.forEach(pin => {
            bounds.push([pin.lat, pin.lng])

            L.marker([pin.lat, pin.lng]).addTo(map).bindPopup(`
                <div class="custom-popup">
                    <h6>${pin.name}</h6>
                    <p>Pinned Location</p>
                </div>
            `)
        })

        if (bounds.length) map.fitBounds(bounds, { padding: [50, 50]})

        $('.region-desc').text(`${pins.length} active pins detected in this viewport.`)

        $('.map-type').on('click', function () {
            $('.map-type').removeClass('active')
            $(this).addClass('active')

            map.removeLayer(tileLayer)

            const type = $(this).data('type')

            if (type === 'satellite') {
                tileLayer = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                    {
                        attribution: '&copy; Esri'
                    }
                )
            } else if (type === 'terrain') {
                tileLayer = L.tileLayer(
                    'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
                    {
                        attribution: '&copy; OpenTopoMap'
                    }
                )
            } else {
                tileLayer = L.tileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    {
                        attribution: '&copy; OpenStreetMap contributors'
                    }
                )
            }

            tileLayer.addTo(map)
        })

        setTimeout(() => {
            map.invalidateSize()
        }, 100)
    })
</script>