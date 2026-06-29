const addContentCoor = (coor, targetLatId, targetLongId) => {
    $(`#${targetLatId}`).val(coor.lat)
    $(`#${targetLongId}`).val(coor.lng)
}

$(document).on('click', '.btn-direction', function () {
    const lat = $(this).data('lat')
    const lng = $(this).data('long')

    const gmapsUrl = `https://www.google.com/maps/dir/My+Location/${lat},${lng}`

    window.open(gmapsUrl, '_blank')
})

const switchMapType = (type, map, tileLayer) => {
    if (!['default','satellite','terrain'].includes(type)) return

    map.removeLayer(tileLayer)

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
    $('.map-type').removeClass('active')
    $(`.map-type[data-type="${type}"]`).addClass('active')
}

const getZoomFromRange = (val) => {
    switch (val) {
        case '3':   
            return 14
        case '5':   
            return 13
        case '15':  
            return 12
        case '30':  
            return 11
        case '100': 
            return 10
        case 'all': 
            return 9
        default:    
            return 11
    }
}

const check_nearest_pin = (with_recommend = false, check_name = false) => {
    const lat = $('#pin_lat').val().trim()
    const long = $('#pin_long').val().trim()
    let pin_name = check_name ? $('#pin_name').val().trim() : null
    if (pin_name === "") pin_name = null

    if (!lat || !long) return 

    Swal.showLoading()

    $.ajax({
        url: `/api/v1/pin/validate_new`,
        data: { lat, long, pin_name },
        dataType: 'json',
        contentType: 'application/json',
        headers: {
            'Authorization': `Bearer ${tokenKey}`
        },
    })
    .done(function (response) {
        Swal.hideLoading()
        const detail = response.data.detail
        const recommended = response.data.recommended

        $('#pin_address').val(detail.address)
        $('#pin_city').val(detail.city)
        $('#pin_village').val(detail.village)
        $('#pin_suburb').val(detail.suburb)
        $('#pin_country').val(detail.country)

        if (with_recommend) {
            $('#recommended-marker-holder').empty()
            if (recommended && recommended.length > 0) {
                $('#recommended-section').toggleClass('d-none d-block')

                recommended.forEach(dt => {
                    $('#recommended-marker-holder').append(`
                        <a class="tag bg-primary recommended-marker-btn" data-pin-name="${dt.name}" data-pin-lat="${dt.lat}" data-pin-long="${dt.lng}">${dt.name} <span title="Distance">(${dt.distance} m)</span></a>
                    `)
                })
            } else {
                $('#pin_name').val('')
                $('#recommended-section').toggleClass('d-block d-none')
            }
        }

        if (!response.is_found_near) {
            Swal.fire({
                title: 'Success!',
                text: 'No other pin detected near this coordinate. You are free to create.',
                icon: 'success'
            })
        } else {
            Swal.fire({
                title: 'Warning!',
                html: response.body.message,
                icon: 'info'
            })
        }
    })
    .fail(function (response) {
        Swal.hideLoading()

        const statusCode = response.status             
        if (statusCode === 400 || statusCode === 409) {
            const message = response.responseJSON?.message ?? 'Something went wrong.'

            Swal.fire({
                title: 'Failed',
                html: statusCode === 409 ? 'There is another marker who have same name. Make a unique one' : message,
                icon: 'warning'
            })
        } else if (response.status !== 404) {
            unknownErrorSwal()
        }
    })
}

const check_pin_name_availability = (pin_name, action) => {
    Swal.showLoading()
    $.ajax({
        url: `/api/v1/pin/validate_new`,
        data: { pin_name },
        dataType: 'json',
        contentType: 'application/json',
        headers: {
            'Authorization': `Bearer ${tokenKey}`
        },
    })
    .done(function (response) {
        Swal.close()
        action(false)
    })
    .fail(function (response) {
        Swal.close()
        
        if (response.status === 409) {
            action(true)
            Swal.fire({
                title: 'Failed!',
                text: 'There is another marker who have same name. Make a unique one',
                icon: 'error'
            })
        } else {
            unknownErrorSwal()
        }
    })
}

const renderMarkerItemShort = (dt) => {
    const imgEl = dt.pin_image ? `<img class="pin-item-img" src="${dt.pin_image}" alt="${dt.pin_name}" loading="lazy"/>` : `<div class="pin-item-img-placeholder"><i class="fa-solid fa-building"></i></div>`
    const isFavoriteEl = dt.is_favorite === 0 ? `<span class='position-absolute text-danger' style='top: -7.5px; right: -7.5px;'><i class='fa-solid fa-heart'></i></span>` : ''

    return `
        <div class="pin-item" data-id="${dt.pin_id}" data-name="${dt.pin_name}" data-lat="${dt.pin_lat}" data-lng="${dt.pin_long}">
            <div class="position-relative">
                ${imgEl}
                ${isFavoriteEl}
            </div>
            <div class="d-flex flex-column">
                <div class="text-sm fw-bold mb-1">${dt.pin_name}</div>
                <div class="d-flex gap-1">
                    <span class="tag bg-info">${dt.pin_category}</span>
                    <span class="tag bg-success"><i class="fa-solid fa-location-dot"></i> ${dt.pin_final_address}</span>
                </div>
            </div>
        </div>`
}

const renderVisitItemShort = (dt, type) => {
    return `
        <div class="visit-item ${type === "next" ? "text-end" : "text-start"}" data-id="${dt.id}">
            <label class="capitalize">${type}</label>
            <hr class="my-1">
            <div class="d-flex flex-column">
                <p class="text-sm fw-bold mb-0">Visit at ${dt.pin_name}</p>
                <div class="m-0">
                    <div class="tag bg-info"><i class="fa-solid fa-calendar"></i> ${datetimeText(dt.created_at)}</div>
                </div>
            </div>
        </div>`
}

let _marker = null

const placeMarker = (latLng, map) => {
    if (!map) return
    if (_marker) map.removeLayer(_marker)
    _marker = L.marker([latLng.lat, latLng.lng]).addTo(map)
}

const showPinOnMap = (lat, lng, map, routingControl, userLat, userLng) => {
    const latLng = { lat: parseFloat(lat), lng: parseFloat(lng) }
    placeMarker(latLng, map)

    const targetPoint = map.project([latLng.lat, latLng.lng], 17).subtract([map.getSize().x * 0.25, 0])
    const targetLatLng = map.unproject(targetPoint, 17)
    map.flyTo(targetLatLng, 17, { animate: true, duration: 0.8 })

    const newRoutingControl = showDirection(map, routingControl, userLat, userLng, latLng.lat, latLng.lng, '#pin_to_pin_distance_count', '#pin_to_pin_duration_count')
    $('#destination-info-section').removeClass('d-none')

    return newRoutingControl
}

const showDirection = (map, routingControl, originLat, originLng, destinationLat, destinationLng, distanceValHolder = null, durationValHolder = null, fitLeft = null) => {
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
        waypoints: [L.latLng(originLat, originLng), L.latLng(destinationLat, destinationLng)],
        routeWhileDragging: false,
        addWaypoints: false,
        draggableWaypoints: false,
        fitSelectedRoutes: fitLeft === null,
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
    .on('routesfound', (e) => {
        const route = e.routes[0]
        const distance = (route.summary.totalDistance / 1000).toFixed(2)
        const totalMinutes = Math.round(route.summary.totalTime / 60)
        const hours = Math.floor(totalMinutes / 60)
        const minutes = totalMinutes % 60
        const duration = hours > 0 ? `${hours} hr ${minutes} min` : `${minutes} min`

        if (distanceValHolder) $(distanceValHolder).text(`${distance} Km`)
        if (durationValHolder) $(durationValHolder).text(duration)
        if (fitLeft === true) {
            const bounds = L.latLngBounds([originLat, originLng], [destinationLat, destinationLng])
            map.flyToBounds(bounds, {
                paddingTopLeft: [20, 20],
                paddingBottomRight: [map.getSize().x * 0.5 + 20, 20],
                animate: true,
                duration: 1.2
            })
        }

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

    return routingControl
}