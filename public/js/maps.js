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