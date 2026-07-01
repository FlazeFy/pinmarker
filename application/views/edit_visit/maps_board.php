<div class="map-area mb-4">
    <div id="map-board"></div>
    <div class="map-left-overlay">
        <?php $this->load->view("edit/maps_toolbar") ?>
    </div>
    <div class="map-right-panel">
        <div class="map-form-box">
            <?php $this->load->view('edit_visit/form'); ?>
            <?php $this->load->view('edit_visit/near_period'); ?>
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

    $(document).ready(function () {
        setTimeout(() => map.invalidateSize(), 300)

        const applyLocation = (lat, lng) => {
            userLat = lat
            userLng = lng
            placeUserMarker(lat, lng)
            const targetPoint = map.project([lat, lng], 15).subtract([map.getSize().x * 0.25, 0])
            map.setView(map.unproject(targetPoint, 15), 15)
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
            const targetPoint = map.project([userLat, userLng], 15).subtract([map.getSize().x * 0.25, 0])
            map.flyTo(map.unproject(targetPoint, 15), 15, { animate: true, duration: 0.8 })
        })
    })

    let routingControl = null

    const fetchVisit = (id) => {
        $.ajax({
            url: `/api/v1/visit/by_id/${id}`,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                const data = response.data
                const dt = new Date(data.created_at)
                const date = dt.toISOString().split('T')[0]
                const hour = dt.toTimeString().slice(0, 5)

                $('#pin_name').html(renderMarkerItemShort(data))
                $('#pin-name-display').text(data.pin_name ?? '-')
                $('#visit_by').val(data.visit_by)
                $('#visit_date').val(date)
                $('#visit_hour').val(hour)
                $('#visit_with').val(data.visit_with)
                $('#visit_desc').val(data.visit_desc)

                data.visit_before ? $('#previous-visit-button').html(renderVisitItemShort(data.visit_before, 'previous')).show() : $('#previous-visit-button').hide()
                data.visit_after ? $('#next-visit-button').html(renderVisitItemShort(data.visit_after, 'next')).show() : $('#next-visit-button').hide()

                const pinLat = parseFloat(data.pin_lat)
                const pinLng = parseFloat(data.pin_long)

                showPinOnMap(pinLat, pinLng, map)
                setTimeout(() => {
                    const bounds = L.latLngBounds([pinLat, pinLng], [parseFloat(userLat), parseFloat(userLng)])

                    map.flyToBounds(bounds, {
                        paddingTopLeft: [20, 20],
                        paddingBottomRight: [map.getSize().x * 0.5 + 20, 20],
                        animate: true,
                        duration: 1.2
                    })

                    map.once('moveend', () => {
                        routingControl = showDirection(map, routingControl, parseFloat(userLat), parseFloat(userLng), pinLat, pinLng, '#pin_to_pin_distance_count', '#pin_to_pin_duration_count', true)
                        $('#destination-info-section').removeClass('d-none')
                    })
                }, 350)
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
                $('.map-form-box').append(`
                    <div class="text-center py-3">
                        <span class="tag bg-danger"><i class="fa-solid fa-triangle-exclamation"></i> Failed fetch visit</span>
                    </div>
                `)
            }
        })
    }

    $(document).on('click','.pin-item',function(){
        window.location.href = `/DetailController/view/${$(this).data('id')}`
    })

    fetchVisit('<?= $id ?>')
</script>