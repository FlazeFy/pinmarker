<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<div class="map-area mb-4">
    <div class="map-img-wrap">
        <div id="map-board"></div>
    </div>
    <?php $this->load->view("edit/maps_toolbar") ?>
</div>

<style>
    .map-area{
        position: relative;
        height: 50vh;
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
    let userLat = getCookie('lat')
    let userLng = getCookie('long')
    let routingControl = null

    const map = L.map('map-board', {
        zoomControl: false
    }).setView([userLat, userLng], 13)

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

    const initMap = () => {
        setTimeout(() => {
            map.invalidateSize()
        }, 300)
    }

    const showDirection = (originLat, originLng, destinationLat, destinationLng) => {
        if (!originLat || !originLng) return
        if (routingControl) map.removeControl(routingControl)

        Swal.fire({
            title: 'Preparing the route...',
            text: 'Please wait a moment.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading()
            }
        })

        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(originLat, originLng),
                L.latLng(destinationLat, destinationLng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            show: false,
            createMarker: () => null,
            lineOptions: {
                styles: [
                    {
                        color: getComputedStyle(document.documentElement).getPropertyValue('--primaryColor').trim(),
                        opacity: 0.9,
                        weight: 6
                    }
                ]
            }
        })
        .on('routesfound', () => {
            Swal.close()
        })
        .on('routingerror', () => {
            Swal.fire({
                icon: 'error',
                title: 'Route Not Found',
                text: 'Unable to prepare the route.'
            })
        })
        .addTo(map)
    }

    const showPinOnMap = (lat, lng) => {
        const latLng = {
            lat: parseFloat(lat), lng: parseFloat(lng)
        }

        placeMarker(latLng)
        map.flyTo([latLng.lat, latLng.lng], 17, {
            animate: true,
            duration: 0.8
        })

        showDirection(userLat, userLng, latLng.lat, latLng.lng)
    }

    $(document).on('click', '#focus-map-button', function () {
        map.setView([userLat, userLng], 13)
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