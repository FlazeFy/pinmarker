$(document).ready(function() {
    $(document).on('click', '.btn-add-pin', function() {
        const pin_name = $(this).closest('tr').find('.pin-name-holder').text()
        const coor_split = $(this).closest('tr').find('.pin-id-coor').val().split(',')

        selected_pin.push({
            id: coor_split[0],
            pin_name: pin_name,
            pin_lat: coor_split[1],
            pin_long: coor_split[2],
        })

        $(this).closest('.action-btn-holder').html(
            `<a class='btn btn-danger btn-remove-pin w-100' data-id='${coor_split[0]}'><i class='fa-solid fa-xmark'></i></a>`
        )

        let tagEl = `
            <a class='pin-name-btn remove me-2 mb-1 text-decoration-none'>
                <i class='fa-solid fa-location-dot'></i> ${pin_name}
                <input hidden class='d-none remove-from-id' value='${coor_split[0]}'>
            </a>
        `
        $('#selected-pin-holder').append(tagEl)

        markers.push({
            coords: { lat: parseFloat(coor_split[1]), lng: parseFloat(coor_split[2]) },
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red.png',
                scaledSize: { width: 40, height: 40 }
            },
            content: 
            `<div class='mt-4'>
                <h6>${pin_name}</h6>
                <a class='btn btn-dark remove-via-marker px-2 py-1' style='font-size:12px; font-size:12px;'>
                    <i class='fa-regular fa-circle-xmark'></i> Remove
                    <input hidden class='d-none remove-from-id' value='${coor_split[0]}'>
                </a>
            </div>`
        })

        addMarker(markers)
        initMap()
    })

    $(document).on('click', '.btn-remove-pin', function() {
        const pin_id = $(this).data('id')

        selected_pin = selected_pin.filter(pin => pin.id !== pin_id.toString())

        $(this).closest('.action-btn-holder').html(
            `<a class='btn btn-success btn-add-pin w-100' data-id='${pin_id}'><i class='fa-solid fa-plus'></i></a>`
        )

        $('#selected-pin-holder .remove-from-id').each(function() {
            if ($(this).val() === pin_id.toString()) {
                $(this).closest('.pin-name-btn').remove()
            }
        })

        const markerIndex = markers.findIndex(marker => marker.content.includes(pin_id.toString()))
        if (markerIndex !== -1) {
            markers.splice(markerIndex, 1)
        }

        addMarker(markers)
        initMap()
    })
})