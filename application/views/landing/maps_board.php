<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    
<div class="d-flex justify-content-between position-sticky container p-2" style="top: 10px; z-index: 997;">
    <div>
        <h2 class="section-title">Nearest Markers</h2>
        <p class="section-sub mb-0">Discover what's around you</p>
    </div>
</div>

<div class="map-placeholder mt-4">
    <div id="map"></div>
    <?php $this->load->view("landing/maps_header"); ?>
    <div class="map-content">
        <?php $this->load->view("landing/maps_right_bar"); ?>
        <?php $this->load->view("landing/maps_footer"); ?>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        let userLat = getCookie('lat')
        let userLng = getCookie('long')

        // Initialize Map
        const map = L.map('map', {
            zoomControl: false
        }).setView([userLat, userLng], 11)

        // Zoom Control
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map)

        // OpenStreet Tile
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map)

        let userMarker = L.circleMarker([userLat, userLng], {
            radius: 10,
            fillColor: 'var(--primaryColor)',
            color: '#ffffff',
            weight: 4,
            opacity: 1,
            fillOpacity: 1
        }).addTo(map)

        let userRadius = L.circle([userLat, userLng], {
            radius: 15000,
            color: 'var(--primaryColor)',
            dashArray: '10, 10',
            fillColor: '#8b85ff',
            fillOpacity: 0.12,
            weight: 3
        }).addTo(map)

        // Update User Coordinate
        const updateUserLocation = (lat, lng) => {
            userLat = lat
            userLng = lng

            storeCookie('lat', userLat)
            storeCookie('long', userLng)

            // Remove default marker & radius
            map.removeLayer(userMarker)
            map.removeLayer(userRadius)

            userMarker = L.circleMarker([userLat, userLng], {
                radius: 10,
                fillColor: 'var(--primaryColor)',
                color: '#ffffff',
                weight: 4,
                opacity: 1,
                fillOpacity: 1
            }).addTo(map)

            userRadius = L.circle([userLat, userLng], {
                radius: 15000,
                color: 'var(--primaryColor)',
                dashArray: '10, 10',
                fillColor: '#8b85ff',
                fillOpacity: 0.12,
                weight: 3
            }).addTo(map)

            // Move Camera
            map.setView([userLat, userLng], 12)

            fetchNearbyPlaces()
        }

        // Fetch Nearby Place
        const fetchNearbyPlaces = () => {
            $('.map-card').find('.global-place-list').remove()

            $('.map-card').prepend(`
                <div class="global-place-list">
                    <div class="map-item-skeleton"></div>
                    <div class="map-item-skeleton"></div>
                    <div class="map-item-skeleton"></div>
                </div>
            `)

            $.ajax({
                url: `/api/v1/location/reverse?lat=${userLat}&long=${userLng}`,
                method: 'GET',
                success: (response) => {
                    if (!response.data) {
                        $('.global-place-list').html(`
                            <div class="alert bg-danger map-item">
                                <span>Failed fetch nearby place</span>
                            </div>
                        `)
                        return
                    }

                    const nearby = response.data.nearby
                    let html = ''
                    $('.global-place-list').remove()

                    nearby.forEach(place => {
                        const marker = L.marker([place.lat, place.lng]).addTo(map)

                        marker.bindPopup(`
                            <div class="place-popup">
                                <h3>${place.name}</h3>
                                <p class="popup-address">${place.amenity}</p>
                                <hr>
                                <div class="popup-info">
                                    <div class="popup-icon green">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <span>Distance</span>
                                        <h5>${place.distance} m</h5>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-4">
                                    <button class="btn btn-primary">See Detail</button>
                                    <button class="btn btn-success btn-direction" data-lat="${place.lat}" data-long="${place.lng}">
                                        Set Direction
                                    </button>
                                </div>
                            </div>
                        `)

                        html += `
                            <div class="map-item" data-lat="${place.lat}" data-lng="${place.lng}">
                                <div class="d-flex flex-column align-items-start">
                                    <b>${place.name}</b>
                                    <a class="tag bg-primary mt-1">${place.amenity}</a>
                                </div>
                                <span class="distance-badge">${place.distance}m</span>
                            </div>
                        `
                    })

                    $('#global-place-holder').prepend(html)
                },
                error: () => {
                    $('.global-place-list').html(`
                        <div class="map-item">
                            <span>Failed fetch nearby place</span>
                        </div>
                    `)
                }
            })
        }

        // Focus Marker
        $(document).on('click', '.map-item', function () {
            const lat = $(this).data('lat')
            const lng = $(this).data('lng')

            map.setView([lat, lng], 16)
        })

        $('#btn-focus-me').on('click', function () {
            if (userLat && userLng) {
                map.setView([userLat, userLng], 15)
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Location Not Available',
                    text: 'Your current location has not been set yet.',
                    confirmButtonColor: '#635bff'
                })
            }
        })

        // Check Location Permission
        navigator.permissions.query({ name: 'geolocation' }).then(permission => {
            if (permission.state === 'granted') {
                if (userLat === null && userLng === null) {
                    navigator.geolocation.getCurrentPosition(position => {
                        updateUserLocation(position.coords.latitude, position.coords.longitude)
                    })
                } else {
                    fetchNearbyPlaces()
                }
            } else {
                Swal.fire({
                    icon: 'question',
                    title: 'Enable Location Access?',
                    text: 'Allow location access to discover nearby markers.',
                    showCancelButton: true,
                    confirmButtonText: 'Allow',
                    cancelButtonText: 'Later',
                    confirmButtonColor: '#635bff'
                }).then(result => {
                    if (result.isConfirmed && navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            updateUserLocation(position.coords.latitude, position.coords.longitude)
                        }, () => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Location Access Denied',
                                text: 'Unable to access your location.'
                            })
                        })
                    } else {
                        fetchNearbyPlaces()
                    }
                })
            }
        })
    })
</script>