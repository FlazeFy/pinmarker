<div class="d-flex justify-content-between p-2 text-start">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        let userLat = getCookie('lat')
        let userLng = getCookie('long')

        // Initialize Map
        const map = L.map('map', {
            zoomControl: false
        }).setView([userLat, userLng], getZoomFromRange($('.marker-range-select').val()))

        // Zoom Control
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map)

        // OpenStreet Tile
        let tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map)

        let placesMarkers = []
        let pinsMarkers = []
        let userMarker = L.circleMarker([userLat, userLng], {
            radius: 10,
            fillColor: 'var(--primaryColor)',
            color: '#ffffff',
            weight: 4,
            opacity: 1,
            fillOpacity: 1
        }).addTo(map)

        let userRadius = null
        const updateUserRadius = () => {
            const val = $('.marker-range-select').val()

            if (userRadius) {
                map.removeLayer(userRadius)
                userRadius = null
            }

            if (val === 'all') return

            userRadius = L.circle([userLat, userLng], {
                radius: parseInt(val) * 1000,
                color: 'var(--primaryColor)',
                dashArray: '10, 10',
                fillColor: '#8b85ff',
                fillOpacity: 0.12,
                weight: 3
            }).addTo(map)
        }

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
            fetchNearbyPins()
            updateUserRadius()
        }

        const loadingMapItem = (holder) => {
            Swal.fire({
                title: 'Fetching nearby marker...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            })
            $(holder).append(`
                <div class="skeleton-loading map-item-skeleton"></div>
                <div class="skeleton-loading map-item-skeleton"></div>
                <div class="skeleton-loading map-item-skeleton"></div>
            `) 
        }

        // Fetch Nearby Place
        const fetchNearbyPlaces = () => {
            const holder = '#global-place-holder'

            loadingMapItem(holder)
            $.ajax({
                url: `/api/v1/location/reverse?lat=${userLat}&long=${userLng}`,
                method: 'GET',
                success: (response) => {
                    Swal.close()
                    if (!response.data) {
                        $(holder).html(`
                            <div class="text-none text-start">
                                <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch nearby place
                            </div>
                        `)
                        return
                    }

                    // Clean old marker
                    $(holder).empty()
                    placesMarkers.forEach(m => map.removeLayer(m))
                    placesMarkers = []

                    const nearby = response.data.nearby
                    let html = ''
                    nearby.forEach(place => {
                        const marker = L.marker([place.lat, place.lng]).addTo(map)
                        placesMarkers.push(marker)

                        marker.bindPopup(`
                            <div class="place-popup">
                                <h3>${place.name}</h3>
                                <div class="popup-info">
                                    <span>${place.amenity}</span>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="popup-info">
                                        <span>Distance</span>
                                        <p>${place.distance} km</p>
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

                    $(holder).prepend(html)
                    $('#total-other-location-text').text(`(${nearby.length})`)
                },
                error: () => {
                    Swal.close()
                    $(holder).html(`
                        <div class="text-none text-start">
                            <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch nearby place
                        </div>
                    `)
                }
            })
        }

        const fetchNearbyPins = (page = 1) => {
            const max_distance = $('.marker-range-select').val() !== "all" ? parseInt($('.marker-range-select').val()) : null
            const viewTypeSelect = $('#view-type-select').val()
            const search = $('#pin-name-search').val().trim()
            const per_page = $('.marker-limit-select').val()
            const holder = '#pinmarker-place-holder'

            loadingMapItem(holder)
            $.ajax({
                url: `/api/v1/pin/maps`,
                data: {
                    search,
                    page,
                    per_page,
                    max_distance,
                    lat: userLat,
                    long: userLng
                },
                method: 'GET',
                success: (response) => {
                    Swal.close()
                    if (!response.data) {
                        $(holder).html(`
                            <div class="text-none text-start">
                                <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch nearby pins
                            </div>
                        `)
                        return
                    }

                    // Clean old marker
                    $(holder).empty()
                    pinsMarkers.forEach(m => map.removeLayer(m))
                    pinsMarkers = []

                    const data = response.data
                    const pins = data.data
                    let html = ''
                    pins.forEach(dt => {
                        const marker = L.marker([dt.pin_lat, dt.pin_long]).addTo(map)
                        pinsMarkers.push(marker)

                        marker.bindPopup(`
                            <div class="place-popup">
                                <h3>${dt.pin_name}</h3>
                                <div class="popup-info">
                                    <div>
                                        <span>${dt.pin_address}</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="popup-info">
                                            <span>Distance</span>
                                            <h5>${dt.distance} m</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="popup-info">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-4">
                                    <button class="btn btn-primary">See Detail</button>
                                    <button class="btn btn-success btn-direction" data-lat="${dt.pin_lat}" data-long="${dt.pin_long}">
                                        Set Direction
                                    </button>
                                </div>
                            </div>
                        `)

                        html += `
                            <div class="map-item" data-lat="${dt.pin_lat}" data-lng="${dt.pin_long}">
                                <div class="d-flex flex-column align-items-start">
                                    <b>${dt.pin_name}</b>
                                    <a class="tag bg-primary mt-1">${dt.pin_category}</a>
                                </div>
                                <span class="distance-badge">${dt.distance}m</span>
                            </div>
                        `
                    })

                    $(holder).prepend(html)
                    $('#total-pinmarker-text').text(`(${data.per_page})`)
                    $('.total-marker-hint').text(data.per_page)
                    
                    if (pins.length > 0) {
                        $(holder).addClass('open').css('max-height', '40vh')
                        $('#pinmarker-section-header').find('.map-section-chevron').addClass('rotated')
                    }
                },
                error: () => {
                    Swal.close()
                    $(holder).html(`
                        <div class="text-none text-start">
                            <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch nearby marker
                        </div>
                    `)
                }
            })
        }

        const fetchPlace = () => {
            fetchNearbyPlaces()
            fetchNearbyPins()
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

        const openFullscreen = () => {
            $('section').not('#map-section').addClass('d-none')
            $('.map-placeholder').css({
                width: '100vw',
                height: '100vh',
                position: 'fixed',
                top: 0,
                left: 0,
                zIndex: 9999,
                borderRadius: 0,
                transition: 'all 0.3s ease'
            }).removeClass('mt-4')
            map.invalidateSize()

            $('.content').css({
                maxWidth: 'none !important',
                marginInline: 0,
                padding: 0
            })
            $('#btn-fullscreen').addClass('active bg-danger text-white')
            $('#fullscreen-icon').removeClass('fa-expand').addClass('fa-xmark')
        }

        const exitFullscreen = () => {
            $('section').removeClass('d-none')
            $('.map-placeholder').css({
                width: '',
                height: '',
                position: '',
                top: '',
                left: '',
                zIndex: '',
                borderRadius: '',
                transition: 'all 0.3s ease'
            }).addClass('mt-4')
            map.invalidateSize()

            $('.content').css({
                maxWidth: '1400px',
                marginInline: 'auto',
                padding: 'var(--spaceLG)'
            })
            $('#btn-fullscreen').removeClass('active bg-danger text-white')
            $('#fullscreen-icon').removeClass('fa-xmark').addClass('fa-expand')
        }

        $('#btn-fullscreen').on('click', function () {
            const isFullscreen = $('#btn-fullscreen').hasClass('active')

            if (!isFullscreen) {
                openFullscreen()
                $(this).addClass('.active')
            } else {
                if (isExplorer) removeUrlParam('explorer')
                exitFullscreen()
                $(this).removeClass('.active')
            }
        })

        document.addEventListener('fullscreenchange', function () {
            if (!document.fullscreenElement) $('#fullscreen-icon').removeClass('fa-compress').addClass('fa-expand')
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

        // Handle toolbar action
        $('.map-type').on('click', function () {
            const type = $(this).data('type')
            switchMapType(type, map, tileLayer)
            addUrlParam('map_type', type)
        })

        $(document).on('change', '.marker-range-select', function () {
            updateUserRadius()
            fetchPlace()
            map.setView([userLat, userLng], getZoomFromRange($(this).val()))
        })

        $(document).on('change', '.marker-limit-select', function () {
            fetchNearbyPins()
        })

        let searchDebounce = null
        $(document).on('input', '#pin-name-search', function () {
            clearTimeout(searchDebounce)
            const val = $(this).val().trim()

            searchDebounce = setTimeout(() => {
                val ? addUrlParam('search', val) : removeUrlParam('search')
                fetchNearbyPins()
            }, debouncerTime)
        })

        // Validate query param
        const validateParams = () => {
            if (search !== "") $('#pin-name-search').val(search)
            !['10','20','50','150','all'].includes(limit) ? removeUrlParam('limit') : $('.marker-limit-select').val(limit)
            !['default','satellite','terrain'].includes(map_type) ? removeUrlParam('map_type') : switchMapType(map_type, map, tileLayer)
            !['3','5','15','30','100','all'].includes(max_distance) ? removeUrlParam('max_distance') : $('.marker-range-select').val(max_distance)
        }
        
        $(document).ready(function () {
            if (isExplorer || (search !== "" && search)) {
                openFullscreen()
                $(this).addClass('.active')
            }

            validateParams()
            updateUserRadius()
            if (userLat && userLng) fetchPlace()
        })
    })
</script>