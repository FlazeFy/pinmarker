const addContentCoor = (coor, targetLatId, targetLongId) => {
    coor = coor.toJSON()
    $(`#${targetLatId}`).val(coor['lat'])
    $(`#${targetLongId}`).val(coor['lng'])
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