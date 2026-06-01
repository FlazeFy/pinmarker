<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<div class="map-area">
    <div class="map-img-wrap">
        <div id="map-board"></div>
    </div>
    <?php $this->load->view("maps/maps_toolbar") ?>
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
    .map-toolbar{
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .map-type-wrap{
        background: #fff;
        padding: var(--spaceXSM);
        border-radius: var(--roundedLG);
        box-shadow: 0 4px 16px rgba(0,0,0,.1);
    }
    .map-type-wrap h6{
        margin-left: var(--spaceMini);
        font-size: var(--textSM);
        font-weight: 600;
        margin-bottom: var(--spaceMini);
    }
    .map-type-group{
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        border-radius: var(--roundedMD);
        padding: 4px;
        display: flex;
        gap: 2px;
        border: 1px solid rgba(199,196,216,.3);
    }
    .map-type{
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
        white-space: nowrap;
    }
    .map-type.active{
        background: var(--primaryColor);
        color: #fff;
        box-shadow: 0 3px 10px rgba(99,91,255,.3);
    }
    .map-type:hover:not(.active){
        background: #f2f3f7;
    }
    .map-range-select{
        min-width: 120px;
        padding: var(--spaceSM);
        border: 1px solid rgba(199,196,216,.3);
        border-radius: var(--roundedMD);
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        font-size: var(--textXSM);
        font-weight: 700;
        margin-bottom: 0;
        color: #464555;
        outline: none;
        cursor: pointer;
    }
    .map-range-select:focus{
        border-color: var(--primaryColor);
    }
    .region-bar{
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
        z-index: 1000;
    }
    .region-focus{
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
    .region-label{
        font-size: var(--textXSM);
        font-weight: 700;
        color: var(--primaryColor);
        margin-bottom: 2px;
    }
    .region-desc{
        font-size: var(--textXSM);
        color: #464555;
        line-height: 1.5;
    }
    .leaflet-control-zoom{
        border-radius: var(--roundedMD)!important;
        overflow: hidden;
    }
    .leaflet-popup-content-wrapper{
        border-radius: var(--roundedMD);
    }
    @media(max-width: 768px){
        .map-toolbar{
            top: 12px;
            right: 12px;
            left: 12px;
            flex-direction: column;
            gap: 8px;
        }
        .map-type-group{
            overflow-x: auto;
        }
        .map-range-select{
            width: 100%;
        }
    }
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let userLat = getCookie('lat')
    let userLng = getCookie('long')
    let userRadius = null
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

        

        // Zoom Control
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map)

        // OpenStreet Tile
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

        if (max_distance !== "all") {
            renderRadius()
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

            renderRadius()

            // Move Camera
            map.setView([userLat, userLng], 12)

            fetchNearbyPins()
        }

        // Fetch Nearby Pin
        const fetchNearbyPins = (page = 1) => {
            const max_distance = parseInt($('#max-range-select').val())
            if (!$('.cat-item.active').length) $('.cat-name').eq(0).closest('.cat-item').addClass('active')
            const pin_category = $('.cat-item.active').find('.cat-name').data('val')
            markers.forEach(marker => map.removeLayer(marker))
            markers = []

            $('.region-desc').text('Loading nearby pins...')
            $.ajax({
                url: `/api/v1/pin/maps`,
                data: {
                    pin_category,
                    page,
                    max_distance,
                    lat: userLat,
                    long: userLng
                },
                method: 'GET',
                success: (response) => {
                    const pins = response.data.data
                    const bounds = [[userLat, userLng]]
                    let listPinEl = ''

                    pins.forEach(dt => {
                        bounds.push([dt.pin_lat, dt.pin_long])
                        const marker = L.marker([dt.pin_lat, dt.pin_long]).addTo(map)

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
                                <div class="popup-info mt-2">
                                    <div>
                                        <span>Distance</span>
                                        <h5>${dt.distance} km</h5>
                                    </div>
                                </div>
                                <div class="popup-info mt-2">
                                    <div>
                                        <span>Total Visit</span>
                                        <h5>${dt.total_visit}</h5>
                                    </div>
                                </div>
                            </div>
                        `)

                        markers.push(marker)

                        const pinAddressEl = dt.pin_address ? `<span class='tag bg-success'><i class='fa-solid fa-location-dot'></i> ${dt.pin_address}</span>` : ""
                        const createdAtText = datetimeText(dt.created_at)
                        const isFavoriteEl = dt.is_favorite == 1 ? "<span class='fav-dot'><i class='fa-solid fa-heart'></i></span>" : ""
                        const distanceEl = dt.distance ? ` • <div class="tag bg-primary">${dt.distance} Km</div>` : ""

                        listPinEl += `
                            <div class='activity-item mb-0'>
                                <div class='activity-thumb'>
                                    <img src='https://lh3.googleusercontent.com/aida-public/AB6AXuB9TgxRWJZ1lxyBC2boJYHByBkeSaroy5x0M-AVvRCH_M7rWkJDFoVc1Lykvj4iQd7LMnmKIZdneHmRaXwFC9lv7_R60HRIGCVjEjIOXZfaa7J7VxkudxcVJ4rY3Rgs-ylDPPCviUANS--Z29u4nWUV66EasfeFSHxoNl_DXhJTkoVFcwXP083QKchtEh0pwmn4zKaOzhGVc1BczkDGjALzZe6T1f9_UaS1XcyhLg9yioAdJ4m4iYi-5GEJreWku7OO99GdpvvHlmjT' alt=''>
                                    ${isFavoriteEl}
                                </div>
                                <div class='activity-info'>
                                    <div class='activity-name'>${dt.pin_name}</div>
                                    <div class='activity-meta'>${createdAtText}${distanceEl}</div>
                                    <div class='activity-tags'>
                                        ${pinAddressEl}
                                    </div>
                                </div>
                            </div>
                        `
                    })

                    if (bounds.length > 1) map.fitBounds(bounds, { padding: [50, 50]})
                    $('.region-desc').text(`${pins.length} places detected within ${max_distance} km radius.`)
                    $('#place-nearby-radius-text').text(max_distance)
                    
                    const $cat = $(`.cat-name[data-val="${pin_category}"]`).first().closest('.cat-item')
                    $cat.addClass('active')
                    $cat.find('.cat-body').html(`<div class="pt-2">${listPinEl}</div>`)
                },
                error: () => {
                    $('.region-desc').text('Failed fetch nearby pins.')
                }
            })
        }

        // Check Location Permission
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
            $('.map-type').removeClass('active')
            $(this).addClass('active')

            map.removeLayer(tileLayer)
            const type = $(this).data('type')
            if (type === 'satellite') {
                tileLayer = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                    { attribution: '&copy; Esri' }
                )
            } else if (type === 'terrain') {
                tileLayer = L.tileLayer(
                    'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
                    { attribution: '&copy; OpenTopoMap' }
                )
            } else {
                tileLayer = L.tileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    { attribution: '&copy; OpenStreetMap contributors' }
                )
            }

            tileLayer.addTo(map)
        })

        $(document).on('click', '.cat-item', function(e) {
            $('.cat-item').removeClass('active')
            $('.cat-body').empty()
            $(this).toggleClass('active')
            fetchNearbyPins()
        })

        $(document).on('click', '#max-range-select', function(e) {
            renderRadius()
            fetchNearbyPins()
        })

        setTimeout(() => {
            renderRadius()
            map.invalidateSize()
        }, 100)
    })
</script>