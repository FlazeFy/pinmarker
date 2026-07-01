<div class="map-area">
    <div class="map-img-wrap">
        <div id="map-board"></div>
    </div>
    <?php $this->load->view("detail/maps_toolbar") ?>
</div>

<style>
    .map-area{
        position: relative;
        height: 100%;
        border-radius: var(--roundedJumbo);
        overflow: hidden;
        border: 1.5px solid #e7e8ec;
        background: #1a1a2e;
        box-shadow: 0 8px 32px rgba(0,0,0,.08);
    }
    .map-img-wrap{
        position: absolute;
        inset: 0;
    }
    #map-board{
        width: 100%;
        height: 100%;
    }
    .leaflet-control-zoom{
        border-radius: var(--roundedMD)!important;
        overflow: hidden;
    }
    .leaflet-popup-content-wrapper{
        border-radius: var(--roundedMD);
    }
</style>

<script>
    const LOCATION_COORDS = {
        lat: <?= $dt_detail_pin->pin_lat ?>,
        lng: <?= $dt_detail_pin->pin_long ?>,
        name: "Main Location",
        category: "Default Category"
    }

    const map = L.map('map-board', {
        zoomControl: false
    })

    L.control.zoom({ position: 'bottomright' }).addTo(map)

    let tileLayer = L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        { attribution: '&copy; OpenStreetMap contributors' }
    ).addTo(map)

    let marker = null

    const renderLocation = () => {
        if (marker) map.removeLayer(marker)

        marker = L.marker([
            LOCATION_COORDS.lat,
            LOCATION_COORDS.lng
        ]).addTo(map)

        map.setView([LOCATION_COORDS.lat, LOCATION_COORDS.lng], 13)
    }

    const initMap = () => {
        renderLocation()

        setTimeout(() => {
            map.invalidateSize()
        }, 300)
    }

    initMap()

    $('.map-type').on('click', function () {
        const type = $(this).data('type')
        switchMapType(type, map, tileLayer)
        addUrlParam('map_type', type)
        renderLocation()
    })

    // Validate query param
    const validateParams = () => !['default','satellite','terrain'].includes(map_type) ? removeUrlParam('map_type') : switchMapType(map_type, map, tileLayer)
    
    $(document).ready(function () {
        validateParams()
    })
</script>