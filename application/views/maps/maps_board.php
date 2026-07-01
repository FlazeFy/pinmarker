<div class="map-area">
    <div class="map-img-wrap">
        <div id="map-board"></div>
    </div>
    <?php $this->load->view("maps/maps_toolbar") ?>
    <?php $this->load->view("maps/maps_footer") ?>
</div>

<style>
    .map-area{
        position: relative;
        height: 70vh;
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
    let userRadius = null
    let previousMarkerPerFetch = $('#marker-per-fetch-select').val()

    // Initialize Map
    const map = L.map('map-board', {
        zoomControl: false
    }).setView([userLat, userLng], 11)

    const renderRadius = () => {
        const max_distance = $('#max-range-select').val()

        if (userRadius) {
            map.removeLayer(userRadius)
            userRadius = null
        }

        if (max_distance !== 'all') {
            userRadius = L.circle([userLat, userLng], {
                radius: parseInt(max_distance) * 1000,
                color: 'var(--primaryColor)',
                dashArray: '10, 10',
                fillColor: '#8b85ff',
                fillOpacity: 0.12,
                weight: 3
            }).addTo(map)
        }
    }

    $(document).ready(function () {
        const max_distance = $('#max-range-select').val() !== "all" ? parseInt($('#max-range-select').val()) : "all"
        let markers = []
        let oldMarkers = []

        L.control.zoom({ position: 'bottomright' }).addTo(map)
        let tileLayer = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            { attribution: '&copy; OpenStreetMap contributors' }
        ).addTo(map)

        let userMarker = L.circleMarker([userLat, userLng], {
            radius: 10,
            fillColor: 'var(--primaryColor)',
            color: '#ffffff',
            weight: 4,
            opacity: 1,
            fillOpacity: 1
        }).addTo(map)

        if (max_distance !== "all") renderRadius()

        const updateUserLocation = (lat, lng) => {
            userLat = lat
            userLng = lng
            storeCookie('lat', userLat)
            storeCookie('long', userLng)

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

            renderRadius()
            map.setView([userLat, userLng], 12)

            fetchNearbyPins()
        }

        const fetchNearbyPins = (page = 1) => {
            const max_distance = $('#max-range-select').val() !== "all" ? parseInt($('#max-range-select').val()) : null
            if (!$('.cat-item.active').length) $('.cat-name').eq(0).closest('.cat-item').addClass('active')
            const pin_category = $('.cat-item.active').find('.cat-name').data('val')
            const viewTypeSelect = $('#view-type-select').val()
            const search = $('#pin-name-search').val().trim()
            const is_favorite = viewTypeSelect === "favorite" ? "1" : "all"
            const is_visited = viewTypeSelect === "visited" ? "1" : viewTypeSelect === "unvisited" ? "0" : "all"
            const per_page = $('#marker-per-fetch-select').val()

            markers.forEach(marker => map.removeLayer(marker))
            markers = []

            $('.region-desc').text('Loading nearby pins...')
            $.ajax({
                url: `/api/v1/pin/maps`,
                data: {
                    search,
                    pin_category,
                    page,
                    per_page,
                    max_distance,
                    lat: userLat,
                    long: userLng,
                    is_favorite, 
                    is_visited,
                },
                headers: {
                    'Authorization': `Bearer ${tokenKey}`
                },
                method: 'GET',
                success: (response) => {
                    const data = response.data
                    const pins = data.data
                    const bounds = [[userLat, userLng]]
                    let listPinEl = ''

                    pins.forEach(dt => {
                        bounds.push([dt.pin_lat, dt.pin_long])
                        const marker = L.marker([dt.pin_lat, dt.pin_long]).addTo(map)

                        marker.bindPopup(`
                            <div class="place-popup">
                                <h3>${dt.pin_name}</h3>
                                <div class="popup-info">
                                    <span>${dt.pin_address ?? '-'}</span>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="popup-info">
                                            <span>Category</span>
                                            <p>${dt.pin_category}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="popup-info">
                                            <span>Total Visit</span>
                                            <p>${dt.total_visit}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="popup-info">
                                            <span>Distance</span>
                                            <p>${dt.distance} km</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        ${
                                            dt.last_visit_at ? `
                                                <div class="popup-info">
                                                    <span>Last Visit</span>
                                                    <p>${datetimeText(dt.last_visit_at)}</p>
                                                </div>
                                            ` : ''
                                        }
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <button class="btn btn-success w-100 set-direction" data-pin-lat="${dt.pin_lat}" data-pin-long="${dt.pin_long}" data-pin-name="${dt.pin_name}">Set Direction</button>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <a class="btn btn-primary" href="/DetailController/view/${dt.id}">Detail</a>
                                    </div>
                                </div>
                            </div>
                        `)

                        markers.push(marker)

                        const pinAddressEl = dt.pin_address ? `<span class='tag bg-success'><i class='fa-solid fa-location-dot'></i> ${dt.pin_address}</span>` : ""
                        const visitAtText = dt.last_visit_at ? ` • Last visit at ${datetimeText(dt.last_visit_at, false, 'date')}` : ''
                        const isFavoriteEl = dt.is_favorite == 1 ? "<span class='fav-dot'><i class='fa-solid fa-heart'></i></span>" : ""
                        const distanceEl = dt.distance ? `<div class="tag bg-primary">${dt.distance} Km</div>` : ""

                        listPinEl += `
                            <div class='activity-item mb-0' data-lat='${dt.pin_lat}' data-long='${dt.pin_long}'>
                                <div class='activity-thumb'>
                                    <img src='https://lh3.googleusercontent.com/aida-public/AB6AXuB9TgxRWJZ1lxyBC2boJYHByBkeSaroy5x0M-AVvRCH_M7rWkJDFoVc1Lykvj4iQd7LMnmKIZdneHmRaXwFC9lv7_R60HRIGCVjEjIOXZfaa7J7VxkudxcVJ4rY3Rgs-ylDPPCviUANS--Z29u4nWUV66EasfeFSHxoNl_DXhJTkoVFcwXP083QKchtEh0pwmn4zKaOzhGVc1BczkDGjALzZe6T1f9_UaS1XcyhLg9yioAdJ4m4iYi-5GEJreWku7OO99GdpvvHlmjT' alt=''>
                                    ${isFavoriteEl}
                                </div>
                                <div class='activity-info'>
                                    <div class='activity-name'>${dt.pin_name}</div>
                                    <div class='activity-meta'>${distanceEl}${visitAtText}</div>
                                    <div class='activity-tags'>
                                        ${pinAddressEl}
                                    </div>
                                </div>
                            </div>
                        `
                    })

                    if (bounds.length > 1) map.fitBounds(bounds, { padding: [50, 50]})
                    $('.region-desc').text(`${pins.length} places detected${max_distance ? ` within ${max_distance} km radius.`:''}`)

                    max_distance ? $('#place-nearby-radius-text').html(`<i class="fa-solid fa-circle-info"></i> in ${max_distance} Km Radius`) : $('#place-nearby-radius-text').empty()
                    $('#visit-percentage-text').html(`
                        <div class="d-flex align-items-end gap-2">${data.visited_percentage}%${
                            max_distance ? `
                                <div class="d-flex flex-column">
                                    <span style="font-size: var(--textXMD);">${data.average_distance.toFixed(2)} Km</span>
                                    <span style="font-size: var(--textSM); font-weight: 500;">Avg Distance</span>
                                </div>
                            `:''}
                        </div>
                    `)

                    const $cat = $(`.cat-name[data-val="${pin_category}"]`).first().closest('.cat-item')
                    $cat.addClass('active')
                    $cat.find('.cat-body').html(`<div class="pt-2">${listPinEl}</div>`)

                    const isSearchNameActive = search !== ""
                    if (isSearchNameActive) {
                        map.setView([pins[0].pin_lat, pins[0].pin_long], zoomValueFocusMarker)
                    }
                },
                error: (response) => {
                    if (response.status === 401) return failedAuth()
                    $('.region-desc').text('Failed fetch nearby pins.')
                }
            })
        }

        navigator.permissions.query({ name: 'geolocation' }).then(permission => {
            if (permission.state === 'granted') {
                if (userLat === null && userLng === null) {
                    navigator.geolocation.getCurrentPosition(position => {
                        updateUserLocation(position.coords.latitude, position.coords.longitude)
                    })
                } else {
                    fetchNearbyPins()
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
                            fetchNearbyPins()
                        })
                    } else {
                        fetchNearbyPins()
                    }
                })
            }
        })

        $('.map-type').on('click', function () {
            const type = $(this).data('type')
            switchMapType(type, map, tileLayer)
            addUrlParam('map_type', type)
        })

        let routingControl = null
        $(document).on('click', '.cat-item:not(.active)', function() {
            $('.cat-item').removeClass('active')
            $('.cat-body').empty()
            $(this).toggleClass('active')
            exitRoute()
            fetchNearbyPins()
        })

        $(document).on('change', '#max-range-select', function() {
            renderRadius()
        })

        $(document).on('change', '#max-range-select, #view-type-select', function() {
            fetchNearbyPins()
            addUrlParam($(this).attr('id') === 'max-range-select' ? 'max_distance' : 'view_type', $(this).val())
        })

        const exitRoute = () => {
            $('.exit-route-button').addClass('d-none')
            $('.region-label').text('Current Region Focus')
            map.removeControl(routingControl)
            routingControl = null

            markers = oldMarkers
            markers.forEach(marker => marker.addTo(map))
            oldMarkers = []

            const max_distance = $('#max-range-select').val() !== "all" ? parseInt($('#max-range-select').val()) : null
            $('.region-desc').text(`${markers.length} places detected${max_distance ? ` within ${max_distance} km radius.`:''}`)
        }

        $(document).on('click', '.activity-item', function() {
            const lat = $(this).data('lat')
            const long = $(this).data('long')

            if (routingControl) exitRoute()

            map.setView([lat, long], zoomValueFocusMarker)
            openPopUpMap(markers, lat, long)
        })

        let pinSearchDebounce = null
        $(document).on('input', '#pin-name-search', function() {
            clearTimeout(pinSearchDebounce)
            pinSearchDebounce = setTimeout(() => {
                fetchNearbyPins()
                addUrlParam('search', $(this).val())
            }, debouncerTime)
        })

        $(document).on('change', '#marker-per-fetch-select', function() {
            const val = $(this).val()

            if (val === "all") {
                Swal.fire({
                    icon: 'question',
                    title: 'Fetch all marker?',
                    text: 'This may take a long time if you have thousands of markers.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Later',
                    confirmButtonColor: '#635bff'
                }).then(result => {
                    if (result.isConfirmed) {
                        fetchNearbyPins()
                        addUrlParam('limit', val)
                    } else {
                        $(this).val(previousMarkerPerFetch)
                    }
                })
            } else {
                fetchNearbyPins()
                addUrlParam('limit', val)
                previousMarkerPerFetch = val
            }
        })

        setTimeout(() => {
            renderRadius()
            map.invalidateSize()
        }, 100)

        // Validate query param
        const validateParams = () => {
            if (search !== "") $('#pin-name-search').val(search)
            !['20','50','150','all'].includes(limit) ? removeUrlParam('limit') : $('#marker-per-fetch-select').val(limit)
            !['favorite','visited','unvisited','all'].includes(view_type) ? removeUrlParam('view_type') : $('#view-type-select').val(view_type)
            !['default','satellite','terrain'].includes(map_type) ? removeUrlParam('map_type') : switchMapType(map_type, map, tileLayer)
            !['5','15','30','100','all'].includes(max_distance) ? removeUrlParam('max_distance') : $('#max-range-select').val(max_distance)
        }
        
        $(document).ready(function () {
            validateParams()
        })

        $(document).on('click', '.set-direction', function () {
            const lat = $(this).data('pin-lat')
            const lng = $(this).data('pin-long')
            const pinName = $(this).data('pin-name')

            oldMarkers = markers
            markers.forEach(marker => {
                const mLat = marker.getLatLng().lat
                const mLng = marker.getLatLng().lng
                if (mLat !== parseFloat(lat) || mLng !== parseFloat(lng)) map.removeLayer(marker)
            })

            $('.region-label').text('Current Route')
            $('.region-desc').html(`From your location to <b>${pinName}</b> with distance <b class="route-distance">...</b> and duration about <b class="route-duration">...</b>`)
            $('.exit-route-button').removeClass('d-none')

            routingControl = showDirection(map, routingControl, parseFloat(userLat), parseFloat(userLng), lat, lng, '.route-distance', '.route-duration')
        })

        $(document).on('click', '.exit-route-button', function () {
            exitRoute()
        })
    })
</script>