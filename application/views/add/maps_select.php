<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<div class="map-area">
    <div class="map-img-wrap">
        <div id="map-board"></div>
    </div>
    <?php $this->load->view("add/maps_toolbar") ?>
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

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let userLat = getCookie('lat')
    let userLng = getCookie('long')

    const map = L.map('map-board', {
        zoomControl: false
    }).setView([userLat ?? -2.5, userLng ?? 118], userLat ? 13 : 5)

    L.control.zoom({ position: 'bottomright' }).addTo(map)

    let tileLayer = L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        { attribution: '&copy; OpenStreetMap contributors' }
    ).addTo(map)

    let marker = null
    let userMarker = null

    const placeMarker = (latLng) => {
        if (marker) {
            map.removeLayer(marker)
        }

        marker = L.marker([latLng.lat, latLng.lng]).addTo(map)
    }

    const placeUserMarker = (lat, lng) => {
        if (userMarker) {
            map.removeLayer(userMarker)
        }

        userMarker = L.circleMarker([lat, lng], {
            radius: 10,
            fillColor: 'var(--primaryColor)',
            color: '#ffffff',
            weight: 4,
            opacity: 1,
            fillOpacity: 1
        }).addTo(map)
    }

    const onMapClick = (e) => {
        placeMarker(e.latlng)
        addContentCoor(e.latlng, 'pin_lat', 'pin_long')
        check_nearest_pin()
    }

    const initMap = () => {
        map.on('click', onMapClick)

        setTimeout(() => {
            map.invalidateSize()
        }, 300)
    }

    $(document).on('click', '#focus-map-button', function () {
        map.setView([userLat, userLng], 13)
    })

    $(document).on('click','.recommended-marker-btn', function(){
        const pinName = $(this).data('pin-name')
        const pinLat = $(this).data('pin-lat')
        const pinLong = $(this).data('pin-long')

        $('#pin_name').val(pinName)
        $('#pin_lat').val(pinLat)
        $('#pin_long').val(pinLong)
        
        placeMarker({ lat: pinLat, lng: pinLong})
    })

    $(document).ready(function () {
        initMap()

        navigator.permissions.query({ name: 'geolocation' }).then(permission => {
            if (permission.state === 'granted') {
                if (userLat === null && userLng === null) {
                    navigator.geolocation.getCurrentPosition(position => {
                        userLat = position.coords.latitude
                        userLng = position.coords.longitude
                        storeCookie('lat', userLat)
                        storeCookie('long', userLng)
                        placeUserMarker(userLat, userLng)
                        map.setView([userLat, userLng], 13)
                    })
                } else {
                    placeUserMarker(userLat, userLng)
                }
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
                    if (result.isConfirmed && navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            userLat = position.coords.latitude
                            userLng = position.coords.longitude
                            storeCookie('lat', userLat)
                            storeCookie('long', userLng)
                            placeUserMarker(userLat, userLng)
                            map.setView([userLat, userLng], 13)
                        })
                    }
                })
            }
        })

        $('.map-type').on('click', function () {
            const type = $(this).data('type')
            switchMapType(type, map, tileLayer)
        })
    })
</script>