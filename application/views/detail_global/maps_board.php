<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<div class="map-area mb-4">
    <div id="map-board"></div>
    <div class="map-left-overlay">
        <?php $this->load->view("edit/maps_toolbar") ?>
    </div>
    <div class="map-right-panel">
        <div class="map-form-box w-100">
            <?php $this->load->view('detail_global/form'); ?>
        </div>
    </div>
</div>

<style>
    .main-wrap{
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
    }
    .content{
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        min-height: 0;
    }
    .map-area{
        position: relative;
        flex: 1;
        min-height: 0;
        border-radius: var(--roundedJumbo);
        overflow: hidden;
        border: 1.5px solid #e7e8ec;
        background: #1a1a2e;
        box-shadow: 0 8px 32px rgba(0,0,0,.08);
    }
    #map-board{
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }
    .map-left-overlay{
        position: absolute;
        top: 0;
        left: 0;
        width: 50%;
        height: 100%;
        z-index: 2;
        pointer-events: none;
    }
    .map-left-overlay > *{
        pointer-events: all;
    }
    .map-right-panel{
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        z-index: 2;
        display: flex;
        align-items: top;
        justify-content: center;
        padding: 1.25rem;
        pointer-events: none;
    }
    .map-form-box{
        pointer-events: all;
        border-radius: var(--roundedXLG);
    }
    .coord-label{
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #888;
    }
    .coord-value{
        font-size: .95rem;
        font-weight: 700;
        color: #1a1a2e;
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
    let userLat = getCookie('lat')
    let userLng = getCookie('long')
    let userMarker = null
    let pinMarkers = []

    const defaultLat = userLat ?? -6.2088
    const defaultLng = userLng ?? 106.8456

    const map = L.map('map-board', {
        zoomControl: false
    }).setView([defaultLat, defaultLng], 15)

    L.control.zoom({ position: 'bottomright' }).addTo(map)

    const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map)

    const placeUserMarker = (lat, lng) => {
        if (userMarker) map.removeLayer(userMarker)
        userMarker = L.circleMarker([lat, lng], {
            radius: 10,
            fillColor: 'var(--primaryColor)',
            color: '#ffffff',
            weight: 4,
            opacity: 1,
            fillOpacity: 1
        }).addTo(map)
    }

    const clearPinMarkers = () => {
        pinMarkers.forEach(marker => map.removeLayer(marker))
        pinMarkers = []
    }

    const renderAllPins = (pins) => {
        clearPinMarkers()
        let listPinEl = ''
        const holder = '#pin-list-holder'

        pins.forEach(dt => {
            const lat = parseFloat(dt.pin_lat)
            const lng = parseFloat(dt.pin_long)

            if (isNaN(lat) || isNaN(lng)) return

            const marker = L.marker([lat, lng]).addTo(map)
            marker.bindPopup(`
                <div class="place-popup">
                    <h3>${dt.pin_name}</h3>
                    <p class="popup-address">${dt.pin_address ?? '-'}</p>
                    <hr>
                    <div class="popup-info">
                        <div>
                            <span>Category</span>
                            <h5>${dt.pin_category}</h5>
                        </div>
                    </div>
                </div>
            `)

            pinMarkers.push(marker)

            const isFavoriteEl = dt.is_favorite == 1 ? "<span class='fav-dot'><i class='fa-solid fa-heart'></i></span>" : ""

            listPinEl += `
                <div class='activity-item mb-0' data-lat='${dt.pin_lat}' data-long='${dt.pin_long}'>
                    <div class='activity-thumb'>
                        <img src='https://lh3.googleusercontent.com/aida-public/AB6AXuB9TgxRWJZ1lxyBC2boJYHByBkeSaroy5x0M-AVvRCH_M7rWkJDFoVc1Lykvj4iQd7LMnmKIZdneHmRaXwFC9lv7_R60HRIGCVjEjIOXZfaa7J7VxkudxcVJ4rY3Rgs-ylDPPCviUANS--Z29u4nWUV66EasfeFSHxoNl_DXhJTkoVFcwXP083QKchtEh0pwmn4zKaOzhGVc1BczkDGjALzZe6T1f9_UaS1XcyhLg9yioAdJ4m4iYi-5GEJreWku7OO99GdpvvHlmjT' alt=''>
                        ${isFavoriteEl}
                    </div>
                    <div class='activity-info'>
                        <div class='activity-name pin-name'>${dt.pin_name}</div>
                        <div class='activity-tags'>
                            <span class='tag bg-success pin-address'><i class='fa-solid fa-location-dot'></i> ${dt.pin_address}</span>
                            <span class='tag bg-primary'>${dt.pin_category}</span>
                        </div>
                    </div>
                </div>
            `
        })

        if (pinMarkers.length > 0) {
            const group = L.featureGroup(pinMarkers)
            map.fitBounds(group.getBounds().pad(0.2))
            $(holder).html(listPinEl)
        }
    }

    $(document).ready(function () {
        setTimeout(() => map.invalidateSize(), 300)

        const applyLocation = (lat, lng) => {
            userLat = lat
            userLng = lng
            placeUserMarker(lat, lng)
        }

        const requestGeolocation = () => {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude
                const lng = pos.coords.longitude
                storeCookie('lat', lat)
                storeCookie('long', lng)
                applyLocation(lat, lng)
            })
        }

        if (userLat !== null && userLng !== null) {
            applyLocation(parseFloat(userLat), parseFloat(userLng))
        }

        navigator.permissions.query({ name: 'geolocation' }).then(permission => {
            if (permission.state === 'granted') {
                if (userLat === null || userLng === null) requestGeolocation()
            } else {
                Swal.fire({
                    icon: 'question',
                    title: 'Enable Location Access?',
                    text: 'Allow location access to show your current position on the map.',
                    showCancelButton: true,
                    confirmButtonText: 'Allow',
                    cancelButtonText: 'Later',
                    confirmButtonColor: '#635bff'
                }).then(result => {
                    if (result.isConfirmed && navigator.geolocation) requestGeolocation()
                })
            }
        })

        $('.map-type').on('click', function () {
            switchMapType($(this).data('type'), map, tileLayer)
        })

        $(document).on('click', '#focus-map-button', function () {
            if (userLat === null || userLng === null) return
            map.flyTo([parseFloat(userLat), parseFloat(userLng)], 15, { animate: true, duration: 0.8 })
        })
    })

    const fetchGlobalList = (id) => {
        $.ajax({
            url: `/api/v1/global_list/detail/${id}`,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                const data = response.data
                const detail = data.detail
                const pins = data.pin ?? []

                $('#list_name').val(detail.list_name)
                $('#list_desc').val(detail.list_desc)
                $('#global-list-name-text').text(`> ${detail.list_name}`)
                $('#created_at').text(datetimeText(detail.created_at))
                detail.updated_at && $('#updated_at').html(`<p class="text-muted text-sm">Updated at : ${datetimeText(detail.updated_at)}</p>`)

                renderAllPins(pins)
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
                $('.map-form-box').append(`
                    <div class="text-center py-3">
                        <span class="tag bg-danger"><i class="fa-solid fa-triangle-exclamation"></i> Failed fetch list</span>
                    </div>
                `)
            }
        })
    }

    let routingControl = null

    $(document).on('click', '.activity-item', function () {
        const lat = parseFloat($(this).data('lat'))
        const lng = parseFloat($(this).data('long'))
        routingControl = showDirection(map, routingControl, parseFloat(userLat), parseFloat(userLng), lat, lng)
    })

    fetchGlobalList('<?= $id ?>')
</script>